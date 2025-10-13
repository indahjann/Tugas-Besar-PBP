<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\WishlistController;

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
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('/cart/data', [CartController::class, 'getCartData'])->name('cart.data');
    Route::post('/cart/add', [CartController::class, 'addItem'])->name('cart.add');
    Route::patch('/cart/update/{cartItemId}', [CartController::class, 'updateItem'])->name('cart.update');
    Route::delete('/cart/remove/{cartItemId}', [CartController::class, 'removeItem'])->name('cart.remove');

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // Checkout
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
});

// Breeze auth routes removed (moved to resources/breeze_backup). Manual auth will be implemented instead.

Route::get('/products', [ProductController::class, 'index'])->name('products.index');

Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');

// General search endpoint (used by navbar and global search)
Route::get('/search', [ProductController::class, 'search'])->name('search.global');
Route::get('/search/suggestions', [ProductController::class, 'suggestions'])->name('search.suggestions');

require __DIR__.'/auth_manual.php';

// Temporary debug route to inspect remember-me behavior. Remove in production.
use Illuminate\Support\Facades\Auth;
Route::get('/check-remember', function () {
    return response()->json([
        'authenticated' => Auth::check(),
        'via_remember' => Auth::viaRemember(),
        'user_id' => Auth::id(),
    ]);
});