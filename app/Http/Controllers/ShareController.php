<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\SharedFolder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShareController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $sharedWithMe = Document::whereHas('sharedFolders', function ($query) use ($user) {
            $query->where('shared_with_id', $user->id);
        })->with(['user', 'category', 'latestVersion'])->latest()->paginate(10);

        return view('shares.index', compact('sharedWithMe'));
    }

    public function store(Request $request, Document $document)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        if ($document->user_id !== Auth::id() && ! Auth::user()->isAdmin()) {
            abort(403, 'You can only share your own documents.');
        }

        if ($request->user_id == Auth::id()) {
            return back()->with('error', 'You cannot share a document with yourself.');
        }

        $exists = SharedFolder::where('document_id', $document->id)
            ->where('shared_with_id', $request->user_id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'This document is already shared with this user.');
        }

        SharedFolder::create([
            'document_id' => $document->id,
            'owner_id' => Auth::id(),
            'shared_with_id' => $request->user_id,
        ]);

        return back()->with('success', 'Document shared successfully.');
    }

    public function destroy(Document $document, User $user)
    {
        if ($document->user_id !== Auth::id() && ! Auth::user()->isAdmin()) {
            abort(403, 'You can only unshare your own documents.');
        }

        SharedFolder::where('document_id', $document->id)
            ->where('shared_with_id', $user->id)
            ->delete();

        return back()->with('success', 'Document unshared successfully.');
    }
}
