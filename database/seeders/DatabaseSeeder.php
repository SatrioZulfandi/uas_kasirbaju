<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default users
        \App\Models\User::create([
            'name' => 'Admin',
            'email' => 'admin@kasir.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
        ]);

        \App\Models\User::create([
            'name' => 'Kasir',
            'email' => 'kasir@kasir.com',
            'password' => bcrypt('kasir123'),
            'role' => 'kasir',
        ]);

        // Seed products
        $this->call(ProductSeeder::class);
    }
}
