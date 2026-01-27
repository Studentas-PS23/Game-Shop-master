<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Game;

class ImportGamesFromApi extends Command
{
    protected $signature = 'games:import-api';
    protected $description = 'Importuoja žaidimus iš RAWG API į SQL duomenų bazę';

    public function handle()
    {
        $apiKey = config('services.rawg.key');
        $mustHaveSlugs = [
            'split-fiction',
            'citizen-sleeper-2-starward-vector',
            'south-of-midnight',
            'clair-obscur-expedition-33',
            'doom-the-dark-ages',
            'the-blue-prince',
            'kingdom-come-deliverance-ii',
            'rematch',
            'monster-hunter-wilds',
            'assassins-creed-shadows',
            'the-elder-scrolls-iv-oblivion',
            'mlb-the-show-25',
            'call-of-duty-black-ops-6',
            'dune-awakening',
            'helldivers-2',
            'grand-theft-auto-v',
        ];

        $imported = 0;
        $limit = 200;

        // 1. Importuok būtinus žaidimus pagal slug
        foreach ($mustHaveSlugs as $slug) {
            $gameDetails = Http::get("https://api.rawg.io/api/games/{$slug}", [
                'key' => $apiKey,
            ])->json();

            if (!empty($gameDetails['slug'])) {
                // 1. Sukurk arba atnaujink žaidimą
                $game = Game::updateOrCreate(
                    ['slug' => $gameDetails['slug']],
                    [
                        'name' => $gameDetails['name'],
                        'slug' => $gameDetails['slug'],
                        'description' => $gameDetails['description'] ?? null,
                        'image' => $gameDetails['background_image'] ?? null,
                        'price' => 60.00,
                    ]
                );

                // 2. Platformos
                if (!empty($gameDetails['platforms'])) {
                    $platformIds = [];
                    foreach ($gameDetails['platforms'] as $platformData) {
                        $platformName = $platformData['platform']['name'];
                        $platform = \App\Models\Platform::firstOrCreate(['name' => $platformName]);
                        $platformIds[] = $platform->id;
                    }
                    $game->platforms()->sync($platformIds);
                }

                // 3. Žanrai
                if (!empty($gameDetails['genres'])) {
                    $genreIds = [];
                    foreach ($gameDetails['genres'] as $genreData) {
                        $genreName = $genreData['name'];
                        $genre = \App\Models\Genre::firstOrCreate(['name' => $genreName]);
                        $genreIds[] = $genre->id;
                    }
                    $game->genres()->sync($genreIds);
                }

                $imported++;
                $this->info("Įtrauktas: {$gameDetails['name']}");
            } else {
                $this->warn("Nerastas žaidimas pagal slug: $slug");
            }
        }

        // 2. Importuok papildomus žaidimus iš RAWG API
        $page = 1;
        while ($imported < $limit) {
            $response = Http::get('https://api.rawg.io/api/games', [
                'key' => $apiKey,
                'page_size' => 40,
                'page' => $page,
            ]);
            $data = $response->json();

            if (!isset($data['results'])) {
                $this->error('API klaida:');
                dd($data);
                break;
            }

            foreach ($data['results'] as $apiGame) {
                // Praleisk, jei jau importuotas (pagal slug)
                if (Game::where('slug', $apiGame['slug'])->exists()) {
                    continue;
                }

                // Gauk pilną žaidimo informaciją pagal slug
                $gameDetails = Http::get("https://api.rawg.io/api/games/{$apiGame['slug']}", [
                    'key' => $apiKey,
                ])->json();

                // Sukurk arba atnaujink žaidimą
                $game = Game::updateOrCreate(
                    ['slug' => $apiGame['slug']],
                    [
                        'name' => $apiGame['name'],
                        'slug' => $apiGame['slug'],
                        'description' => $gameDetails['description'] ?? null,
                        'image' => $apiGame['background_image'] ?? null,
                        'price' => 60.00,
                    ]
                );

                // Platformos
                if (!empty($gameDetails['platforms'])) {
                    $platformIds = [];
                    foreach ($gameDetails['platforms'] as $platformData) {
                        $platformName = $platformData['platform']['name'];
                        $platform = \App\Models\Platform::firstOrCreate(['name' => $platformName]);
                        $platformIds[] = $platform->id;
                    }
                    $game->platforms()->sync($platformIds);
                }

                // Žanrai
                if (!empty($gameDetails['genres'])) {
                    $genreIds = [];
                    foreach ($gameDetails['genres'] as $genreData) {
                        $genreName = $genreData['name'];
                        $genre = \App\Models\Genre::firstOrCreate(['name' => $genreName]);
                        $genreIds[] = $genre->id;
                    }
                    $game->genres()->sync($genreIds);
                }

                $imported++;
                $this->info("Įtrauktas: {$apiGame['name']}");
                if ($imported >= $limit) break 2;
            }
            $page++;
        }

        $this->info("Importuota $imported žaidimų!");
    }
}