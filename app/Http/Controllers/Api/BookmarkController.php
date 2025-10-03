<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bookmark;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class BookmarkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->user()->bookmarks()->with(['category', 'tags']);

        // Apply filters
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('slug', $request->tag);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->boolean('favorites')) {
            $query->favorites();
        }

        // Sort options
        $sortBy = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        $bookmarks = $query->paginate($request->get('per_page', 20));

        return response()->json([
            'data' => $bookmarks->items(),
            'meta' => [
                'current_page' => $bookmarks->currentPage(),
                'last_page' => $bookmarks->lastPage(),
                'per_page' => $bookmarks->perPage(),
                'total' => $bookmarks->total(),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'url' => 'required|url|max:2048',
            'description' => 'nullable|string|max:1000',
            'category_id' => 'nullable|exists:categories,id',
            'status' => 'in:active,archived,broken',
            'favorite' => 'boolean',
            'private' => 'boolean',
            'tags' => 'array',
            'tags.*' => 'string|max:50',
        ]);

        $data = $request->all();
        $data['user_id'] = $request->user()->id;

        // Generate title from URL if not provided
        if (empty($data['title'])) {
            $data['title'] = $this->generateTitleFromUrl($data['url']);
        }

        // Get favicon
        $data['favicon'] = $this->getFavicon($data['url']);

        // Check for duplicates
        $existingBookmark = $request->user()->bookmarks()
            ->where('url', $data['url'])
            ->first();

        if ($existingBookmark) {
            throw ValidationException::withMessages([
                'url' => ['This URL has already been bookmarked.']
            ]);
        }

        $bookmark = Bookmark::create($data);

        // Attach tags
        if ($request->filled('tags')) {
            $tagIds = $this->processTagNames($request->tags);
            $bookmark->tags()->sync($tagIds);
        }

        $bookmark->load(['category', 'tags']);

        return response()->json([
            'message' => 'Bookmark created successfully',
            'data' => $bookmark
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Bookmark $bookmark)
    {
        if ($bookmark->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $bookmark->load(['category', 'tags', 'user']);
        $bookmark->incrementVisits();

        return response()->json(['data' => $bookmark]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bookmark $bookmark)
    {
        if ($bookmark->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url|max:2048',
            'description' => 'nullable|string|max:1000',
            'category_id' => 'nullable|exists:categories,id',
            'status' => 'in:active,archived,broken',
            'favorite' => 'boolean',
            'private' => 'boolean',
            'tags' => 'array',
            'tags.*' => 'string|max:50',
        ]);

        $data = $request->all();

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

        $bookmark->load(['category', 'tags']);

        return response()->json([
            'message' => 'Bookmark updated successfully',
            'data' => $bookmark
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bookmark $bookmark)
    {
        if ($bookmark->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $bookmark->delete();

        return response()->json(['message' => 'Bookmark deleted successfully']);
    }

    /**
     * Quick save for Chrome extension
     */
    public function quickSave(Request $request)
    {
        $request->validate([
            'url' => 'required|url|max:2048',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $data = $request->all();
        $data['user_id'] = $request->user()->id;

        // Generate title from URL if not provided
        if (empty($data['title'])) {
            $data['title'] = $this->generateTitleFromUrl($data['url']);
        }

        // Get favicon
        $data['favicon'] = $this->getFavicon($data['url']);

        // Check for duplicates
        $existingBookmark = $request->user()->bookmarks()
            ->where('url', $data['url'])
            ->first();

        if ($existingBookmark) {
            return response()->json([
                'message' => 'Bookmark already exists',
                'data' => $existingBookmark
            ], 200);
        }

        $bookmark = Bookmark::create($data);
        $bookmark->load(['category', 'tags']);

        return response()->json([
            'message' => 'Bookmark saved successfully',
            'data' => $bookmark
        ], 201);
    }

    /**
     * Chrome extension endpoint
     */
    public function chromeExtension(Request $request)
    {
        return $this->quickSave($request);
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
