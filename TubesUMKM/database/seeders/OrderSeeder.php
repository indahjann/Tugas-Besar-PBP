<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User;
use App\Models\Book;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        if (Book::count() === 0) {
            $this->command->warn('Tidak ada buku untuk membuat orders. Jalankan BookSeeder dulu.');
            return;
        }

        // Buat satu order deterministik untuk setiap user (jika belum ada)
        User::orderBy('id')->get()->each(function ($user) {
            Order::firstOrCreate([
                'user_id' => $user->id,
                'status' => 'pending',
            ], [
                'total' => 0, // akan di-update oleh OrderItemSeeder
                'address_text' => 'Alamat User #' . $user->id . ' Jl. Contoh No.' . (100 + $user->id),
            ]);
        });

        // Tambahkan satu demo order statis (admin) jika admin ada
        $admin = User::where('username', 'admin')->first();
        if ($admin) {
            Order::firstOrCreate([
                'user_id' => $admin->id,
                'status' => 'pending',
            ], [
                'total' => 0,
                'address_text' => 'Alamat Admin Pusat, Jl. Admin No.1',
            ]);
        }
    }
}
