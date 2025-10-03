<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Activity;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = auth()->user()->categories()
            ->with(['children', 'parent'])
            ->withCount('bookmarks')
            ->orderBy('sort_order')
            ->get();

        // Group categories by parent for better display
        $rootCategories = $categories->whereNull('parent_id');

        return view('categories.index', compact('categories', 'rootCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parentCategories = auth()->user()->categories()
            ->rootCategories()
            ->orderBy('name')
            ->get();

        return view('categories.create', compact('parentCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'color' => 'required|string|size:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'icon' => 'required|string|max:50',
        ]);

        // Ensure parent belongs to the authenticated user if specified
        if ($request->parent_id) {
            $parent = auth()->user()->categories()->findOrFail($request->parent_id);
        }

        $data = $request->all();
        $data['user_id'] = auth()->id();

        // Set sort order
        $maxSort = auth()->user()->categories()
            ->where('parent_id', $request->parent_id)
            ->max('sort_order');
        $data['sort_order'] = ($maxSort ?? 0) + 1;

        $category = Category::create($data);

        // Log activity
        Activity::log('created', $category, ['type' => 'category']);

        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $this->authorize('view', $category);

        $category->load(['children', 'parent']);

        $bookmarks = $category->bookmarks()
            ->with(['tags', 'category'])
            ->latest()
            ->paginate(20);

        return view('categories.show', compact('category', 'bookmarks'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $this->authorize('update', $category);

        $parentCategories = auth()->user()->categories()
            ->where('id', '!=', $category->id)
            ->rootCategories()
            ->orderBy('name')
            ->get();

        return view('categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $this->authorize('update', $category);

        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'color' => 'required|string|size:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'icon' => 'required|string|max:50',
        ]);

        // Ensure parent belongs to the authenticated user if specified
        if ($request->parent_id) {
            $parent = auth()->user()->categories()->findOrFail($request->parent_id);

            // Prevent circular reference
            if ($request->parent_id == $category->id) {
                return back()->withErrors(['parent_id' => 'A category cannot be its own parent.']);
            }
        }

        $data = $request->all();
        $category->update($data);

        // Log activity
        Activity::log('updated', $category, ['type' => 'category']);

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);

        // Check if category has bookmarks
        if ($category->bookmarks()->count() > 0) {
            return back()->withErrors(['delete' => 'Cannot delete category with bookmarks. Please move or delete bookmarks first.']);
        }

        // Move child categories to parent or root
        $category->children()->update(['parent_id' => $category->parent_id]);

        // Log activity before deletion
        Activity::log('deleted', $category, ['type' => 'category', 'name' => $category->name]);

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully!');
    }

    /**
     * Update sort order via AJAX
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'categories' => 'required|array',
            'categories.*.id' => 'required|exists:categories,id',
            'categories.*.sort_order' => 'required|integer|min:0',
        ]);

        foreach ($request->categories as $categoryData) {
            $category = auth()->user()->categories()->findOrFail($categoryData['id']);
            $category->update(['sort_order' => $categoryData['sort_order']]);
        }

        return response()->json(['message' => 'Order updated successfully']);
    }
}
