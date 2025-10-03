<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User Management
            ['name' => 'users.view', 'display_name' => 'View Users', 'category' => 'User Management'],
            ['name' => 'users.create', 'display_name' => 'Create Users', 'category' => 'User Management'],
            ['name' => 'users.update', 'display_name' => 'Update Users', 'category' => 'User Management'],
            ['name' => 'users.delete', 'display_name' => 'Delete Users', 'category' => 'User Management'],

            // Role Management
            ['name' => 'roles.view', 'display_name' => 'View Roles', 'category' => 'Role Management'],
            ['name' => 'roles.create', 'display_name' => 'Create Roles', 'category' => 'Role Management'],
            ['name' => 'roles.update', 'display_name' => 'Update Roles', 'category' => 'Role Management'],
            ['name' => 'roles.delete', 'display_name' => 'Delete Roles', 'category' => 'Role Management'],

            // Permission Management
            ['name' => 'permissions.view', 'display_name' => 'View Permissions', 'category' => 'Permission Management'],
            ['name' => 'permissions.create', 'display_name' => 'Create Permissions', 'category' => 'Permission Management'],
            ['name' => 'permissions.update', 'display_name' => 'Update Permissions', 'category' => 'Permission Management'],
            ['name' => 'permissions.delete', 'display_name' => 'Delete Permissions', 'category' => 'Permission Management'],

            // Bookmark Management
            ['name' => 'bookmarks.view-all', 'display_name' => 'View All Bookmarks', 'category' => 'Bookmark Management'],
            ['name' => 'bookmarks.create', 'display_name' => 'Create Bookmarks', 'category' => 'Bookmark Management'],
            ['name' => 'bookmarks.update', 'display_name' => 'Update Bookmarks', 'category' => 'Bookmark Management'],
            ['name' => 'bookmarks.delete', 'display_name' => 'Delete Bookmarks', 'category' => 'Bookmark Management'],
            ['name' => 'bookmarks.export', 'display_name' => 'Export Bookmarks', 'category' => 'Bookmark Management'],
            ['name' => 'bookmarks.import', 'display_name' => 'Import Bookmarks', 'category' => 'Bookmark Management'],

            // Category Management
            ['name' => 'categories.view-all', 'display_name' => 'View All Categories', 'category' => 'Category Management'],
            ['name' => 'categories.create', 'display_name' => 'Create Categories', 'category' => 'Category Management'],
            ['name' => 'categories.update', 'display_name' => 'Update Categories', 'category' => 'Category Management'],
            ['name' => 'categories.delete', 'display_name' => 'Delete Categories', 'category' => 'Category Management'],

            // Tag Management
            ['name' => 'tags.view-all', 'display_name' => 'View All Tags', 'category' => 'Tag Management'],
            ['name' => 'tags.create', 'display_name' => 'Create Tags', 'category' => 'Tag Management'],
            ['name' => 'tags.update', 'display_name' => 'Update Tags', 'category' => 'Tag Management'],
            ['name' => 'tags.delete', 'display_name' => 'Delete Tags', 'category' => 'Tag Management'],

            // System Settings
            ['name' => 'settings.view', 'display_name' => 'View Settings', 'category' => 'System Settings'],
            ['name' => 'settings.update', 'display_name' => 'Update Settings', 'category' => 'System Settings'],

            // Analytics
            ['name' => 'analytics.view', 'display_name' => 'View Analytics', 'category' => 'Analytics'],
            ['name' => 'analytics.export', 'display_name' => 'Export Analytics', 'category' => 'Analytics'],
        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission['name'],
                'guard_name' => 'web',
                'category' => $permission['category']
            ]);
        }

        // Create roles
        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $moderatorRole = Role::create(['name' => 'moderator', 'guard_name' => 'web']);
        $userRole = Role::create(['name' => 'user', 'guard_name' => 'web']);

        // Assign permissions to roles

        // Admin gets all permissions
        $adminRole->givePermissionTo(Permission::all());

        // Moderator gets limited permissions
        $moderatorRole->givePermissionTo([
            'users.view',
            'bookmarks.view-all',
            'bookmarks.create',
            'bookmarks.update',
            'bookmarks.delete',
            'categories.view-all',
            'categories.create',
            'categories.update',
            'categories.delete',
            'tags.view-all',
            'tags.create',
            'tags.update',
            'tags.delete',
            'analytics.view',
        ]);

        // User gets basic permissions
        $userRole->givePermissionTo([
            'bookmarks.create',
            'bookmarks.update',
            'bookmarks.delete',
            'categories.create',
            'categories.update',
            'categories.delete',
            'tags.create',
            'tags.update',
            'tags.delete',
        ]);

        // Create admin user if doesn't exist
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@bookmark.com'],
            [
                'name' => 'Administrator',
                'password' => bcrypt('admin123'),
                'email_verified_at' => now(),
            ]
        );

        $adminUser->assignRole('admin');

        // Create moderator user
        $moderatorUser = User::firstOrCreate(
            ['email' => 'moderator@bookmark.com'],
            [
                'name' => 'Moderator',
                'password' => bcrypt('moderator123'),
                'email_verified_at' => now(),
            ]
        );

        $moderatorUser->assignRole('moderator');

        $this->command->info('Roles and permissions created successfully!');
        $this->command->info('Admin User: admin@bookmark.com / admin123');
        $this->command->info('Moderator User: moderator@bookmark.com / moderator123');
    }
}
