<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_order_more_than_stock()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['quantity' => 2]);
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->postJson('/api/v1/orders', [
                             'user_id' => $user->id,
                             'product_id' => $product->id,
                             'date' => now()->toDateString(),
                             'quantity' => 5,
                         ]);

        $response->assertStatus(400)
                 ->assertJsonFragment(['error' => 'Not enough product in stock.']);
    }
}
