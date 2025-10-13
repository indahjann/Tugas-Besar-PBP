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
    public function searchProducts(string $searchTerm, int $perPage = 12, ?string $sort = null)
    {
        // Return a paginator so the categories view (which expects a paginated $books)
        // can be reused for search results.
        // Only select required columns to reduce payload and query cost
        $query = Book::select(['id', 'name', 'author', 'price', 'cover_image', 'category_id', 'is_active', 'created_at'])
            ->where('is_active', true)
            ->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', "%{$searchTerm}%")
                      ->orWhereHas('category', function ($q) use ($searchTerm) {
                          $q->where('name', 'like', "%{$searchTerm}%");
                      });
            })
            ->latest()
            ->with(['category' => function($q){
                $q->select(['id','name']);
            }]);

        // Apply sorting if provided
        if ($sort) {
            switch ($sort) {
                case 'name_asc':
                    $query->reorder('name', 'asc');
                    break;
                case 'name_desc':
                    $query->reorder('name', 'desc');
                    break;
                case 'price_asc':
                    $query->reorder('price', 'asc');
                    break;
                case 'price_desc':
                    $query->reorder('price', 'desc');
                    break;
                default:
                    // keep default latest()
                    break;
            }
        }

        // Use paginate to provide a length-aware paginator (views call total(), firstItem(), etc.)
        return $query->paginate($perPage);
    }
}