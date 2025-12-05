<?php
namespace App\Http\Controllers;
use App\Http\Requests\CreatePostRequest;
use App\Models\category;
use App\Models\Licence;
use App\Models\Post;
use App\Models\Serie;
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
            'series' => auth()->user()->series()->select('id', 'title')->get(),
        ]);
    }

    public function store(CreatePostRequest $request)
    {
        $post = new Post();
        $data = $this->extractData($post, $request);
        $post = auth()->user()->posts()->create($data);
        $post->tags()->sync($request->validated('tags'));
        $post->categories()->sync($request->validated('categories'));

        return redirect()->route('posts.submit')->with('success', 'Post created');
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
            'imageUrl' => $post->imageUrl(),
            'webtoonUrl' => $post->webtoon ? \Illuminate\Support\Facades\Storage::url($post->webtoon) : null,
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

        // --- GESTION IMAGE ---
        $image = $request->file('image'); // On récupère le fichier

        if($image){
            // Si un nouveau fichier est là, on supprime l'ancien et on upload le nouveau
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $data['image'] = $image->store('posts', 'public');
        } else {
            // IMPORTANT : Si pas de nouvelle image, on retire la clé du tableau
            // pour éviter d'écraser la valeur en BDD avec "null"
            unset($data['image']);
        }

        // --- GESTION WEBTOON ---
        $webtoon = $request->file('webtoon');

        if($webtoon){
            if ($post->webtoon) {
                Storage::disk('public')->delete($post->webtoon);
            }
            $data['webtoon'] = $webtoon->store('webtoons', 'public');
        } else {
            // Idem pour le webtoon, on retire la clé si pas de nouveau fichier
            unset($data['webtoon']);
        }

        return $data;
    }

    public function favorite(Post $post)
    {
        $user = auth()->user();

        $user->favoritePosts()->toggle($post);

        return $user;
    }


    public function submit()
    {
        return Inertia::render('Submision');
    }


    public function show(string $slug, string $id){
        $post = Post::with(['licence', 'user'])->findOrFail($id);

        if ($post->status !== 'published') {
            if (auth()->id() !== $post->user_id) {
                abort(404);
            }
        }

        if($post->slug !== $slug){
            return to_route('show', ['slug' => $post->slug, 'id' => $post->id]);
        }

        $postCategories = $post->categories()->pluck('id');

        $postsRelated = collect();

        if($postCategories->isNotEmpty()) {
        $postsRelated = Post::published()->with(['user', 'categories', 'favorites'])->whereHas('categories', function($query) use ($postCategories) {
            $query->whereIn('categories.id', $postCategories);
        })

            ->where('id', '!=', $post->id)
            ->limit(8)->get();
        }

        $postsRelated->each(function($relatedPost) {
            $relatedPost->image_url = $relatedPost->imageUrl();
            $relatedPost->is_favorited = auth()->check() && $relatedPost->favorites->contains(auth()->id());
        });


        return Inertia::render('ShowPost', [
            'post' => $post,
            'postsRelated' => $postsRelated,
            'canEdit' => auth()->check() && auth()->id() === $post->user_id,
            'hasWebtoon' => (bool) $post->webtoon,
            'isFavorite' => auth()->check() && $post->favorites->contains(auth()->id()),
            'editUrl' => route('posts.edit', ['post' => $post->id]),
            'webtoonUrl' => route('posts.webtoon', ['slug' => $post->slug, 'id' => $post->id]),
            'deleteUrl' => route('posts.destroy', ['post' => $post->id]),
            'imageUrl' => $post->imageUrl(),
        ]);
    }

    public function webtoon(string $slug, string $id) {
        $post = Post::with(['licence', 'user'])->findOrFail($id);

        if ($post->status !== 'published') {
            if (auth()->id() !== $post->user_id) {
                abort(404);
            }
        }

        if($post->slug !== $slug){
            return to_route('posts.webtoon', ['slug' => $post->slug, 'id' => $post->id]);
        }

        if (!$post->webtoon) {
            abort(404, 'Aucune planche disponible');
        }

        return Inertia::render('ShowWebtoon', [
            'post' => $post,
            'webtoonUrl' => $post->webtoonUrl(),
        ]);
    }













}
