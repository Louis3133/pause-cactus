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
        'image',
        'user_id',
        'serie_id',
        'episode_number',
        'status',
        'webtoon',
    ];

    public function licence () {
        return $this->belongsTo(Licence::class);
    }

    public function imageUrl () {
        return Storage::url($this->image);
    }

    public function webtoonUrl() {
        return Storage::url($this->webtoon);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags() {
        return $this->belongsToMany(Tag::class);
    }

    public function favorites() {
        return $this->belongsToMany(User::class);
    }

    public function categories() {
        return $this->belongsToMany(category::class);
    }

    public function serie() {
        return $this->belongsTo(Serie::class);
    }

    public function scopePublished($query) {
        return $query->where('status', 'published');
    }

    public function scopePending($query) {
        return $query->where('status', 'pending');
    }
}
