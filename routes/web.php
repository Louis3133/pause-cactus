<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\WorkOS\Http\Middleware\ValidateSessionWithWorkOS;

Route::get('/', function () {
    $posts = \App\Models\Post::with('licence')->get();
    $posts->each(function($post) {
        $post->image_url = $post->imageUrl();
    });

    return Inertia::render('Welcome', [
        'posts' => $posts
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

Route::prefix('/posts')->name('posts.')->middleware(['auth', ValidateSessionWithWorkOS::class,])->controller(\App\Http\Controllers\PostController::class)->group(function () {
    route::get('/', 'index')->name('index');
    Route::get('/new', 'create')->name('create');
    Route::post('/store', 'store')->name('store');
    Route::get('/{post}/edit', 'edit')->name('edit');
    Route::put('/{post}', 'update')->name('update');
    Route::delete('/{post}', 'destroy')->name('destroy');
    Route::put('/{post}/favorite', 'favorite')->name('favorite');
    Route::get('/{slug}-{id}', 'show')->where([
        'id' => '[0-9]+',
        'slug' => '[a-z0-9\-]+'
        ])->name('show');
});

Route::prefix('/tags')->name('tags.')->middleware(['auth', ValidateSessionWithWorkOS::class,])->controller(\App\Http\Controllers\TagController::class)->group(function () {
    Route::post('/store', 'store')->name('store');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
