<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('123456789');

        // Admin
        $admin = User::create([
            'name' => 'Admin One',
            'email' => 'admin@example.com',
            'password' => $password,
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Employees
        $employee1 = User::create([
            'name' => 'Employee One',
            'email' => 'employee1@example.com',
            'password' => $password,
            'role' => 'employee',
            'email_verified_at' => now(),
        ]);

        $employee2 = User::create([
            'name' => 'Employee Two',
            'email' => 'employee2@example.com',
            'password' => $password,
            'role' => 'employee',
            'email_verified_at' => now(),
        ]);

        // Customers / standard users
        User::create([
            'name' => 'User One',
            'email' => 'user1@example.com',
            'password' => $password,
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'User Two',
            'email' => 'user2@example.com',
            'password' => $password,
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'User Three',
            'email' => 'user3@example.com',
            'password' => $password,
            'role' => 'user',
            'email_verified_at' => now(),
        ]);
    }
}
