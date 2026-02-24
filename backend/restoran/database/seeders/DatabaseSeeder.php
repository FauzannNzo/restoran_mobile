<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Akun ADMIN
        User::create([
            'name' => 'Admin',
            'email' => 'admin@resto.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
        ]);

        // Akun KASIR
        User::create([
            'name' => 'Kasir',
            'email' => 'kasir@resto.com',
            'password' => bcrypt('kasir123'),
            'role' => 'kasir',
        ]);

        // Akun CHEF
        User::create([
            'name' => 'Head Chef',
            'email' => 'chef@resto.com',
            'password' => bcrypt('chef123'),
            'role' => 'chef',
        ]);

        // Akun untuk Pelanggan
        User::create([
            'name' => 'Pelanggan',
            'email' => 'pelanggan@resto.com',
            'password' => bcrypt('pelanggan123'),
            'role' => 'pelanggan',
        ]);
    }
}
