<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function show(string $slug, string $id) {
        $user = User::findOrFail($id);

        $expectedSlug = \Str::slug($user->name);

        if($slug !== $expectedSlug){
            return to_route('author.show', ['slug' => $expectedSlug, 'id' => $user->id]);
        }

        $posts = Post::where('user_id', $user->id)->with(['favorites', 'categories', 'licence', 'user'])->get();

        $posts->each(function($post) {
            $post->image_url = $post->imageUrl();
            $post->is_favorited = auth()->check() && $post->favorites->contains(auth()->id());
        });

        return Inertia::render('ShowUser', [
            'user' => $user,
            'posts' => $posts,
            'canEdit' => auth()->check() && auth()->id() === $user->id,
        ]);
    }
}

