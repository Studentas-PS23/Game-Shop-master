<?php
// filepath: app/Http/Controllers/HomeController.php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        $newGames = [
            [
                'name' => 'Split Fiction',
                'slug' => 'split-fiction',
                'image' => 'images/games/split-fiction.png',
                'platform' => 'PS5',
                'price' => '47.00',
            ],
            [
                'name' => 'Citizen Sleeper 2: Starward Vector',
                'slug' => 'citizen-sleeper-2-starward-vector',
                'image' => 'images/games/citizen-sleeper-2.png',
                'platform' => 'PC',
                'price' => '19.00',
            ],
            [
                'name' => 'South of Midnight',
                'slug' => 'south-of-midnight',
                'image' => 'images/games/south-of-midnight.jpg',
                'platform' => 'Xbox',
                'price' => '50.00',
            ],
            [
                'name' => 'Clair Obscur: Expedition 33',
                'slug' => 'clair-obscur-expedition-33',
                'image' => 'images/games/expedition-33.png',
                'platform' => 'PS5',
                'price' => '55.00',
            ],
            [
                'name' => 'Doom: The Dark Ages',
                'slug' => 'doom-the-dark-ages',
                'image' => 'images/games/Doom-The-Dark-Ages.jpg',
                'platform' => 'PS5',
                'price' => '69.99',
            ],
            [
                'name' => 'Blue Prince',
                'slug' => 'the-blue-prince',
                'image' => 'images/games/Blue-Prince.jpg',
                'platform' => 'PC',
                'price' => 'TBA',
            ],
            [
                'name' => 'Kingdom Come: Deliverance II',
                'slug' => 'kingdom-come-deliverance-ii',
                'image' => 'images/games/Kingdom-Come-Deliverance-II.png',
                'platform' => 'PS5',
                'price' => '59.99',
            ],
            [
                'name' => 'REMATCH',
                'slug' => 'rematch',
                'image' => 'images/games/REMATCH.png',
                'platform' => 'PC',
                'price' => '25.00',
            ],
        ];

        $popularGames = [
            [
                'name' => 'Monster Hunter: Wilds',
                'slug' => 'monster-hunter-wilds',
                'image' => 'images/games/Monster-Hunter-Wilds.png',
                'platform' => 'PS5',
                'price' => '69.99',
            ],
            [
                'name' => 'Assassin’s Creed Shadows',
                'slug' => 'assassins-creed-shadows',
                'image' => 'images/games/Assassin’s-Creed-Shadows.png',
                'platform' => 'PC',
                'price' => '59.99',
            ],
            [
                'name' => 'Oblivion Remastered',
                'slug' => 'the-elder-scrolls-iv-oblivion',
                'image' => 'images/games/Oblivion-Remastered.png',
                'platform' => 'PC',
                'price' => '59.99',
            ],
            [
                'name' => 'MLB The Show 25',
                'slug' => 'mlb-the-show-25',
                'image' => 'images/games/MLB-The-Show-25.png',
                'platform' => 'PS5',
                'price' => '69.99',
            ],
            [
                'name' => 'Call of Duty: Black Ops 6',
                'slug' => 'call-of-duty-black-ops-6',
                'image' => 'images/games/bo6.png',
                'platform' => 'PC',
                'price' => '69.99',
            ],
            [
                'name' => 'Dune awakening',
                'slug' => 'dune-awakening',
                'image' => 'images/games/dune-awakening.png',
                'platform' => 'PC',
                'price' => '19.99',
            ],
            [
                'name' => 'Helldivers 2',
                'slug' => 'helldivers-2',
                'image' => 'images/games/Helldivers-2.png',
                'platform' => 'PC',
                'price' => '39.99',
            ],
            [
                'name' => 'GTA V',
                'slug' => 'grand-theft-auto-v',
                'image' => 'images/games/GTA-V.png',
                'platform' => 'PC',
                'price' => '29.99',
            ],
        ];

        return view('home', compact('newGames', 'popularGames'));
    }
}
