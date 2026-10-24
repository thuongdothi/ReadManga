<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoinTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount_vnd',
        'coins',
        'payment_method',
        'transaction_code',
        'status',
    ];

    protected $casts = [
        'amount_vnd' => 'integer',
        'coins' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
