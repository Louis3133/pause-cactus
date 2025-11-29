<?php
namespace App\Http\Controllers;
use App\Http\Requests\CreatePostRequest;
use App\Models\category;
use App\Models\Licence;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

use Illuminate\Http\Request;

class PostController extends Controller
{

    public function index() {
        $license = Licence::find(1);
        $posts = Post::find(10);
    }
    public function create(){
        return Inertia::render('CreatePost', [
            'licences' => Licence::select('id', 'name')->get(),
            'tags' => Tag::select('id', 'name')->get(),
            'categories' => category::select('id', 'name')->get(),
        ]);
    }

    public function store(CreatePostRequest $request)
    {
        $post = new Post();
        $data = $this->extractData($post, $request);
        $post = auth()->user()->posts()->create($data);
        $post->tags()->sync($request->validated('tags'));

        return redirect()->route('welcome')->with('success', 'Post created');
    }

    public function edit(Post $post){
        $post->load('licence');

        $post->licence_id = $post->licence?->id ?? null;

        if (auth()->user()->id != $post->user_id) {
            abort(403, 'Action non autorisée');
        }
        return Inertia::render('EditPost', [
            'post' => $post,
            'licences' => Licence::select('id', 'name')->get(),
            'tags' => Tag::select('id', 'name')->get(),
            'selectedTags' => $post->tags->pluck('id'),
            'categories' => category::select('id', 'name')->get(),
            'selectedCategories' => $post->categories->pluck('id'),
        ]);
    }


    public function show(string $slug, string $id){
        $post = Post::with(['licence', 'user'])->findOrFail($id);
        if($post->slug !== $slug){
            return to_route('show', ['slug' => $post->slug, 'id' => $post->id]);
        }
        return Inertia::render('ShowPost', [
            'post' => $post,
            'canEdit' => auth()->check() && auth()->id() === $post->user_id,
            'isFavorite' => auth()->check() && $post->favorites->contains(auth()->id()),
            'editUrl' => route('posts.edit', ['post' => $post->id]),
            'deleteUrl' => route('posts.destroy', ['post' => $post->id]),
        ]);
    }


    public function update(Post $post, CreatePostRequest $request)
    {
        if (auth()->user()->id != $post->user_id) {
            abort(403, 'Action non autorisée');
        }
        $post->tags()->sync($request->validated('tags'));
        $post->categories()->sync($request->validated('categories'));
        $post->update($this->extractData($post, $request));
        return redirect()->route('welcome')->with('success', 'Post edited');
    }

    public function destroy(Post $post)
    {
        abort_if(auth()->id() !== $post->user_id, 403);

        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();
        return redirect()->route('welcome')->with('success', 'Post supprimé');
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

    public function favorite(Post $post)
    {
        $user = auth()->user();

        $user->favoritePosts()->toggle($post);

        return $user;
    }

}
