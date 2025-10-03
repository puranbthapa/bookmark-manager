<?php

/**
 * ================================================================================
 * WEB ROUTES - APPLICATION ROUTING CONFIGURATION
 * ================================================================================
 *
 * 🏢 VENDOR: Eastlink Cloud Pvt. Ltd.
 * 👨‍💻 AUTHOR: Developer Team
 * 📅 CREATED: October 2025
 * 📧 CONTACT: puran@eastlink.net.np
 * 📞 PHONE: +977-01-4101181
 * 📱 DEVELOPER: +977-9801901140
 * 💼 BUSINESS: +977-9801901141
 * 🏢 ADDRESS: Tripureshwor, Kathmandu, Nepal
 *
 * 📋 DESCRIPTION:
 * Main web routing configuration for the bookmark management application.
 * Defines all public and authenticated routes with proper middleware.
 *
 * 🎯 ROUTE GROUPS:
 * - Public routes (welcome, login, register)
 * - Authenticated routes (dashboard, bookmarks)
 * - Admin routes (user management, permissions)
 * - API routes (bookmark operations)
 * - Resource routes (CRUD operations)
 *
 * 🔒 SECURITY:
 * - Authentication middleware
 * - Authorization checks
 * - CSRF protection
 * - Rate limiting
 *
 * ⚖️ LICENSE: Commercial Enterprise License
 * ================================================================================
 */

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/b/{shortCode}', [BookmarkController::class, 'public'])->name('bookmarks.public');

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Bookmarks
    Route::resource('bookmarks', BookmarkController::class);
    Route::post('/bookmarks/bulk', [BookmarkController::class, 'bulkAction'])->name('bookmarks.bulk');
    Route::patch('/bookmarks/{bookmark}/favorite', [BookmarkController::class, 'toggleFavorite'])->name('bookmarks.favorite');
    Route::get('/bookmarks/{bookmark}/visit', [BookmarkController::class, 'visit'])->name('bookmarks.visit');

    // Categories
    Route::resource('categories', CategoryController::class);

    // Tags
    Route::get('/tags', [TagController::class, 'index'])->name('tags.index');
    Route::get('/tags/{tag}', [TagController::class, 'show'])->name('tags.show');
    Route::post('/tags', [TagController::class, 'store'])->name('tags.store');
    Route::put('/tags/{tag}', [TagController::class, 'update'])->name('tags.update');
    Route::delete('/tags/{tag}', [TagController::class, 'destroy'])->name('tags.destroy');
    Route::get('/api/tags/autocomplete', [TagController::class, 'autocomplete'])->name('tags.autocomplete');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // User Management
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    Route::patch('/users/{user}/toggle-status', [App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Role Management
    Route::resource('roles', App\Http\Controllers\Admin\RoleController::class);

    // Permission Management
    Route::resource('permissions', App\Http\Controllers\Admin\PermissionController::class)->except(['show']);
});

require __DIR__.'/auth.php';
