<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'role',
        'coin_balance',
        'vip_expires_at',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'vip_expires_at' => 'datetime',
        'password' => 'hashed',
        'coin_balance' => 'integer',
    ];

    // Role checks
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isModerator(): bool
    {
        return in_array($this->role, ['admin', 'moderator']);
    }

    public function isUploader(): bool
    {
        return in_array($this->role, ['admin', 'moderator', 'uploader']);
    }

    public function isVip(): bool
    {
        return $this->vip_expires_at && $this->vip_expires_at->isFuture();
    }

    public function hasUnlocked(int $chapterId): bool
    {
        if ($this->isAdmin() || $this->isVip()) {
            return true;
        }
        return $this->unlockedChapters()->where('chapter_id', $chapterId)->exists();
    }

    // Relationships
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoriteComics()
    {
        return $this->belongsToMany(Comic::class, 'favorites');
    }

    public function readingHistories()
    {
        return $this->hasMany(ReadingHistory::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function coinTransactions()
    {
        return $this->hasMany(CoinTransaction::class);
    }

    public function unlockedChapters()
    {
        return $this->hasMany(UnlockedChapter::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
