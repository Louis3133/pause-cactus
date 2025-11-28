<?php
namespace App\Http\Controllers;
use App\Http\Requests\CreatePostRequest;
use App\Models\Licence;
use App\Models\Post;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

use Illuminate\Http\Request;

class PostController extends Controller
{

    public function index() {
        $license = Licence::find(1);
        $posts = Post::find(10);
        dd($license->posts()->where('id' , '>', '10')->get());
    }
    public function create(){
        return Inertia::render('CreatePost', [
            'licences' => Licence::select('id', 'name')->get(),
        ]);
    }

    public function store(CreatePostRequest $request)
    {
        $post = new Post();
        $post = Post::create($this->extractData($post, $request));
        return redirect()->route('welcome')->with('success', 'Post created');
    }

    public function edit(Post $post){
        $post->load('licence');

        $post->licence_id = $post->licence?->id ?? null;

        return Inertia::render('EditPost', [
            'post' => $post,
            'licences' => Licence::select('id', 'name')->get(),
        ]);
    }

    public function show(string $slug, string $id){
        $post = Post::with('licence')->findOrFail($id);
        if($post->slug !== $slug){
            return to_route('show', ['slug' => $post->slug, 'id' => $post->id]);
        }
        return Inertia::render('ShowPost', [
            'post' => $post,
        ]);
    }


    public function update(Post $post, CreatePostRequest $request)
    {
        $post->update($this->extractData($post, $request));
        return redirect()->route('welcome')->with('success', 'Post edited');
    }

    private function extractData(Post $post, CreatePostRequest $request){
        $data = $request->validated();

        /** @var UploadedFile|null $image */
        $image = $request->validated('image');
        if($image == null || $image->getError()){
            return $data;
        }
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }
        $data['image'] = $image->store('posts', 'public');
        return $data;
    }

}
