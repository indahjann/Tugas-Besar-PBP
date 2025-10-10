<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoriesController;

Route::get('/', [BookController::class, 'index'])->name('books.index');

Route::get('/books', function () {
    return view('welcome'); 
});

Route::get('/categories', [CategoriesController::class, 'index'])->name('categories.index');
Route::get('/categories/{category}', [CategoriesController::class, 'show'])->name('categories.show');

Route::get('/about', function () {
    return view('welcome'); 
});

Route::get('/contact', function () {
    return view('welcome'); 
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

Route::get('/products', [ProductController::class, 'index'])->name('products.index');

Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');

// Rute untuk Keranjang Belanja (dilindungi oleh middleware auth)
Route::middleware('auth')->group(function () {
    Route::post('/cart/add', [CartController::class, 'addItem'])->name('cart.add');
});

require __DIR__.'/auth_manual.php';