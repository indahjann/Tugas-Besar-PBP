<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cart;
use App\Models\User;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        // Buat satu cart deterministik untuk setiap user (jika belum ada)
        User::orderBy('id')->get()->each(function ($user) {
            Cart::firstOrCreate(['user_id' => $user->id]);
        });
    }
}
