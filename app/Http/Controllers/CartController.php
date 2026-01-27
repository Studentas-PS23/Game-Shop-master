<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartProduct;
use App\Models\Game;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class CartController extends Controller
{
    public function index()
    {
        $cartProducts = auth()->user()->cartProducts;
        $total = $cartProducts->sum('price');
        return view('cart.index', compact('cartProducts', 'total'));
    }

    public function add(Request $request, $id)
    {
        $game = Game::findOrFail($id);

        auth()->user()->cartProducts()->create([
            'game_id' => $game->id,
            'name' => $game->name,
            'price' => $game->price,
        ]);

        return redirect()->back()->with('cart_success', $game->name . ' pridėtas į krepšelį');
    }

    public function checkout(Request $request)
    {
        $cartProducts = auth()->user()->cartProducts;
        $lineItems = $cartProducts->map(function($product) {
            return [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $product->name,
                    ],
                    'unit_amount' => $product->price * 100,
                ],
                'quantity' => 1,
            ];
        })->toArray();

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('cart.index'),
            'customer_email' => auth()->user()->email,
        ]);

        return redirect($session->url);
    }

    public function remove($id)
    {
        $cartProduct = auth()->user()->cartProducts()->findOrFail($id);
        $cartProduct->delete();

        return redirect()->route('cart.index')->with('cart_success', 'Žaidimas pašalintas iš krepšelio!');
    }
}