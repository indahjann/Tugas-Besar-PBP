<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Models\Book;

Route::get('/', function () {
    // Ambil beberapa buku aktif terbaru untuk ditampilkan di landing
    $books = Book::where('is_active', true)
        ->orderByDesc('created_at')
        ->select('id','name','author','price','cover_image')
        ->take(10)
        ->get();
    return view('welcome', compact('books'));
});

Route::get('/books', function () {
    return view('welcome'); // Temporary: use same view
});

Route::get('/categories', function () {
    return view('welcome'); // Temporary: use same view
});

Route::get('/about', function () {
    return view('welcome'); // Temporary: use same view
});

Route::get('/contact', function () {
    return view('welcome'); // Temporary: use same view
});


Route::get('/dashboard', function () {
    // Check if user is admin
    if (auth()->user()->role !== 'admin') {
        return redirect('/')->with('error', 'Access denied. Admin only.');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Breeze auth routes removed (moved to resources/breeze_backup). Manual auth will be implemented instead.

Route::get('/products', [ProductController::class, 'index'])->name('products.index');

Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');

require __DIR__.'/auth_manual.php';