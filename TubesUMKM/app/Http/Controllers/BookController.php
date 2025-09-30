<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    // Semua user bisa lihat katalog
    public function index()
    {
        $books = Book::with('category')->where('is_active', true)->paginate(12);
        return view('books.index', compact('books'));
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
