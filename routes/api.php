<?php

use App\Http\Controllers\Api\BookmarkController as ApiBookmarkController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API routes with Sanctum authentication
Route::middleware('auth:sanctum')->group(function () {
    // Bookmarks API
    Route::apiResource('bookmarks', ApiBookmarkController::class);
    Route::post('/bookmarks/chrome-extension', [ApiBookmarkController::class, 'chromeExtension'])->name('api.bookmarks.chrome');

    // Quick bookmark save (for Chrome extension)
    Route::post('/quick-bookmark', [ApiBookmarkController::class, 'quickSave'])->name('api.quick-bookmark');
});
