<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Book;
use Illuminate\Http\Request;

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
        
        // Get books with pagination
        $books = $booksQuery->orderBy('name')->paginate(12);
        
        // Get popular categories (categories with most books)
        $popularCategories = Category::withCount('books')
            ->orderBy('books_count', 'desc')
            ->limit(5)
            ->get();
        
        return view('categories', compact(
            'categories', 
            'books', 
            'selectedCategory', 
            'popularCategories'
        ));
    }
    
    /**
     * Show specific category
     */
    public function show(Category $category)
    {
        $books = $category->books()->paginate(12);
        $categories = Category::withCount('books')->orderBy('name')->get();
        
        return view('categories.show', compact('category', 'books', 'categories'));
    }
}