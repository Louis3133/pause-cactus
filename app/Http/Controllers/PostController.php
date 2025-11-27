<?php
namespace App\Http\Controllers;
use App\Http\Requests\CreatePostRequest;
use App\Models\Post;
use Inertia\Inertia;

use Illuminate\Http\Request;

class PostController extends Controller
{
    public function create(){
        return Inertia::render('CreatePost');
    }

    public function store(CreatePostRequest $request){
        $post = Post::create($request->validated());
        return redirect()->route('welcome')->with('success', 'Post created');
    }

    public function edit(Post $post){
        return Inertia::render('EditPost', [
            'post' => $post
        ]);
    }

    public function show(string $slug, string $id){
        $post = Post::findOrFail($id);
        if($post->slug !== $slug){
            return to_route('show', ['slug' => $post->slug, 'id' => $post->id]);
        }
        return Inertia::render('ShowPost', [
            'post' => $post
        ]);
    }


    public function update(Post $post, CreatePostRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {

            $path = $request->file('image')->store('posts', 'public');

            $data['image'] = $path;
        }

        $post->update($data);

        return redirect()->route('welcome')->with('success', 'Post edited');
    }
}
