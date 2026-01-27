<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchasedProduct extends Model
{
    protected $fillable = ['user_id', 'game_id', 'pdf_path', 'name', 'price'];

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }
}
