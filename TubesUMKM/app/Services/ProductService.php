<?php

namespace App\Services;

use App\Models\Book; // Menggunakan model Book sesuai implementasi yang ada
use Illuminate\Database\Eloquent\Collection;

class ProductService
{
    /**
     * Mengambil semua produk yang statusnya aktif.
     *
     * @return Collection
     */
    public function getActiveProducts(): Collection
    {
        // Ganti 'Book' dengan 'Product' jika Anda melakukan refactoring nama model
        return Book::where('is_active', true)->latest()->get();
    }

    /**
     * Mencari produk berdasarkan nama atau kategori.
     *
     * @param string $searchTerm
     * @return Collection
     */
    public function searchProducts(string $searchTerm): Collection
    {
        return Book::where('is_active', true)
            ->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', "%{$searchTerm}%")
                      ->orWhereHas('category', function ($q) use ($searchTerm) {
                          $q->where('name', 'like', "%{$searchTerm}%");
                      });
            })
            ->latest()
            ->get();
    }
}