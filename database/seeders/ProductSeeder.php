<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'Wireless Mouse',        'description' => '2.4G ergonomic mouse',                     'price' => 19.99, 'category' => 'Electronics', 'quantity' => 150],
            ['name' => 'Mechanical Keyboard',   'description' => 'RGB backlit, blue switches',               'price' => 79.50, 'category' => 'Electronics', 'quantity' => 75],
            ['name' => 'USB-C Hub',             'description' => '7‑in‑1 multiport adapter',                 'price' => 34.00, 'category' => 'Electronics', 'quantity' => 120],
            ['name' => 'Noise Cancel Headset',  'description' => 'Over‑ear ANC headset',                     'price' => 129.99, 'category' => 'Electronics', 'quantity' => 60],
            ['name' => 'Portable SSD 1TB',      'description' => 'USB 3.2 Gen2 portable storage',            'price' => 99.00, 'category' => 'Electronics', 'quantity' => 80],

            ['name' => 'Laravel In Action',     'description' => 'Practical Laravel guide',                  'price' => 29.95, 'category' => 'Books',       'quantity' => 40],
            ['name' => 'PHP Cookbook',          'description' => 'Solutions & examples in PHP',              'price' => 39.95, 'category' => 'Books',       'quantity' => 35],
            ['name' => 'Clean Code Tee',        'description' => 'Cotton T‑shirt for devs',                  'price' => 18.00, 'category' => 'Apparel',     'quantity' => 110],
            ['name' => 'Debug Mode Hoodie',     'description' => 'Zip hoodie with dev print',                'price' => 45.00, 'category' => 'Apparel',     'quantity' => 55],
            ['name' => 'Syntax Socks',          'description' => 'Colorful code pattern socks',              'price' => 8.99,  'category' => 'Apparel',     'quantity' => 200],

            ['name' => 'Coffee Mug',            'description' => 'Ceramic “Ship It” mug',                     'price' => 9.50,  'category' => 'Home',        'quantity' => 300],
            ['name' => 'Desk Plant',            'description' => 'Low‑care succulent in pot',                'price' => 12.75, 'category' => 'Home',        'quantity' => 85],
            ['name' => 'Cable Organizer',       'description' => 'Reusable cable ties pack',                 'price' => 6.25,  'category' => 'Home',        'quantity' => 500],
            ['name' => 'Mini Drone',            'description' => 'Entry‑level quadcopter w/ camera',         'price' => 59.90, 'category' => 'Toys',        'quantity' => 45],
            ['name' => 'Puzzle Cube',           'description' => '3x3 speed cube',                           'price' => 4.99,  'category' => 'Toys',        'quantity' => 250],
        ];

        foreach ($products as $p) {
            Product::create($p);
        }
    }
}
