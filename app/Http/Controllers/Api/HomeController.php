<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GameController extends Controller
{
    /**
     * GET /api/games
     * Query:
     *  - q=... (paieška pagal pavadinimą)
     *  - platform=PC (filtras pagal platformos pavadinimą)
     *  - genre=Action (filtras pagal žanro pavadinimą)
     *  - sort=newest|oldest|name|price_asc|price_desc
     *  - per_page=1..100
     */
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $platform = trim((string) $request->query('platform', ''));
        $genre = trim((string) $request->query('genre', ''));
        $sort = (string) $request->query('sort', 'newest');

        $perPage = (int) $request->query('per_page', 20);
        $perPage = max(1, min(100, $perPage));

        $query = Game::query()->with(['platforms', 'genres']);

        if ($q !== '') {
            $query->where('name', 'like', '%' . $q . '%');
        }

        if ($platform !== '') {
            $query->whereHas('platforms', function ($q) use ($platform) {
                $q->where('name', $platform);
            });
        }

        if ($genre !== '') {
            $query->whereHas('genres', function ($q) use ($genre) {
                $q->where('name', $genre);
            });
        }

        $this->applySort($query, $sort);

        $games = $query->paginate($perPage);

        // JSON (su pagination meta)
        return response()->json($games);
    }

    /**
     * GET /api/games/{game:slug}
     */
    public function show(Game $game)
    {
        $game->load(['platforms', 'genres']);
        return response()->json($game);
    }

    /**
     * POST /api/games
     * Body (JSON):
     *  - name (required)
     *  - price (optional)
     *  - description (optional)
     *  - image (optional)
     *  - platform_ids (optional array<int>)
     *  - genre_ids (optional array<int>)
     */
    public function store(Request $request)
    {
        // Jei turi Policy, įjunk:
        // $this->authorize('create', Game::class);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:2048'],

            'platform_ids' => ['nullable', 'array'],
            'platform_ids.*' => ['integer', 'exists:platforms,id'],

            'genre_ids' => ['nullable', 'array'],
            'genre_ids.*' => ['integer', 'exists:genres,id'],
        ]);

        $game = new Game();
        $game->name = $data['name'];
        $game->slug = $this->uniqueSlug($data['name']);
        $game->price = $data['price'] ?? 60.00;
        $game->description = $data['description'] ?? null;
        $game->image = $data['image'] ?? null;
        $game->save();

        if (!empty($data['platform_ids'])) {
            $game->platforms()->sync($data['platform_ids']);
        }

        if (!empty($data['genre_ids'])) {
            $game->genres()->sync($data['genre_ids']);
        }

        $game->load(['platforms', 'genres']);

        return response()->json($game, 201);
    }

    /**
     * PUT/PATCH /api/games/{game}
     */
    public function update(Request $request, Game $game)
    {
        // Jei turi Policy, įjunk:
        // $this->authorize('update', $game);

        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'price' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'description' => ['sometimes', 'nullable', 'string'],
            'image' => ['sometimes', 'nullable', 'string', 'max:2048'],

            'platform_ids' => ['sometimes', 'nullable', 'array'],
            'platform_ids.*' => ['integer', 'exists:platforms,id'],

            'genre_ids' => ['sometimes', 'nullable', 'array'],
            'genre_ids.*' => ['integer', 'exists:genres,id'],
        ]);

        if (array_key_exists('name', $data)) {
            $game->name = $data['name'];
            $game->slug = $this->uniqueSlug($data['name'], $game->id);
        }

        if (array_key_exists('price', $data)) {
            $game->price = $data['price'];
        }

        if (array_key_exists('description', $data)) {
            $game->description = $data['description'];
        }

        if (array_key_exists('image', $data)) {
            $game->image = $data['image'];
        }

        $game->save();

        if (array_key_exists('platform_ids', $data)) {
            $game->platforms()->sync($data['platform_ids'] ?? []);
        }

        if (array_key_exists('genre_ids', $data)) {
            $game->genres()->sync($data['genre_ids'] ?? []);
        }

        $game->load(['platforms', 'genres']);

        return response()->json($game);
    }

    /**
     * DELETE /api/games/{game}
     */
    public function destroy(Game $game)
    {
        // Jei turi Policy, įjunk:
        // $this->authorize('delete', $game);

        $game->platforms()->detach();
        $game->genres()->detach();
        $game->delete();

        return response()->json(['deleted' => true]);
    }

    private function applySort($query, string $sort): void
    {
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 2;

        while (
            Game::query()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $i;
            $i++;
        }

        return $slug;
    }
}
