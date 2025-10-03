<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Display a listing of permissions
     */
    public function index()
    {
        $permissions = Permission::all()->groupBy('category');

        return view('admin.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new permission
     */
    public function create()
    {
        $categories = Permission::distinct('category')->pluck('category')->filter();

        return view('admin.permissions.create', compact('categories'));
    }

    /**
     * Store a newly created permission
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'unique:permissions,name'],
            'display_name' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'category' => ['required', 'string'],
        ]);

        Permission::create([
            'name' => $validated['name'],
            'guard_name' => 'web',
            'category' => $validated['category'],
        ]);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission created successfully.');
    }

    /**
     * Show the form for editing the specified permission
     */
    public function edit(Permission $permission)
    {
        $categories = Permission::distinct('category')->pluck('category')->filter();

        return view('admin.permissions.edit', compact('permission', 'categories'));
    }

    /**
     * Update the specified permission
     */
    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'unique:permissions,name,' . $permission->id],
            'display_name' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'category' => ['required', 'string'],
        ]);

        $permission->update([
            'name' => $validated['name'],
            'category' => $validated['category'],
        ]);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission updated successfully.');
    }

    /**
     * Remove the specified permission
     */
    public function destroy(Permission $permission)
    {
        // Check if permission is assigned to any roles
        if ($permission->roles->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete permission that is assigned to roles.');
        }

        $permission->delete();

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission deleted successfully.');
    }
}
