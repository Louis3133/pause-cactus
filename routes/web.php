<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\WorkOS\Http\Middleware\ValidateSessionWithWorkOS;

Route::get('/', function (Request $request) {

    $categoryId = $request->get('category');

    $posts = \App\Models\Post::published()->with(['licence', 'user', 'favorites', 'categories']);

    if ($categoryId) {
        $posts = $posts->whereHas('categories', function($query) use ($categoryId) {
            $query->where('categories.id', $categoryId);
        });
    }

    $posts = $posts->get();

    $posts->each(function($post) {
        $post->image_url = $post->imageUrl();
        $post->is_favorited = auth()->check() && $post->favorites->contains(auth()->id());
    });

    $series = \App\Models\Serie::with('user')->get();

    return Inertia::render('Welcome', [
        'posts' => $posts,
        'series' => $series,
        'categories' => \App\Models\category::select('id', 'name')->get(),
        'selectedCategory' => $categoryId,
    ]);
})->name('welcome');

Route::middleware([
    'auth',
    ValidateSessionWithWorkOS::class,
])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

Route::get('/posts/{slug}-{id}', [\App\Http\Controllers\PostController::class, 'show'])
    ->where(['id' => '[0-9]+', 'slug' => '[a-z0-9\-]+'])
    ->name('posts.show');

Route::prefix('/posts')->name('posts.')->middleware(['auth', ValidateSessionWithWorkOS::class])->controller(\App\Http\Controllers\PostController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/new', 'create')->name('create');
    Route::get('/submit', 'submit')->name('submit');
    Route::post('/store', 'store')->name('store');
    Route::get('/{post}/edit', 'edit')->name('edit');
    Route::put('/{post}', 'update')->name('update');
    Route::delete('/{post}', 'destroy')->name('destroy');
    Route::put('/{post}/favorite', 'favorite')->name('favorite');
    Route::get('/{slug}-{id}/webtoon', 'webtoon')->where(['id' => '[0-9]+', 'slug' => '[a-z0-9\-]+'])->name('webtoon');
});

Route::prefix('/author')->name('author.')->controller(\App\Http\Controllers\UserController::class)->group(function () {
    Route::get('/{slug}-{id}', 'show')->where([
        'id' => '[0-9]+',
        'slug' => '[a-z0-9\-]+'
    ])->name('show');
});

Route::prefix('/tags')->name('tags.')->middleware(['auth', ValidateSessionWithWorkOS::class,])->controller(\App\Http\Controllers\TagController::class)->group(function () {
    Route::post('/store', 'store')->name('store');
});

Route::prefix('/series')->name('series.')->middleware(['auth', ValidateSessionWithWorkOS::class,])->controller(\App\Http\Controllers\SerieController::class)->group(function () {
    Route::post('/store', 'store')->name('store');
    Route::get('/{slug}-{id}', 'show')->where([
        'id' => '[0-9]+',
        'slug' => '[a-z0-9\-]+'
    ])->name('show');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\AdminController::class, 'index'])->name('index');
    Route::put('/posts/{post}/approve', [\App\Http\Controllers\AdminController::class, 'approve'])->name('approve');
    Route::put('/posts/{post}/reject', [\App\Http\Controllers\AdminController::class, 'reject'])->name('reject');
    Route::delete('/admin/post/{post}', [\App\Http\Controllers\AdminController::class, 'destroyPost'])->name('deletePost');
    Route::delete('/admin/user/{user}', [\App\Http\Controllers\AdminController::class, 'destroyUser'])->name('deleteUser');
});

Route::get('/concept', function (Request $request) { return Inertia::render('Concept');})->name('concept');

Route::get('/about', function (Request $request) { return Inertia::render('AboutUs');})->name('about');

Route::get('/mentions-legales', function (Request $request) { return Inertia::render('MentionsLegales');})->name('legal');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
