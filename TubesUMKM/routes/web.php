<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (Guest & Authenticated)
|--------------------------------------------------------------------------
*/

// Home page
Route::get('/', [BookController::class, 'index'])->name('books.index');

// Categories
Route::get('/categories', [CategoriesController::class, 'index'])->name('categories.index');
Route::get('/categories/{category}', [CategoriesController::class, 'show'])->name('categories.show');

// Book detail (public access)
Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');

// Search
Route::get('/search', [ProductController::class, 'search'])->name('search.global');
Route::get('/search/suggestions', [ProductController::class, 'suggestions'])->name('search.suggestions');

// Static pages
Route::get('/about', function () {
    return view('about'); 
})->name('about');

Route::get('/contact', function () {
    return view('contact'); 
})->name('contact');

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    
    // Dashboard (User)
    Route::get('/dashboard', function () {
        // Redirect admin to admin dashboard
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        // User dashboard shows books homepage
        return redirect()->route('books.index');
    })->name('dashboard');
    
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Cart Management (User Only)
    Route::middleware('user')->prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::get('/data', [CartController::class, 'getCartData'])->name('data');
        Route::post('/add', [CartController::class, 'addItem'])->name('add');
        Route::patch('/update/{cartItemId}', [CartController::class, 'updateItem'])->name('update');
        Route::delete('/remove/{cartItemId}', [CartController::class, 'removeItem'])->name('remove');
    });

    // Wishlist Management (User Only)
    Route::middleware('user')->prefix('wishlist')->name('wishlist.')->group(function () {
        Route::get('/', [WishlistController::class, 'index'])->name('index');
        Route::post('/toggle', [WishlistController::class, 'toggle'])->name('toggle');
    });

    // Checkout (User Only)
    Route::middleware('user')->group(function () {
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    });

    // Order Management (User Only)
    Route::middleware('user')->prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        Route::post('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');
    });
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Books Management
    Route::prefix('books')->name('books.')->group(function () {
        Route::get('/', [AdminController::class, 'booksIndex'])->name('index');
        Route::get('/create', [AdminController::class, 'booksCreate'])->name('create');
        Route::post('/', [AdminController::class, 'booksStore'])->name('store');
        Route::get('/{book}/edit', [AdminController::class, 'booksEdit'])->name('edit');
        Route::put('/{book}', [AdminController::class, 'booksUpdate'])->name('update');
        Route::delete('/{book}', [AdminController::class, 'booksDestroy'])->name('destroy');
    });

    // Orders Management (Admin)
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [AdminController::class, 'ordersIndex'])->name('index');
        Route::get('/{order}', [AdminController::class, 'ordersShow'])->name('show');
        Route::patch('/{order}/status', [AdminController::class, 'ordersUpdateStatus'])->name('updateStatus');
    });

    // Categories Management
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [AdminController::class, 'categoriesIndex'])->name('index');
        Route::post('/', [AdminController::class, 'categoriesStore'])->name('store');
        Route::put('/{category}', [AdminController::class, 'categoriesUpdate'])->name('update');
        Route::delete('/{category}', [AdminController::class, 'categoriesDestroy'])->name('destroy');
    });
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth_manual.php';

/*
|--------------------------------------------------------------------------
| Development/Debug Routes (Remove in production)
|--------------------------------------------------------------------------
*/

// Check remember-me functionality
Route::get('/check-remember', function () {
    return response()->json([
        'authenticated' => Auth::check(),
        'via_remember' => Auth::viaRemember(),
        'user_id' => Auth::id(),
        'user_role' => Auth::user()?->role,
    ]);
})->middleware('web');