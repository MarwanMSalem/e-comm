<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customer = User::where('role', 'user')->first();
        $employee = User::where('role', 'employee')->first();
        $product  = Product::first();

        if (! $customer || ! $product) {
            // Minimal guard: if required records missing, bail.
            return;
        }

        Order::create([
            'user_id'      => $customer->id,
            'product_id'   => $product->id,
            'date'         => now()->toDateString(),
            'status'       => 'pending',
            'employee_id'  => $employee?->id, // may be null if no employee
            'is_assigned'  => $employee !== null,
        ]);
    }
}
