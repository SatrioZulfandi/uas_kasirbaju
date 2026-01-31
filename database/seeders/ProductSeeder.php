<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure Categories exist
        $categories = [
            'Baju' => \App\Models\Category::firstOrCreate(['name' => 'Baju']),
            'Celana' => \App\Models\Category::firstOrCreate(['name' => 'Celana']),
            'Jaket' => \App\Models\Category::firstOrCreate(['name' => 'Jaket']),
            'Sepatu' => \App\Models\Category::firstOrCreate(['name' => 'Sepatu']),
            'Aksesoris' => \App\Models\Category::firstOrCreate(['name' => 'Aksesoris']),
        ];

        // Seeding Products with Codes
        \App\Models\Product::create([
            'product_code' => 'P001',
            'category_id' => $categories['Celana']->id,
            'name' => 'Celana Pendek',
            'price' => 180000,
            'stock' => 40
        ]);

        \App\Models\Product::create([
            'product_code' => 'P002',
            'category_id' => $categories['Baju']->id,
            'name' => 'T-Shirt Premium',
            'price' => 150000,
            'stock' => 50
        ]);

        \App\Models\Product::create([
            'product_code' => 'P003',
            'category_id' => $categories['Celana']->id,
            'name' => 'Jeans Slim Fit',
            'price' => 350000,
            'stock' => 30
        ]);

        \App\Models\Product::create([
            'product_code' => 'P004',
            'category_id' => $categories['Jaket']->id,
            'name' => 'Jacket Denim',
            'price' => 450000,
            'stock' => 20
        ]);

        \App\Models\Product::create([
            'product_code' => 'P005',
            'category_id' => $categories['Baju']->id,
            'name' => 'Dress Casual',
            'price' => 280000,
            'stock' => 25
        ]);

        \App\Models\Product::create([
            'product_code' => 'P006',
            'category_id' => $categories['Sepatu']->id,
            'name' => 'Sneakers Sport',
            'price' => 550000,
            'stock' => 15
        ]);

        \App\Models\Product::create([
            'product_code' => 'P007',
            'category_id' => $categories['Jaket']->id,
            'name' => 'Hoodie Oversized',
            'price' => 320000,
            'stock' => 40
        ]);

        \App\Models\Product::create([
            'product_code' => 'P008',
            'category_id' => $categories['Baju']->id,
            'name' => 'Kemeja Formal',
            'price' => 250000,
            'stock' => 35
        ]);
    }
}
