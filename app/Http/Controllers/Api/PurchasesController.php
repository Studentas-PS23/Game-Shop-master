<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PurchasedProduct;
use Illuminate\Http\Request;

class PurchasesController extends Controller
{
    // GET /api/purchases
    public function index(Request $request)
    {
        $items = PurchasedProduct::query()
            ->where('user_id', $request->user()->id)
            ->orderByDesc('purchased_at')
            ->orderByDesc('id')
            ->get()
            ->map(fn($p) => [
                'id' => (int) $p->id,
                'game_id' => (int) $p->game_id,
                'name' => (string) $p->name,
                'price' => (float) $p->price,
                'quantity' => (int) $p->quantity,
                'line_total' => round(((float)$p->price) * ((int)$p->quantity), 2),
                'purchased_at' => optional($p->purchased_at)->toISOString(),
            ]);

        return response()->json([
            'items' => $items,
            'total' => round($items->sum('line_total'), 2),
        ]);
    }
}
