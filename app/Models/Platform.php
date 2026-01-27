<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Game;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Platform extends Model
{
    protected $fillable = ['name'];

    public function games()
    {
        return $this->belongsToMany(\App\Models\Game::class, 'game_platform');
    }
}

