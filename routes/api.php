<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\GameController;

Route::get('/', [HomeController::class, 'index']);

Route::get('/games', [GameController::class, 'index']);
Route::get('/games/{game:slug}', [GameController::class, 'show']);

Route::post('/games', [GameController::class, 'store']);
Route::put('/games/{game:slug}', [GameController::class, 'update']);
Route::delete('/games/{game:slug}', [GameController::class, 'destroy']);
