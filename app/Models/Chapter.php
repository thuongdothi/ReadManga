<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'comic_id',
        'chapter_number',
        'title',
        'slug',
        'price_coins',
        'is_vip',
        'views_count',
        'published_at',
    ];

    protected $casts = [
        'chapter_number' => 'float',
        'price_coins' => 'integer',
        'is_vip' => 'boolean',
        'views_count' => 'integer',
        'published_at' => 'datetime',
    ];

    public function comic()
    {
        return $this->belongsTo(Comic::class);
    }

    public function images()
    {
        return $this->hasMany(ChapterImage::class)->orderBy('page_number', 'asc');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function unlockedUsers()
    {
        return $this->belongsToMany(User::class, 'unlocked_chapters');
    }

    // Previous and Next chapter helper
    public function getPreviousChapter()
    {
        return Chapter::where('comic_id', $this->comic_id)
            ->where('chapter_number', '<', $this->chapter_number)
            ->orderBy('chapter_number', 'desc')
            ->first();
    }

    public function getNextChapter()
    {
        return Chapter::where('comic_id', $this->comic_id)
            ->where('chapter_number', '>', $this->chapter_number)
            ->orderBy('chapter_number', 'asc')
            ->first();
    }
}
