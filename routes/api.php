<?php

use App\Http\Controllers\Api\V1\ArticleController;
use App\Http\Controllers\Api\V1\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — VNKR v1
|--------------------------------------------------------------------------
| Base URL: https://vnkr.vn/api/v1/
| Auth: Sanctum token (optional — public endpoints không cần token)
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ===== PUBLIC API v1 =====
Route::prefix('v1')->name('api.v1.')->group(function () {

    // Articles
    Route::get('/articles',        [ArticleController::class, 'index'])->name('articles.index');
    Route::get('/articles/{slug}', [ArticleController::class, 'show'])->name('articles.show');

    // Search
    Route::get('/search',          [ArticleController::class, 'search'])->name('search');

    // Categories
    Route::get('/categories',                    [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/{id}/articles',      [CategoryController::class, 'articles'])->name('categories.articles');

    // Tags
    Route::get('/tags',                          [\App\Http\Controllers\Api\V1\TagController::class, 'index'])->name('tags.index');
    Route::get('/tags/{slug}/articles',          [\App\Http\Controllers\Api\V1\TagController::class, 'articles'])->name('tags.articles');
});
