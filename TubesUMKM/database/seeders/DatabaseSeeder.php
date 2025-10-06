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
        // Seed categories first (referenced by books)
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            BookSeeder::class,
            CartSeeder::class,       // buat keranjang per user
            CartItemSeeder::class,   // isi keranjang dengan buku
            OrderSeeder::class,      // buat pesanan dasar
            OrderItemSeeder::class,  // isi pesanan dan hitung total
        ]);
    }
}
