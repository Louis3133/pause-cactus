<?php

namespace App\Http\Controllers;

use App\Models\category;
use App\Models\Licence;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{

    public function index() {

        $pendingPosts = Post::pending()->with(['user', 'categories'])->latest()->get();
        $pendingPosts->transform(fn($p) => $p->setAttribute('image_url', $p->imageUrl()));

        $publishedPosts = Post::where('status', 'published')
            ->with(['user'])
            ->latest()
            ->get();

        $publishedPosts->transform(fn($p) => $p->setAttribute('image_url', $p->imageUrl()));

        $users = \App\Models\User::latest()->get();

        return Inertia::render('AdminPanel', [
            'pendingPosts' => $pendingPosts,
            'publishedPosts' => $publishedPosts,
            'users' => $users,
        ]);
    }

    public function destroyPost(Post $post)
    {
        $post->delete();
        return redirect()->back()->with('success', 'Article supprimé.');
    }

    public function destroyUser(\App\Models\User $user)
    {
        if (auth()->id() === $user->id) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();
        return redirect()->back()->with('success', 'Utilisateur banni/supprimé.');
    }

    public function approve(Post $post)
    {
        $post->update([
            'status' => 'published'
        ]);

        return redirect()->route('admin.index')->with('success', 'Article validé et publié !');
    }

    public function reject(Post $post)
    {
        $post->update([
            'status' => 'rejected'
        ]);

        return redirect()->route('admin.index')->with('success', 'Article refusé.');
    }
}
