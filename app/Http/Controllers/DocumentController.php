<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Document;
use App\Models\SharedFolder;
use App\Models\User;
use App\Models\Version;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->isAdmin();

        $query = Document::with(['user', 'category', 'latestVersion', 'sharedFolders']);

        if (! $isAdmin) {
            $query->where('user_id', $user->id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('tags', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('user') && $isAdmin) {
            $query->where('user_id', $request->user);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $documents = $query->latest()->paginate(10);
        $categories = Category::all();

        return view('documents.index', compact('documents', 'categories', 'isAdmin'));
    }

    public function create()
    {
        $categories = Category::all();
        $users = User::where('id', '!=', Auth::id())->get();

        return view('documents.create', compact('categories', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,png,jpg,jpeg,gif|max:20480',
            'share_with' => 'nullable|array',
            'share_with.*' => 'exists:users,id',
        ]);

        $file = $request->file('file');
        $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)).'_'.time().'.'.$file->getClientOriginalExtension();
        $path = $file->storeAs('documents', $filename, 'public');

        $tags = $request->tags ? array_map('trim', explode(',', $request->tags)) : [];

        $document = Document::create([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'file_path' => $path,
            'user_id' => Auth::id(),
            'tags' => $tags,
        ]);

        Version::create([
            'document_id' => $document->id,
            'file_path' => $path,
            'version_number' => 1,
            'notes' => 'Initial version',
            'user_id' => Auth::id(),
        ]);

        if ($request->has('share_with')) {
            foreach ($request->share_with as $userId) {
                SharedFolder::create([
                    'document_id' => $document->id,
                    'owner_id' => Auth::id(),
                    'shared_with_id' => $userId,
                ]);
            }
        }

        return redirect()->route('documents.show', $document)
            ->with('success', 'Document uploaded successfully.');
    }

    public function show(Document $document)
    {
        $user = Auth::user();

        if (! $user->isAdmin() && $document->user_id !== $user->id) {
            abort(403, 'Access denied.');
        }

        $document->load(['user', 'category', 'versions.user', 'sharedWithUsers']);

        $canShare = $user->isAdmin() || $document->user_id === $user->id;
        $users = User::where('id', '!=', $user->id)->get();

        return view('documents.show', compact('document', 'canShare', 'users'));
    }

    public function edit(Document $document)
    {
        $this->authorize('update', $document);
        $categories = Category::all();

        return view('documents.edit', compact('document', 'categories'));
    }

    public function update(Request $request, Document $document)
    {
        $this->authorize('update', $document);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg,gif|max:20480',
        ]);

        $document->update([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)).'_'.time().'.'.$file->getClientOriginalExtension();
            $path = $file->storeAs('documents', $filename, 'public');

            $document->update(['file_path' => $path]);

            $newVersionNumber = $document->current_version + 1;

            Version::create([
                'document_id' => $document->id,
                'file_path' => $path,
                'version_number' => $newVersionNumber,
                'notes' => $request->version_notes ?? 'Updated version',
                'user_id' => Auth::id(),
            ]);
        }

        if ($request->tags) {
            $tags = array_map('trim', explode(',', $request->tags));
            $document->update(['tags' => $tags]);
        }

        return redirect()->route('documents.show', $document)
            ->with('success', 'Document updated successfully.');
    }

    public function destroy(Document $document)
    {
        $this->authorize('delete', $document);

        foreach ($document->versions as $version) {
            Storage::disk('public')->delete($version->file_path);
        }

        Storage::disk('public')->delete($document->file_path);

        $document->delete();

        return redirect()->route('documents.index')
            ->with('success', 'Document deleted successfully.');
    }

    public function download(Document $document)
    {
        $user = Auth::user();

        if (! $user->isAdmin() && $document->user_id !== $user->id) {
            abort(403, 'Access denied.');
        }

        if (! Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'File not found.');
        }

        return Storage::disk('public')->download(
            $document->file_path,
            $document->title.'.'.pathinfo($document->file_path, PATHINFO_EXTENSION)
        );
    }

    public function downloadVersion(Document $document, Version $version)
    {
        $user = Auth::user();

        if (! $user->isAdmin() && $document->user_id !== $user->id) {
            abort(403, 'Access denied.');
        }

        if ($version->document_id !== $document->id) {
            abort(404, 'Version not found.');
        }

        if (! Storage::disk('public')->exists($version->file_path)) {
            abort(404, 'File not found.');
        }

        return Storage::disk('public')->download(
            $version->file_path,
            $document->title.'_v'.$version->version_number.'.'.pathinfo($version->file_path, PATHINFO_EXTENSION)
        );
    }
}
