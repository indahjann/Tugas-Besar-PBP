<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Book;

class CartItemSeeder extends Seeder
{
    public function run(): void
    {
        if (Book::count() === 0) {
            $this->command->warn('Tidak ada buku untuk membuat cart items. Jalankan BookSeeder dulu.');
            return;
        }

        // Tambahkan item deterministik ke setiap cart:
        // - Cart ke-n akan berisi buku id (n%jumlah_buku)+1 dan (n+1)%jumlah_buku +1 (maks 2 item)
        $books = Book::orderBy('id')->get();
        if ($books->count() === 0) { return; }

        Cart::orderBy('id')->get()->each(function ($cart, $index) use ($books) {
            $count = $books->count();
            $first = $books[$index % $count];
            $second = $books[($index + 1) % $count];

            // Item pertama qty = 1
            CartItem::firstOrCreate([
                'cart_id' => $cart->id,
                'book_id' => $first->id,
            ], ['qty' => 1]);

            // Item kedua qty = 2 (hindari duplikasi jika sama)
            if ($second->id !== $first->id) {
                CartItem::firstOrCreate([
                    'cart_id' => $cart->id,
                    'book_id' => $second->id,
                ], ['qty' => 2]);
            }
        });
    }
}
