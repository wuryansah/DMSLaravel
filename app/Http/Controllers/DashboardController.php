<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Document;
use App\Models\SharedFolder;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isAdmin = $user->isAdmin();

        $query = Document::with(['user', 'category']);

        if (! $isAdmin) {
            $query->where('user_id', $user->id);
        }

        $stats = [
            'total_documents' => $isAdmin ? Document::count() : Document::where('user_id', $user->id)->count(),
            'my_documents' => Document::where('user_id', $user->id)->count(),
            'categories' => Category::count(),
            'recent_documents' => $query->latest()->take(5)->get(),
            'shared_count' => SharedFolder::where('owner_id', $user->id)->count(),
            'shared_with_me' => SharedFolder::where('shared_with_id', $user->id)->count(),
        ];

        return view('dashboard.index', compact('stats', 'isAdmin'));
    }
}
