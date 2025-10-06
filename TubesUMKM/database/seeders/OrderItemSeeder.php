<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Book;

class OrderItemSeeder extends Seeder
{
    public function run(): void
    {
        if (Book::count() === 0) {
            $this->command->warn('Tidak ada buku untuk membuat order items. Jalankan BookSeeder dulu.');
            return;
        }

        // Ambil semua buku sekali saja (urut id) untuk memastikan deterministik
        $allBooks = Book::orderBy('id')->get();

        Order::orderBy('id')->get()->each(function ($order, $index) use ($allBooks) {
            // Set: order ke-n pakai (n%jumlah_buku) dan (n+1)%jumlah_buku, maksimal 2 item
            if ($allBooks->count() === 0) { return; }
            $selected = $allBooks->slice($index % $allBooks->count(), 2); // 0 atau 2 buku tergantung sisa
            if ($selected->isEmpty()) { $selected = $allBooks->take(1); }

            $total = 0;
            $line = 1;
            foreach ($selected as $book) {
                // qty deterministik: 1 untuk item pertama, 2 untuk kedua
                $qty = $line; // 1, lalu 2
                $price = $book->price;
                $subtotal = $price * $qty;
                OrderItem::create([
                    'order_id' => $order->id,
                    'book_id' => $book->id,
                    'price' => $price,
                    'qty' => $qty,
                    'subtotal' => $subtotal,
                ]);
                $total += $subtotal;
                $line++;
            }
            $order->update(['total' => $total]);
        });
    }
}
