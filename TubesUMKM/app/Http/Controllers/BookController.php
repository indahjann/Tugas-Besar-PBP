<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function index()
    {
        // Get user's wishlist for checking if books are already wishlisted
        $userWishlist = [];
        if (Auth::check()) {
            $userWishlist = Wishlist::where('user_id', Auth::id())
                                  ->pluck('book_id')
                                  ->toArray();
        }
        
        // Prepare multiple themed sections for homepage
        $sections = [
            // Buku Terbaru (Recent Books)
            'recent' => [
                'title' => 'Buku Terbaru',
                'books' => Book::where('is_active', true)
                    ->with('category')
                    ->orderByDesc('created_at')
                    ->take(12)
                    ->get()
            ],
            
            // Bestsellers (simulasi berdasarkan stock rendah = banyak terjual)
            'bestsellers' => [
                'title' => 'Bestsellers',
                'books' => Book::where('is_active', true)
                    ->with('category')
                    ->where('stock', '<', 25)
                    ->orderBy('stock', 'asc')
                    ->take(12)
                    ->get()
            ],
            
            // Fiction Books
            'fiction' => [
                'title' => 'Fiction',
                'books' => Book::where('is_active', true)
                    ->with('category')
                    ->where('category_id', 1)
                    ->orderByDesc('created_at')
                    ->take(12)
                    ->get()
            ],
            
            // Manga & Comics  
            'manga' => [
                'title' => 'Manga & Comics',
                'books' => Book::where('is_active', true)
                    ->with('category')
                    ->where('category_id', 3)
                    ->orderByDesc('created_at')
                    ->take(12)
                    ->get()
            ],
            
            // Self-Help
            'selfhelp' => [
                'title' => 'Self-Help',
                'books' => Book::where('is_active', true)
                    ->with('category')
                    ->where('category_id', 5)
                    ->orderByDesc('created_at')
                    ->take(12)
                    ->get()
            ],
            
            // Technology Books
            'technology' => [
                'title' => 'Technology',
                'books' => Book::where('is_active', true)
                    ->with('category')
                    ->where('category_id', 6)
                    ->orderByDesc('created_at')
                    ->take(12)
                    ->get()
            ]
        ];

        return view('welcome', compact('sections', 'userWishlist'));
    }

    /**
     * Menampilkan page detail buku.
     */
    public function show(Book $book)
    {
        // Cek apakah buku aktif 
        if (!$book->is_active) {
            abort(404, 'Book not found or not available');
        }

        // Wishlist status untuk buku
        $isWishlisted = false;
        if (Auth::check()) {
            $isWishlisted = Wishlist::where('user_id', Auth::id())
                                  ->where('book_id', $book->id)
                                  ->exists();
        }

        // Mengambil buku yang related
        $relatedBooks = Book::where('category_id', $book->category_id)
                           ->where('id', '!=', $book->id)
                           ->where('is_active', true)
                           ->limit(4)
                           ->get();

        // Mengambil wishlist user untuk buku relate
        $userWishlist = [];
        if (Auth::check()) {
            $userWishlist = Wishlist::where('user_id', Auth::id())
                                  ->pluck('book_id')
                                  ->toArray();
        }

        return view('book', compact('book', 'isWishlisted', 'relatedBooks', 'userWishlist'));
    }
}
