<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

// Redirect home to posts
Route::get('/', function () {
    return redirect()->route('posts.index');
});

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Protected post routes (must be logged in) - THESE MUST COME FIRST!
Route::middleware('auth')->group(function () {
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::get('/posts/{post}', [PostController::class, 'show']);
    // Category and Tag management
    Route::resource('categories', CategoryController::class);
    Route::resource('tags', TagController::class);
});

// Public post routes (anyone can view) - THESE COME LAST!
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

// Setup route (can remove after first use)
Route::get('/setup-blog', function () {
    // Create categories
    $categories = [
        ['name' => 'Technology', 'slug' => 'technology', 'description' => 'Tech news and tutorials'],
        ['name' => 'Lifestyle', 'slug' => 'lifestyle', 'description' => 'Life and culture'],
        ['name' => 'Travel', 'slug' => 'travel', 'description' => 'Travel guides and tips'],
        ['name' => 'Food', 'slug' => 'food', 'description' => 'Recipes and food reviews'],
    ];

    foreach ($categories as $category) {
        \App\Models\Category::firstOrCreate(['slug' => $category['slug']], $category);
    }

    // Create tags
    $tags = [
        ['name' => 'Laravel', 'slug' => 'laravel'],
        ['name' => 'PHP', 'slug' => 'php'],
        ['name' => 'Tutorial', 'slug' => 'tutorial'],
        ['name' => 'Tips', 'slug' => 'tips'],
        ['name' => 'Guide', 'slug' => 'guide'],
    ];

    foreach ($tags as $tag) {
        \App\Models\Tag::firstOrCreate(['slug' => $tag['slug']], $tag);
    }

    return 'Blog setup complete! Categories and tags created. <a href="/posts">View Posts</a>';
});

require __DIR__.'/auth.php';