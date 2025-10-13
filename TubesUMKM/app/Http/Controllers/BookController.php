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
        $books = Book::where('is_active', true)->orderByDesc('created_at')->paginate(12);
        
        // Get user's wishlist for checking if books are already wishlisted
        $userWishlist = [];
        if (Auth::check()) {
            $userWishlist = Wishlist::where('user_id', Auth::id())
                                  ->pluck('book_id')
                                  ->toArray();
        }
        
        return view('welcome', compact('books', 'userWishlist'));
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

    // ADMIN: form tambah
    public function create()
    {
        $categories = Category::all();
        return view('admin.books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'price'      => 'required|numeric',
            'stock'      => 'required|integer',
            'description'=> 'nullable|string',
            'author'     => 'nullable|string|max:255',
            'publisher'  => 'nullable|string|max:255',
            'year'       => 'nullable|digits:4|integer',
            'isbn'       => 'nullable|string|unique:books,isbn',
            'category_id'=> 'required|exists:categories,id',
            'is_active'  => 'boolean',
        ]);

        Book::create($data);

        return redirect()->route('books.index')->with('success', 'Buku berhasil ditambahkan');
    }

    public function edit(Book $book)
    {
        $categories = Category::all();
        return view('admin.books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'price'      => 'required|numeric',
            'stock'      => 'required|integer',
            'description'=> 'nullable|string',
            'author'     => 'nullable|string|max:255',
            'publisher'  => 'nullable|string|max:255',
            'year'       => 'nullable|digits:4|integer',
            'isbn'       => 'nullable|string|unique:books,isbn,' . $book->id,
            'category_id'=> 'required|exists:categories,id',
            'is_active'  => 'boolean',
        ]);

        $book->update($data);

        return redirect()->route('books.index')->with('success', 'Buku berhasil diperbarui');
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return back()->with('success', 'Buku berhasil dihapus');
    }
}
