<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Book;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoriesController extends Controller
{
    /**
     * Menampilkan halaman kategori dengan optional category filtering
     */
    public function index(Request $request)
    {
        // Get all categories untuk sidebar
        $categories = Category::withCount('books')->orderBy('name')->get();
        
        // Inisialisasi kueri books
        $booksQuery = Book::with('category');
        
        // Filter berdasarkan kategori jika ada
        $selectedCategory = null;
        if ($request->has('category') && $request->category) {
            $selectedCategory = Category::findOrFail($request->category);
            $booksQuery->where('category_id', $request->category);
        }
        
        $sort = $request->input('sort');
        switch ($sort) {
            case 'name_asc':
                $booksQuery->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $booksQuery->orderBy('name', 'desc');
                break;
            case 'price_asc':
                $booksQuery->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $booksQuery->orderBy('price', 'desc');
                break;
            default:
                $booksQuery->orderBy('name');
                break;
        }

        // Ambil books dengan pagination
        $books = $booksQuery->paginate(15);
        
        // Get popular categories (kategori dengan jumlah buku terbanyak)
        $popularCategories = Category::withCount('books')
            ->orderBy('books_count', 'desc')
            ->limit(5)
            ->get();
        
        $userWishlist = [];
        if (Auth::check()) {
            $userWishlist = Wishlist::where('user_id', Auth::id())
                                  ->pluck('book_id')
                                  ->toArray();
        }
        
        return view('categories', compact(
            'categories', 
            'books', 
            'selectedCategory', 
            'popularCategories',
            'userWishlist'
        ));
    }
    
    /**
     * Tampilkan kategori spesifik (disebut juga clicking breadcrumb atau category link)
     */
    public function show($categoryId, Request $request)
    {
        $selectedCategory = Category::findOrFail($categoryId);
        
        $categories = Category::withCount('books')->orderBy('name')->get();
        
        $booksQuery = Book::with('category')
            ->where('category_id', $selectedCategory->id);
        
        $sort = $request->input('sort', 'name_asc');
        switch ($sort) {
            case 'name_asc':
                $booksQuery->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $booksQuery->orderBy('name', 'desc');
                break;
            case 'price_asc':
                $booksQuery->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $booksQuery->orderBy('price', 'desc');
                break;
            default:
                $booksQuery->orderBy('name', 'asc');
                break;
        }
        
        // Ambil buku pagination
        $books = $booksQuery->paginate(12)->withQueryString();
        
        // Get popular categories (kategori dengan jumlah buku terbanyak)
        $popularCategories = Category::withCount('books')
            ->orderBy('books_count', 'desc')
            ->limit(5)
            ->get();
        
        $userWishlist = [];
        if (Auth::check()) {
            $userWishlist = Wishlist::where('user_id', Auth::id())
                                  ->pluck('book_id')
                                  ->toArray();
        }
        
        return view('categories', compact(
            'categories', 
            'books', 
            'selectedCategory', 
            'popularCategories',
            'userWishlist'
        ));
    }
}