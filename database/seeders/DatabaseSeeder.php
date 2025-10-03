<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@bookmarks.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create test user
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        // Create categories
        $devCategory = \App\Models\Category::create([
            'user_id' => $user->id,
            'name' => 'Development',
            'color' => '#007bff',
            'icon' => 'code-slash',
            'sort_order' => 1,
        ]);

        $newsCategory = \App\Models\Category::create([
            'user_id' => $user->id,
            'name' => 'News & Blogs',
            'color' => '#28a745',
            'icon' => 'newspaper',
            'sort_order' => 2,
        ]);

        $toolsCategory = \App\Models\Category::create([
            'user_id' => $user->id,
            'name' => 'Tools & Resources',
            'color' => '#ffc107',
            'icon' => 'tools',
            'sort_order' => 3,
        ]);

        // Create tags
        $laravelTag = \App\Models\Tag::create(['name' => 'Laravel', 'slug' => 'laravel']);
        $phpTag = \App\Models\Tag::create(['name' => 'PHP', 'slug' => 'php']);
        $jsTag = \App\Models\Tag::create(['name' => 'JavaScript', 'slug' => 'javascript']);
        $tutorialTag = \App\Models\Tag::create(['name' => 'Tutorial', 'slug' => 'tutorial']);

        // Create bookmarks
        $bookmark1 = \App\Models\Bookmark::create([
            'user_id' => $user->id,
            'title' => 'Laravel Documentation',
            'url' => 'https://laravel.com/docs',
            'description' => 'Official Laravel framework documentation',
            'category_id' => $devCategory->id,
            'favorite' => true,
            'visits' => 15,
        ]);
        $bookmark1->tags()->attach([$laravelTag->id, $phpTag->id]);

        $bookmark2 = \App\Models\Bookmark::create([
            'user_id' => $user->id,
            'title' => 'GitHub',
            'url' => 'https://github.com',
            'description' => 'Git repository hosting service',
            'category_id' => $toolsCategory->id,
            'favorite' => true,
            'visits' => 45,
        ]);

        $bookmark3 = \App\Models\Bookmark::create([
            'user_id' => $user->id,
            'title' => 'Stack Overflow',
            'url' => 'https://stackoverflow.com',
            'description' => 'Q&A platform for developers',
            'category_id' => $devCategory->id,
            'visits' => 28,
        ]);

        $bookmark4 = \App\Models\Bookmark::create([
            'user_id' => $user->id,
            'title' => 'MDN Web Docs',
            'url' => 'https://developer.mozilla.org',
            'description' => 'Resources for developers, by developers',
            'category_id' => $devCategory->id,
            'visits' => 12,
        ]);
        $bookmark4->tags()->attach([$jsTag->id, $tutorialTag->id]);

        // Create Jane Doe user
        $jane = User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        // Seed Nepali students with specialized content
        $this->call(NepaliStudentSeeder::class);

        // Add comprehensive educational bookmarks to all users
        $this->call(EducationalBookmarksSeeder::class);

        $this->command->info('Sample data created successfully!');
        $this->command->info('Nepali student users created with specialized bookmark collections!');
        $this->command->info('Educational bookmarks added to all users!');
    }
}
