<?php

use Illuminate\Support\Facades\Route;

Route::get('/{any}', function () {
    return response()->file(public_path('spa/index.html'));
})->where('any', '.*');


/*
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

// Pradinis puslapis ir žaidimai
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/games', [GameController::class, 'index'])->name('games.index');
Route::get('/search', [HomeController::class, 'index'])->name('search');
Route::get('/games/search', [GameController::class, 'search'])->name('games.search');
Route::get('/games/{slug}', [GameController::class, 'show'])->name('games.show');

// Profilis (tik prisijungus ir patvirtinus el. paštą)
Route::get('/profile', [UserController::class, 'profile'])
    ->middleware(['auth', 'verified'])
    ->name('profile');
Route::get('/profile/pdf', [UserController::class, 'downloadPdf'])->name('profile.pdf');
Route::put('/profile', [UserController::class, 'update'])->name('profile.update');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index')->middleware('auth');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add')->middleware('auth');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove')->middleware('auth');
Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
Route::post('/stripe/webhook', [App\Http\Controllers\StripeWebhookController::class, 'handle']);

// Purchased games
Route::get('/purchased', [UserController::class, 'purchased'])->name('users.purchased');
Route::get('/stripe/success', function() {
    return view('cart.success');
})->name('stripe.success');

// Laravel Breeze auth maršrutai (privaloma!)
require __DIR__.'/auth.php';

Route::get('/dashboard', function () {
    return redirect()->route('profile');
})->middleware(['auth', 'verified'])->name('dashboard');

*/