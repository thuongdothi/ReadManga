<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comic extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'alternative_names',
        'author',
        'artist',
        'status',
        'description',
        'cover_image',
        'views_count',
        'rating_score',
        'rating_count',
        'is_featured',
        'uploader_id',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'rating_score' => 'float',
        'views_count' => 'integer',
        'rating_count' => 'integer',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'comic_category');
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class)->orderBy('chapter_number', 'asc');
    }

    public function latestChapters()
    {
        return $this->hasMany(Chapter::class)->orderBy('chapter_number', 'desc');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
}
