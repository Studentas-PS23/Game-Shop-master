<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    // Visi žaidimai (sąrašas)
    public function index()
    {
        $games = Game::with(['platforms', 'genres'])->get();
        return view('game.index', compact('games'));
    }

    // Vieno žaidimo rodymas
    public function show(string $slug)
    {
        $game = Game::with(['platforms', 'genres'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Platformos ir žanrai iš ryšių
        $platforms = $game->platforms->pluck('name')->all();
        $genres = $game->genres->pluck('name')->all();

        // Aprašymas iš DB (stulpelis games.description)
        $description = $game->description; // gali būti null

        return view('game.show', compact('game', 'platforms', 'genres', 'description'));
    }

    // Paieška pagal žaidimo pavadinimą
    public function search(Request $request)
    {
        $query = (string) $request->input('q', '');

        $games = Game::query()
            ->with(['platforms', 'genres'])
            ->where('name', 'like', '%' . $query . '%')
            ->get();

        return view('game.search', compact('games', 'query'));
    }
}
