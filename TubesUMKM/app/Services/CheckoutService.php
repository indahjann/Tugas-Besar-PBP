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
            $books = Book::whereIn('id', $bookIds)->where('is_active', true)->lockForUpdate()->get()->keyBy('id');

            // 2. Validasi ulang stok di dalam transaksi yang aman
            foreach ($cart->items as $item) {
                $book = $books->get($item->book_id);

                if (!$book) {
                    throw new \Exception("Buku '{$item->book->name}' sudah tidak tersedia.");
                }

                if ($book->stock < $item->qty) {
                    throw new \Exception("Stok untuk produk '{$book->name}' tidak mencukupi. Stok tersedia: {$book->stock}");
                }
            }

            // 3. Hitung total harga
            $totalPrice = $cart->items->sum(function ($item) use ($books) {
                return $books->get($item->book_id)->price * $item->qty;
            });
            
            // 4. Buat entri pesanan baru
            $order = $user->orders()->create([
                'total' => $totalPrice,
                'status' => Order::STATUS_PENDING,
                'address_text' => $orderDetails['address_text'] ?? ($orderDetails['adress_text'] ?? ''),
            ]);

            // 5. Pindahkan item dan kurangi stok
            foreach ($cart->items as $item) {
                $book = $books->get($item->book_id);
                $subtotal = $book->price * $item->qty;
                $order->items()->create([
                    'book_id' => $item->book_id,
                    'qty' => $item->qty,
                    'price' => $book->price, // Simpan harga saat ini
                    'subtotal' => $subtotal
                ]);
                
                // Kurangi stok
                $book->stock -= $item->qty;
                $book->save();
            }

            // 6. Kosongkan keranjang
            $cart->items()->delete();
            
            // Muat relasi sebelum mengembalikan untuk efisiensi
            return $order->load('orderItems.book');
        });
    }
}