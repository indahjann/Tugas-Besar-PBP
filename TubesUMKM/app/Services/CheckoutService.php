<?php

namespace App\Services;

use App\Models\Book;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class CheckoutService
{
    /**
     * Memproses keranjang pengguna menjadi pesanan dengan keamanan transaksi yang ditingkatkan.
     *
     * @param int $userId
     * @param array $orderDetails
     * @return Order
     * @throws \Exception
     */
    public function processCheckout(int $userId, array $orderDetails): Order
    {
        $user = User::with('cart.items.book')->findOrFail($userId);
        $cart = $user->cart;

        if (!$cart || $cart->items->isEmpty()) {
            throw new \Exception('Keranjang Anda kosong.');
        }

        // Kumpulkan ID buku dan kuantitas untuk penguncian (locking)
        $bookIds = $cart->items->pluck('book_id')->toArray();

        return DB::transaction(function () use ($user, $cart, $orderDetails, $bookIds) {
            // 1. Kunci baris buku yang relevan untuk mencegah race condition
            $books = Book::whereIn('id', $bookIds)->lockForUpdate()->get()->keyBy('id');

            // 2. Validasi ulang stok di dalam transaksi yang aman
            foreach ($cart->items as $item) {
                $book = $books->get($item->book_id);
                if (!$book || $book->stock < $item->quantity) {
                    throw new \Exception("Stok untuk produk '{$item->book->name}' tidak mencukupi.");
                }
            }

            // 3. Hitung total harga
            $totalPrice = $cart->items->sum(function ($item) use ($books) {
                return $books->get($item->book_id)->price * $item->quantity;
            });
            
            // 4. Buat entri pesanan baru
            $order = $user->orders()->create([
                'total' => $totalPrice,
                'status' => 'pending',
                'shipping_address' => $orderDetails['shipping_address'],
                'phone_number' => $orderDetails['phone_number'],
            ]);

            // 5. Pindahkan item dan kurangi stok
            foreach ($cart->items as $item) {
                $book = $books->get($item->book_id);
                $order->items()->create([
                    'book_id' => $item->book_id,
                    'quantity' => $item->quantity,
                    'price' => $book->price, // Simpan harga saat ini
                ]);
                
                // Kurangi stok
                $book->stock -= $item->quantity;
                $book->save();
            }

            // 6. Kosongkan keranjang
            $cart->items()->delete();
            
            // Muat relasi sebelum mengembalikan untuk efisiensi
            return $order->load('items.book');
        });
    }
}