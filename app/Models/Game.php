<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
        'name','slug','image','price','description'
    ];

    public function platforms()
    {
        return $this->belongsToMany(\App\Models\Platform::class, 'game_platform');
    }

    public function genres()
    {
        return $this->belongsToMany(\App\Models\Genre::class, 'game_genre');
    }
}