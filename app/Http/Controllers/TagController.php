<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTagRequest;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function store(CreateTagRequest $request)
    {
        $tag = Tag::create($request->validated());
        return response()->json($tag);
    }
}
