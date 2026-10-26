<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnlockedChapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'chapter_id',
        'coins_spent',
        'unlocked_at',
    ];

    protected $casts = [
        'coins_spent' => 'integer',
        'unlocked_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }
}
