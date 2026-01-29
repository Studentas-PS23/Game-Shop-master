<?php

use App\Models\Game;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('guest cannot access cart', function () {
    $this->getJson('/api/cart')->assertStatus(401);
});

test('user can add item and see it in cart', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $game = Game::create([
        'name' => 'Test Game',
        'slug' => 'test-game',
        'price' => 19.99,
        'description' => null,
        'image' => null,
    ]);

    $this->postJson('/api/cart/items', ['game_id' => $game->id, 'quantity' => 2])
        ->assertStatus(201)
        ->assertJsonPath('count', 2);

    $this->getJson('/api/cart')
        ->assertOk()
        ->assertJsonPath('items.0.game_id', $game->id)
        ->assertJsonPath('items.0.quantity', 2);
});

test('user cannot remove item that is not in their cart', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $game = Game::create([
        'name' => 'Another Game',
        'slug' => 'another-game',
        'price' => 9.99,
        'description' => null,
        'image' => null,
    ]);

    $this->deleteJson('/api/cart/items/' . $game->id)->assertStatus(404);
});
