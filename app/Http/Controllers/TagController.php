<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Bookmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function index(Request $request)
    {
        $query = Tag::withCount(['bookmarks' => function ($query) {
            $query->where('user_id', Auth::id());
        }])
        ->whereHas('bookmarks', function ($query) {
            $query->where('user_id', Auth::id());
        })
        ->orderBy('bookmarks_count', 'desc');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%");
        }

        // Filter by popularity
        if ($request->filled('filter')) {
            $filter = $request->get('filter');
            switch ($filter) {
                case 'popular':
                    $query->having('bookmarks_count', '>=', 5);
                    break;
                case 'recent':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'alphabetical':
                    $query->orderBy('name', 'asc');
                    break;
            }
        }

        $tags = $query->paginate(24);

        // Get user's bookmark count for each tag
        $userBookmarkCounts = [];
        foreach ($tags as $tag) {
            $userBookmarkCounts[$tag->id] = $tag->bookmarks()
                ->where('user_id', Auth::id())
                ->count();
        }

        // Get total statistics
        $totalTags = Tag::whereHas('bookmarks', function ($query) {
            $query->where('user_id', Auth::id());
        })->count();

        $totalBookmarksWithTags = Bookmark::where('user_id', Auth::id())
            ->whereHas('tags')
            ->count();

        $averageTagsPerBookmark = $totalBookmarksWithTags > 0
            ? round(Bookmark::where('user_id', Auth::id())->withCount('tags')->get()->avg('tags_count'), 1)
            : 0;

        return view('tags.index', compact(
            'tags',
            'userBookmarkCounts',
            'totalTags',
            'totalBookmarksWithTags',
            'averageTagsPerBookmark'
        ));
    }

    public function show(Tag $tag, Request $request)
    {
        // Get user's bookmarks with this tag
        $bookmarksQuery = $tag->bookmarks()
            ->where('user_id', Auth::id())
            ->with(['category', 'tags']);

        // Search within tag's bookmarks
        if ($request->filled('search')) {
            $search = $request->get('search');
            $bookmarksQuery->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('url', 'like', "%{$search}%");
            });
        }

        // Sort bookmarks
        $sort = $request->get('sort', 'created_at');
        switch ($sort) {
            case 'title':
                $bookmarksQuery->orderBy('title', 'asc');
                break;
            case 'updated_at':
                $bookmarksQuery->orderBy('updated_at', 'desc');
                break;
            case 'clicks':
                $bookmarksQuery->orderBy('clicks', 'desc');
                break;
            default:
                $bookmarksQuery->orderBy('created_at', 'desc');
        }

        $bookmarks = $bookmarksQuery->paginate(20);

        // Get related tags (tags that appear with this tag)
        $relatedTags = Tag::whereHas('bookmarks', function ($query) use ($tag) {
            $query->whereHas('tags', function ($subQuery) use ($tag) {
                $subQuery->where('tags.id', $tag->id);
            })->where('user_id', Auth::id());
        })
        ->where('id', '!=', $tag->id)
        ->withCount(['bookmarks' => function ($query) {
            $query->where('user_id', Auth::id());
        }])
        ->orderBy('bookmarks_count', 'desc')
        ->limit(10)
        ->get();

        // Calculate tag statistics
        $userBookmarkCount = $tag->bookmarks()->where('user_id', Auth::id())->count();
        $totalTagUsage = $tag->bookmarks()->count();
        $tagUsagePercentage = $totalTagUsage > 0
            ? round(($userBookmarkCount / $totalTagUsage) * 100, 1)
            : 0;

        return view('tags.show', compact(
            'tag',
            'bookmarks',
            'relatedTags',
            'userBookmarkCount',
            'totalTagUsage',
            'tagUsagePercentage'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:tags,name',
            'color' => 'nullable|string|regex:/^#[0-9A-F]{6}$/i',
        ]);

        $tag = Tag::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'color' => $request->color ?? '#6c757d',
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'tag' => $tag,
                'message' => 'Tag created successfully!'
            ]);
        }

        return redirect()->route('tags.index')
            ->with('success', 'Tag created successfully!');
    }

    public function update(Request $request, Tag $tag)
    {
        Gate::authorize('update', $tag);

        $request->validate([
            'name' => 'required|string|max:50|unique:tags,name,' . $tag->id,
            'color' => 'nullable|string|regex:/^#[0-9A-F]{6}$/i',
        ]);

        $tag->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'color' => $request->color ?? $tag->color,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'tag' => $tag,
                'message' => 'Tag updated successfully!'
            ]);
        }

        return redirect()->route('tags.show', $tag)
            ->with('success', 'Tag updated successfully!');
    }

    public function destroy(Tag $tag)
    {
        Gate::authorize('delete', $tag);

        // Check if tag is being used by current user
        $userBookmarkCount = $tag->bookmarks()->where('user_id', Auth::id())->count();

        if ($userBookmarkCount > 0) {
            return redirect()->route('tags.show', $tag)
                ->with('error', 'Cannot delete tag that is still in use. Remove it from all bookmarks first.');
        }

        $tagName = $tag->name;

        // If no other users are using this tag, delete it completely
        $totalUsage = $tag->bookmarks()->count();
        if ($totalUsage == 0) {
            $tag->delete();
            $message = "Tag '{$tagName}' has been permanently deleted.";
        } else {
            $message = "Tag '{$tagName}' has been removed from your bookmarks.";
        }

        return redirect()->route('tags.index')
            ->with('success', $message);
    }

    public function autocomplete(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $tags = Tag::where('name', 'like', "%{$query}%")
            ->orderBy('name')
            ->limit(10)
            ->get(['id', 'name', 'color']);

        return response()->json($tags->map(function ($tag) {
            return [
                'id' => $tag->id,
                'name' => $tag->name,
                'color' => $tag->color,
                'value' => $tag->name,
                'label' => $tag->name
            ];
        }));
    }
}
