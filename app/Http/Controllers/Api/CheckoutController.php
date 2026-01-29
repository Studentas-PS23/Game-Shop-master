<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartProduct;
use App\Models\PurchasedProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    // POST /api/checkout
    public function store(Request $request)
    {
        $userId = $request->user()->id;

        $result = DB::transaction(function () use ($userId) {
            $cartRows = CartProduct::query()
                ->where('user_id', $userId)
                ->get();

            if ($cartRows->isEmpty()) {
                return ['status' => 422, 'payload' => ['message' => 'Krepšelis tuščias.']];
            }

            // sugrupuojam pagal game_id (jei buvo dublikatų)
            $items = $cartRows->groupBy('game_id')->map(function ($group) {
                $first = $group->first();
                $qty = (int) $group->sum('quantity');
                if ($qty < 1) $qty = $group->count();

                return [
                    'game_id' => (int) $first->game_id,
                    'name' => (string) $first->name,
                    'price' => (float) $first->price,
                    'quantity' => $qty,
                ];
            })->values();

            foreach ($items as $it) {
                PurchasedProduct::create([
                    'user_id' => $userId,
                    'game_id' => $it['game_id'],
                    'name' => $it['name'],
                    'price' => $it['price'],
                    'quantity' => $it['quantity'],
                    'purchased_at' => now(),
                ]);
            }

            CartProduct::query()->where('user_id', $userId)->delete();

            $total = round($items->sum(fn($x) => $x['price'] * $x['quantity']), 2);

            return [
                'status' => 201,
                'payload' => [
                    'message' => 'Pirkimas užfiksuotas.',
                    'items' => $items,
                    'total' => $total,
                ],
            ];
        });

        return response()->json($result['payload'], $result['status']);
    }
}
