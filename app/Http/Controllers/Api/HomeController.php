<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * GET /api
     * (arba GET /api/home – kaip susidėsi routes)
     *
     * Query:
     *  - new_limit=1..50 (default 8)
     *  - popular_limit=1..50 (default 8)
     */
    public function index(Request $request)
    {
        $newLimit = (int) $request->query('new_limit', 8);
        $popularLimit = (int) $request->query('popular_limit', 8);

        $newLimit = max(1, min(50, $newLimit));
        $popularLimit = max(1, min(50, $popularLimit));

        // "Naujausi" – pagal sukūrimo datą (importo metu created_at užsipildo automatiškai)
        $newGames = Game::query()
            ->with(['platforms', 'genres'])
            ->latest('created_at')
            ->limit($newLimit)
            ->get();

        // "Populiarūs" – jei neturi rating/views, kol kas imk random (vėliau pakeisi į views/rating)
        $popularGames = Game::query()
            ->with(['platforms', 'genres'])
            ->inRandomOrder()
            ->limit($popularLimit)
            ->get();

        return response()->json([
            'status' => 'ok',
            'counts' => [
                'games_total' => Game::count(),
                'new_returned' => $newGames->count(),
                'popular_returned' => $popularGames->count(),
            ],
            'new_games' => $newGames,
            'popular_games' => $popularGames,
        ]);
    }
}
