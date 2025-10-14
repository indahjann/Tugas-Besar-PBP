<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Cart;
use App\Models\User;
use App\Models\CartItem;
use Illuminate\Support\Facades\DB;

class CartService
{
    /**
     * Menambahkan item ke keranjang belanja pengguna.
     *
     * @param int $userId
     * @param int $productId
     * @param int $qty
     * @return \App\Models\CartItem
     * @throws \Exception
     */
    public function addItem(int $userId, int $productId, int $qty)
    {
        $product = Book::findOrFail($productId);
        $user = User::findOrFail($userId);

        // Memastikan stok mencukupi
        if ($product->stock < $qty) {
            throw new \Exception('Stok produk tidak mencukupi.');
        }

        // Menggunakan transaksi database untuk menjaga konsistensi data
        return DB::transaction(function () use ($user, $product, $qty) {
            // Ambil atau buat keranjang baru untuk user
            $cart = $user->cart()->firstOrCreate([]);

            // Cek apakah item sudah ada di keranjang
            $cartItem = $cart->items()->where('book_id', $product->id)->first();

            if ($cartItem) {
                // Jika sudah ada, update qty
                $cartItem->qty += $qty;
                $cartItem->save();
            } else {
                // Jika belum ada, buat item baru
                $cartItem = $cart->items()->create([
                    'book_id' => $product->id,
                    'qty' => $qty,
                ]);
            }
            
            // Kurangi stok produk
            $product->stock -= $qty;
            $product->save();

            return $cartItem;
        });
    }

    /**
     * Mengubah jumlah item di dalam keranjang.
     *
     * @param int $cartItemId
     * @param int $newQty
     * @return CartItem
     * @throws \Exception
     */
    public function updateItem(int $cartItemId, int $newQty): CartItem
    {
        return DB::transaction(function () use ($cartItemId, $newQty) {
            $cartItem = CartItem::findOrFail($cartItemId);
            $product = $cartItem->book; // Mengambil produk terkait

            $qtyDifference = $newQty - $cartItem->qty;

            // Cek apakah stok mencukupi untuk penambahan kuantitas
            if ($qtyDifference > 0 && $product->stock < $qtyDifference) {
                throw new \Exception('Stok produk tidak mencukupi.');
            }

            // Update stok produk
            $product->stock -= $qtyDifference;
            $product->save();

            // Update kuantitas item di keranjang
            $cartItem->qty = $newQty;
            $cartItem->save();
            
            return $cartItem;
        });
    }

    /**
     * Menghapus item dari keranjang.
     *
     * @param int $cartItemId
     * @return void
     */
    public function removeItem(int $cartItemId): void
    {
        DB::transaction(function () use ($cartItemId) {
            $cartItem = CartItem::findOrFail($cartItemId);
            $product = $cartItem->book;

            // Kembalikan stok produk
            $product->stock += $cartItem->qty;
            $product->save();

            // Hapus item dari keranjang
            $cartItem->delete();
        });
    }
}