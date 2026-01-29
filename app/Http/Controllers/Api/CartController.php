<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartProduct;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    // GET /api/cart
    public function index(Request $request)
    {
        return response()->json($this->buildCart($request->user()->id));
    }

    // POST /api/cart/items  { game_id, quantity? }
    public function store(Request $request)
    {
        $data = $request->validate([
            'game_id' => ['required', 'integer', 'exists:games,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);

        $userId = $request->user()->id;
        $qtyToAdd = (int) ($data['quantity'] ?? 1);

        $game = Game::findOrFail($data['game_id']);

        DB::transaction(function () use ($userId, $game, $qtyToAdd) {
            $rows = CartProduct::query()
                ->where('user_id', $userId)
                ->where('game_id', $game->id)
                ->orderBy('id')
                ->get();

            if ($rows->isEmpty()) {
                CartProduct::create([
                    'user_id' => $userId,
                    'game_id' => $game->id,
                    'name' => $game->name,
                    'price' => $game->price,
                    'quantity' => $qtyToAdd,
                ]);
                return;
            }

            $primary = $rows->first();

            $extraQty = (int) $rows->skip(1)->sum('quantity');

            $primary->name = $game->name;
            $primary->price = $game->price;
            $primary->quantity = (int) $primary->quantity + $extraQty + $qtyToAdd;
            $primary->save();

            if ($rows->count() > 1) {
                CartProduct::query()
                    ->whereIn('id', $rows->skip(1)->pluck('id'))
                    ->delete();
            }
        });

        return response()->json($this->buildCart($userId), 201);
    }

    // PATCH /api/cart/items/{game}  { quantity }
    public function update(Request $request, Game $game)
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $userId = $request->user()->id;

        DB::transaction(function () use ($userId, $game, $data) {
            $rows = CartProduct::query()
                ->where('user_id', $userId)
                ->where('game_id', $game->id)
                ->orderBy('id')
                ->get();

            if ($rows->isEmpty()) {
                abort(404, 'Krepšelyje nerasta.');
            }

            $primary = $rows->first();
            $primary->name = $game->name;
            $primary->price = $game->price;
            $primary->quantity = (int) $data['quantity'];
            $primary->save();

            CartProduct::query()
                ->where('user_id', $userId)
                ->where('game_id', $game->id)
                ->where('id', '!=', $primary->id)
                ->delete();
        });

        return response()->json($this->buildCart($userId));
    }

    // DELETE /api/cart/items/{game}
    public function destroy(Request $request, Game $game)
    {
        $userId = $request->user()->id;

        $deleted = CartProduct::query()
            ->where('user_id', $userId)
            ->where('game_id', $game->id)
            ->delete();

        if ($deleted === 0) {
            return response()->json(['message' => 'Krepšelyje nerasta.'], 404);
        }

        return response()->noContent();
    }

    // DELETE /api/cart
    public function clear(Request $request)
    {
        CartProduct::query()
            ->where('user_id', $request->user()->id)
            ->delete();

        return response()->noContent();
    }

    private function buildCart(int $userId): array
    {
        $rows = CartProduct::query()
            ->with(['game:id,slug,image'])
            ->where('user_id', $userId)
            ->orderBy('id')
            ->get();

        $items = $rows->groupBy('game_id')->map(function ($group) {
            $first = $group->first();
            $qty = (int) $group->sum('quantity');
            if ($qty < 1) $qty = $group->count();

            $price = (float) $first->price;

            return [
                'game_id' => (int) $first->game_id,
                'name' => (string) $first->name,
                'price' => round($price, 2),
                'quantity' => $qty,
                'line_total' => round($price * $qty, 2),
                'game' => $first->game ? [
                    'slug' => $first->game->slug,
                    'image' => $first->game->image,
                ] : null,
            ];
        })->values();

        $total = round($items->sum('line_total'), 2);
        $count = (int) $items->sum('quantity');

        return [
            'items' => $items,
            'total' => $total,
            'count' => $count,
        ];
    }
}
