<?php

/**
 * ================================================================================
 * BOOKMARK MANAGEMENT CONTROLLER
 * ================================================================================
 *
 * 🏢 VENDOR: Eastlink Cloud Pvt. Ltd.
 * 👨‍💻 AUTHOR: Developer Team
 * 📅 CREATED: October 2025
 * 🔄 UPDATED: October 2025
 * 📧 CONTACT: puran@eastlink.net.np
 * 📞 PHONE: +977-01-4101181
 * 📱 DEVELOPER: +977-9801901140
 * 💼 BUSINESS: +977-9801901141
 * 🏢 ADDRESS: Tripureshwor, Kathmandu, Nepal
 *
 * 📋 DESCRIPTION:
 * Advanced bookmark management controller handling CRUD operations,
 * advanced search, filtering, sorting, and bulk operations for the
 * enterprise bookmark management system.
 *
 * 🎯 FEATURES:
 * - Multi-user bookmark management
 * - Advanced search & filtering
 * - Table sorting with visual indicators
 * - Bulk operations support
 * - Category and tag management
 * - Visit tracking and analytics
 * - Export/Import capabilities
 *
 * ⚖️ LICENSE: Commercial Enterprise License
 * 🔒 COPYRIGHT: © 2025 Professional Web Solutions Ltd.
 * ================================================================================
 */

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookmarkRequest;
use App\Http\Requests\UpdateBookmarkRequest;
use App\Models\Bookmark;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class BookmarkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Auth::user()->bookmarks()->with(['category', 'tags']);

        // Advanced Search & Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('url', 'LIKE', "%{$search}%")
                  ->orWhereHas('tags', function ($tagQuery) use ($search) {
                      $tagQuery->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Tag filter
        if ($request->filled('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->tag}%")
                  ->orWhere('slug', $request->tag);
            });
        }

        // Domain filter - search within URL since domain is an accessor
        if ($request->filled('domain')) {
            $domain = $request->domain;
            $query->where('url', 'LIKE', "%{$domain}%");
        }

        // Status filters
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'active':
                    $query->whereNull('archived_at')->where('is_broken', false);
                    break;
                case 'archived':
                    $query->whereNotNull('archived_at');
                    break;
                case 'broken':
                    $query->where('is_broken', true);
                    break;
            }
        }

        // Favorite filter
        if ($request->filled('favorite')) {
            $query->where('favorite', $request->boolean('favorite'));
        }

        // Privacy filter
        if ($request->filled('private')) {
            $query->where('private', $request->boolean('private'));
        }

        // Date range filters
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Quick filters
        if ($request->filled('recent')) {
            $days = (int) $request->recent;
            $query->where('created_at', '>=', now()->subDays($days));
        }

        if ($request->filled('popular')) {
            $query->where('visits', '>', 0);
        }

        // Sorting options
        $sortBy = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');

        // Custom sorting logic
        switch ($sortBy) {
            case 'title':
                $query->orderBy('title', $sortDirection);
                break;
            case 'visits':
                $query->orderBy('visits', $sortDirection);
                break;
            case 'url':
                $query->orderBy('url', $sortDirection);
                break;
            case 'updated_at':
                $query->orderBy('updated_at', $sortDirection);
                break;
            case 'category':
                $query->leftJoin('categories', 'bookmarks.category_id', '=', 'categories.id')
                      ->orderBy('categories.name', $sortDirection)
                      ->select('bookmarks.*');
                break;
            default:
                $query->orderBy('created_at', $sortDirection);
                break;
        }

        // Secondary sort for consistency
        if ($sortBy !== 'created_at') {
            $query->orderBy('created_at', 'desc');
        }

        // Pagination with custom per_page
        $perPage = $request->get('per_page', 12);
        $perPage = in_array($perPage, [12, 24, 48, 96]) ? $perPage : 12;

        $bookmarks = $query->paginate($perPage)->withQueryString();

        // Get categories and tags for filters
        $categories = Auth::user()->categories()
            ->withCount('bookmarks')
            ->orderBy('name')
            ->get();

        $popularTags = auth()->user()->bookmarks()
            ->with('tags')
            ->get()
            ->pluck('tags')
            ->flatten()
            ->groupBy('id')
            ->map(function ($tags) {
                return $tags->first();
            })
            ->sortByDesc(function ($tag) {
                return $tag->bookmarks_count ?? 0;
            })
            ->take(20);

        return view('bookmarks.index', compact('bookmarks', 'categories', 'popularTags'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = auth()->user()->categories()->rootCategories()->get();
        $tags = Tag::alphabetical()->get();

        return view('bookmarks.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookmarkRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        // Generate title from URL if not provided
        if (empty($data['title'])) {
            $data['title'] = $this->generateTitleFromUrl($data['url']);
        }

        // Get favicon
        $data['favicon'] = $this->getFavicon($data['url']);

        // Check for duplicates
        $existingBookmark = auth()->user()->bookmarks()
            ->where('url', $data['url'])
            ->first();

        if ($existingBookmark) {
            return back()->withErrors(['url' => 'This URL has already been bookmarked.']);
        }

        $bookmark = Bookmark::create($data);

        // Attach tags
        if ($request->filled('tags')) {
            $tagIds = $this->processTagNames($request->tags);
            $bookmark->tags()->sync($tagIds);
        }

        // Log activity
        Activity::log('created', $bookmark);

        return redirect()->route('bookmarks.index')
            ->with('success', 'Bookmark created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Bookmark $bookmark)
    {
        $this->authorize('view', $bookmark);

        $bookmark->load(['category', 'tags', 'user']);
        $bookmark->incrementVisits();

        // Log activity
        Activity::log('viewed', $bookmark);

        return view('bookmarks.show', compact('bookmark'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bookmark $bookmark)
    {
        $this->authorize('update', $bookmark);

        $categories = auth()->user()->categories()->rootCategories()->get();
        $tags = Tag::alphabetical()->get();
        $selectedTags = $bookmark->tags->pluck('name')->toArray();

        return view('bookmarks.edit', compact('bookmark', 'categories', 'tags', 'selectedTags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookmarkRequest $request, Bookmark $bookmark)
    {
        $this->authorize('update', $bookmark);

        $data = $request->validated();

        // Update favicon if URL changed
        if ($bookmark->url !== $data['url']) {
            $data['favicon'] = $this->getFavicon($data['url']);
        }

        $bookmark->update($data);

        // Update tags
        if ($request->filled('tags')) {
            $tagIds = $this->processTagNames($request->tags);
            $bookmark->tags()->sync($tagIds);
        }

        // Log activity
        Activity::log('updated', $bookmark);

        return redirect()->route('bookmarks.index')
            ->with('success', 'Bookmark updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bookmark $bookmark)
    {
        $this->authorize('delete', $bookmark);

        // Log activity before deletion
        Activity::log('deleted', $bookmark);

        $bookmark->delete();

        return redirect()->route('bookmarks.index')
            ->with('success', 'Bookmark deleted successfully!');
    }

    /**
     * Toggle favorite status
     */
    public function toggleFavorite(Bookmark $bookmark)
    {
        $this->authorize('update', $bookmark);

        $bookmark->update(['favorite' => !$bookmark->favorite]);

        Activity::log($bookmark->favorite ? 'favorited' : 'unfavorited', $bookmark);

        return response()->json(['favorite' => $bookmark->favorite]);
    }

    /**
     * Visit bookmark and redirect to URL
     */
    public function visit(Bookmark $bookmark)
    {
        $this->authorize('view', $bookmark);

        $bookmark->incrementVisits();
        Activity::log('visited', $bookmark);

        return redirect($bookmark->url);
    }

    /**
     * Public bookmark view
     */
    public function public(string $shortCode)
    {
        $bookmark = Bookmark::where('short_code', $shortCode)
            ->where('private', false)
            ->firstOrFail();

        $bookmark->load(['category', 'tags', 'user']);
        $bookmark->incrementVisits();

        return view('bookmarks.public', compact('bookmark'));
    }

    /**
     * Bulk operations
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,archive,unarchive,favorite,unfavorite',
            'bookmarks' => 'required|array',
            'bookmarks.*' => 'exists:bookmarks,id',
        ]);

        $bookmarks = auth()->user()->bookmarks()->whereIn('id', $request->bookmarks);

        switch ($request->action) {
            case 'delete':
                $bookmarks->delete();
                break;
            case 'archive':
                $bookmarks->update(['status' => 'archived']);
                break;
            case 'unarchive':
                $bookmarks->update(['status' => 'active']);
                break;
            case 'favorite':
                $bookmarks->update(['favorite' => true]);
                break;
            case 'unfavorite':
                $bookmarks->update(['favorite' => false]);
                break;
        }

        return back()->with('success', 'Bulk action completed successfully!');
    }

    /**
     * Helper methods
     */
    private function generateTitleFromUrl($url)
    {
        try {
            $response = Http::timeout(5)->get($url);
            if ($response->successful()) {
                preg_match('/<title[^>]*>(.*?)<\/title>/si', $response->body(), $matches);
                return isset($matches[1]) ? trim($matches[1]) : parse_url($url, PHP_URL_HOST);
            }
        } catch (\Exception $e) {
            // Fallback to domain name
        }

        return parse_url($url, PHP_URL_HOST) ?: 'Untitled';
    }

    private function getFavicon($url)
    {
        $domain = parse_url($url, PHP_URL_SCHEME) . '://' . parse_url($url, PHP_URL_HOST);
        return $domain . '/favicon.ico';
    }

    private function processTagNames($tagNames)
    {
        $tagIds = [];

        foreach ($tagNames as $tagName) {
            $tag = Tag::firstOrCreate(
                ['name' => trim($tagName)],
                ['slug' => Str::slug(trim($tagName))]
            );
            $tag->incrementUsage();
            $tagIds[] = $tag->id;
        }

        return $tagIds;
    }
}
