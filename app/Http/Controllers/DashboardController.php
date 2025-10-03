<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Activity;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();

        // Statistics
        $stats = [
            'total_bookmarks' => $user->bookmarks()->count(),
            'favorites' => $user->bookmarks()->where('favorite', true)->count(),
            'categories' => $user->categories()->count(),
            'total_visits' => $user->bookmarks()->sum('visits'),
        ];

        // Recent bookmarks
        $recentBookmarks = $user->bookmarks()
            ->with(['category', 'tags'])
            ->latest()
            ->limit(5)
            ->get();

        // Most visited bookmarks
        $popularBookmarks = $user->bookmarks()
            ->with(['category', 'tags'])
            ->where('visits', '>', 0)
            ->orderBy('visits', 'desc')
            ->limit(5)
            ->get();

        // Recent activities
        $recentActivities = $user->activities()
            ->with('subject')
            ->latest()
            ->limit(10)
            ->get();

        // Top categories
        $topCategories = $user->categories()
            ->withCount('bookmarks')
            ->orderBy('bookmarks_count', 'desc')
            ->limit(5)
            ->get();

        // Tag cloud
        $topTags = Tag::whereHas('bookmarks', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orderBy('usage_count', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard', compact(
            'stats',
            'recentBookmarks',
            'popularBookmarks',
            'recentActivities',
            'topCategories',
            'topTags'
        ));
    }
}
