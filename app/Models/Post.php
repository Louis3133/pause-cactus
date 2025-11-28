<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'licence_id',
        'image'
    ];

    public function licence (){
        return $this->belongsTo(Licence::class);
    }

    public function imageUrl (){
        return Storage::url($this->image);
    }
}
