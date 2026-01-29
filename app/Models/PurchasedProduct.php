<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchasedProduct extends Model
{
    protected $fillable = ['user_id', 'game_id', 'name', 'price', 'quantity', 'purchased_at'];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
        'purchased_at' => 'datetime',
    ];
}
