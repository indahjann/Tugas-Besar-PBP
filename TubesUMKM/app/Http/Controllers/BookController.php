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
        $books = Book::where('is_active', true)
                ->with('category')
                ->orderByDesc('created_at')
                ->paginate(12);
        
        // Get user's wishlist for checking if books are already wishlisted
        $userWishlist = [];
        if (Auth::check()) {
            $userWishlist = Wishlist::where('user_id', Auth::id())
                                  ->pluck('book_id')
                                  ->toArray();
        }
        
        // Prepare small grouped lists for the homepage categories section
        // keys expected by the welcome partial: 'fiction', 'manga', 'teen'
        // $categories = [
        //     'fiction' => Book::where('is_active', true)->where('category_id', 1)->orderByDesc('created_at')->take(4)->get(),
        //     'manga' => Book::where('is_active', true)->where('category_id', 3)->orderByDesc('created_at')->take(4)->get(),
        //     'teen' => Book::where('is_active', true)->where('category_id', 4)->orderByDesc('created_at')->take(4)->get(),
        // ];

        return view('welcome', compact('books', 'userWishlist', 'categories'));
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
