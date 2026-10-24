<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoinPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price_vnd',
        'coins_awarded',
        'bonus_coins',
        'is_active',
    ];

    protected $casts = [
        'price_vnd' => 'integer',
        'coins_awarded' => 'integer',
        'bonus_coins' => 'integer',
        'is_active' => 'boolean',
    ];

    public function getTotalCoinsAttribute()
    {
        return $this->coins_awarded + $this->bonus_coins;
    }
}
