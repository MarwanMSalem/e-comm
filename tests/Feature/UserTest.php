<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_login()
    {
        $response = $this->postJson('/api/v1/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $response->assertStatus(201)
                 ->assertJsonStructure(['user', 'token']);
    }

    public function test_user_can_get_profile()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->getJson("/api/v1/users/{$user->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['email' => $user->email]);
    }

    public function test_user_can_update_own_profile()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->putJson("/api/v1/users/{$user->id}", [
                             'name' => 'Updated Name',
                             'email' => $user->email,
                         ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Updated Name']);
    }
}
