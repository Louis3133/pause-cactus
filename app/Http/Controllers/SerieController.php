<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSerieRequest;
use Illuminate\Http\Request;
use App\Models\Serie;
use Inertia\Inertia;

class SerieController extends Controller
{
    public function store(CreateSerieRequest $request)
    {

        $data = $request->validated();
        $serie = auth()->user()->series()->create($data);

        $serie->tags()->sync($request->validated('tags'));
        $serie->categories()->sync($request->validated('categories'));

        return response()->json($serie);
    }

    public function show(string $slug, string $id)
    {
        $serie = Serie::findOrFail($id);

        if($serie->slug !== $slug){
            return to_route('series.show', ['slug' => $serie->slug, 'id' => $serie->id]);
        }

        return Inertia::render('ShowSerie', [
            'serie' => $serie
        ]);
    }
}
