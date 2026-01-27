<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\ExportController;
use App\Http\Controllers\Api\AuthController;

Route::get('/home', [HomeController::class, 'index']);

Route::get('/games', [GameController::class, 'index']);
Route::get('/games/{game:slug}', [GameController::class, 'show']);

Route::get('/exports/games.xml', [ExportController::class, 'games']);

// AUTH (public)
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // CRUD (apsaugotas – authorization)
    Route::post('/games', [GameController::class, 'store'])->middleware('can:manage-games');
    Route::put('/games/{game:slug}', [GameController::class, 'update'])->middleware('can:manage-games');
    Route::delete('/games/{game:slug}', [GameController::class, 'destroy'])->middleware('can:manage-games');
});
