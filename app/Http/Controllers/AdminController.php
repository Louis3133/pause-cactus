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
        $posts = Post::pending()->with(['licence', 'user', 'favorites', 'categories'])->latest()->get();

        return Inertia::render('AdminPanel', [
            'posts' => $posts,
        ]);
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
