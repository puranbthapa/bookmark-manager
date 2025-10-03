<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Bookmark;
use App\Models\Category;
use App\Models\Tag;
use App\Policies\BookmarkPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\TagPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Bookmark::class => BookmarkPolicy::class,
        Category::class => CategoryPolicy::class,
        Tag::class => TagPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register policies
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }
}
