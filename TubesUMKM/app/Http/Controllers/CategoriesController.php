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
     * Display categories page with optional category filtering
     */
    public function index(Request $request)
    {
        // Get all categories for sidebar
        $categories = Category::withCount('books')->orderBy('name')->get();
        
        // Initialize books query
        $booksQuery = Book::with('category');
        
        // Filter by category if provided
        $selectedCategory = null;
        if ($request->has('category') && $request->category) {
            $selectedCategory = Category::findOrFail($request->category);
            $booksQuery->where('category_id', $request->category);
        }
        
        // Apply sort parameter
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

        // Get books with pagination
        $books = $booksQuery->paginate(12);
        
        // Get popular categories (categories with most books)
        $popularCategories = Category::withCount('books')
            ->orderBy('books_count', 'desc')
            ->limit(5)
            ->get();
        
        // Get user's wishlist for checking if books are already wishlisted
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
     * Show specific category (called when clicking breadcrumb or category link)
     */
    public function show($categoryId, Request $request)
    {
        // Find category by ID only (no slug support)
        $selectedCategory = Category::findOrFail($categoryId);
        
        // Get all categories for sidebar
        $categories = Category::withCount('books')->orderBy('name')->get();
        
        // Initialize books query for this category
        $booksQuery = Book::with('category')
            ->where('category_id', $selectedCategory->id);
        
        // Apply sort parameter
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
        
        // Get books with pagination
        $books = $booksQuery->paginate(12)->withQueryString();
        
        // Get popular categories (categories with most books)
        $popularCategories = Category::withCount('books')
            ->orderBy('books_count', 'desc')
            ->limit(5)
            ->get();
        
        // Get user's wishlist for checking if books are already wishlisted
        $userWishlist = [];
        if (Auth::check()) {
            $userWishlist = Wishlist::where('user_id', Auth::id())
                                  ->pluck('book_id')
                                  ->toArray();
        }
        
        // Use the same view as index, but with selectedCategory set
        return view('categories', compact(
            'categories', 
            'books', 
            'selectedCategory', 
            'popularCategories',
            'userWishlist'
        ));
    }
}