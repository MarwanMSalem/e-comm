<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_scope_filter_by_role()
    {
        User::factory()->create(['role' => 'admin']);
        User::factory()->create(['role' => 'user']);

        $admins = User::filter(['role' => 'admin'])->get();
        $this->assertCount(1, $admins);
        $this->assertEquals('admin', $admins->first()->role);
    }
}
