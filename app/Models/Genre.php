<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Game;

class Genre extends Model
{
    protected $fillable = ['name'];

    public function games()
    {
        return $this->belongsToMany(\App\Models\Game::class, 'game_genre');
    }
}

