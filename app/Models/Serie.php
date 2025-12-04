<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Serie extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class)->orderBy('episode_number');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags() {
        return $this->belongsToMany(Tag::class);
    }

    public function categories() {
        return $this->belongsToMany(category::class);
    }
}
