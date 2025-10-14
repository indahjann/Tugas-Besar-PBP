<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Index untuk Books (buat hanya jika belum ada)
        $booksCategoryIndex = DB::select("SHOW INDEX FROM `books` WHERE Key_name = ?", ['books_category_id_index']);
        if (empty($booksCategoryIndex)) {
            Schema::table('books', function (Blueprint $table) {
                $table->index('category_id');
            });
        }

        $booksIsActiveIndex = DB::select("SHOW INDEX FROM `books` WHERE Key_name = ?", ['books_is_active_index']);
        if (empty($booksIsActiveIndex)) {
            Schema::table('books', function (Blueprint $table) {
                $table->index('is_active');
            });
        }

        $booksComposite = DB::select("SHOW INDEX FROM `books` WHERE Key_name = ?", ['books_is_active_created_at_index']);
        if (empty($booksComposite)) {
            Schema::table('books', function (Blueprint $table) {
                $table->index(['is_active', 'created_at']); // Composite index untuk listing
            });
        }

        $booksName = DB::select("SHOW INDEX FROM `books` WHERE Key_name = ?", ['books_name_index']);
        if (empty($booksName)) {
            Schema::table('books', function (Blueprint $table) {
                $table->index('name'); // Untuk search
            });
        }

        // Index untuk Cart Items
        Schema::table('cart_items', function (Blueprint $table) {
            $table->index('cart_id');
            $table->index('book_id');
        });

        // Create unique index for cart_items only if it doesn't exist already
        $cartItemUnique = DB::select("SHOW INDEX FROM `cart_items` WHERE Key_name = ?", ['cart_items_cart_id_book_id_unique']);
        if (empty($cartItemUnique)) {
            Schema::table('cart_items', function (Blueprint $table) {
                $table->unique(['cart_id', 'book_id']); // Prevent duplicate items
            });
        }

        // Index untuk Orders
        Schema::table('orders', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('status');
            $table->index(['user_id', 'created_at']); // Untuk user order history
        });

        // Index untuk Order Items
        Schema::table('order_items', function (Blueprint $table) {
            $table->index('order_id');
            $table->index('book_id');
        });

        // Index untuk Carts
        // Create unique index for carts.user_id only if it doesn't exist
        $cartsUnique = DB::select("SHOW INDEX FROM `carts` WHERE Key_name = ?", ['carts_user_id_unique']);
        if (empty($cartsUnique)) {
            Schema::table('carts', function (Blueprint $table) {
                $table->unique('user_id'); // One cart per user
            });
        }

        // Index untuk Wishlist
        Schema::table('wishlists', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('book_id');
        });

        // Create unique index for wishlists only if it doesn't exist already
        $wishlistUnique = DB::select("SHOW INDEX FROM `wishlists` WHERE Key_name = ?", ['wishlists_user_id_book_id_unique']);
        if (empty($wishlistUnique)) {
            Schema::table('wishlists', function (Blueprint $table) {
                $table->unique(['user_id', 'book_id']); // Prevent duplicate wishlist items
            });
        }

        // Index untuk Users
        Schema::table('users', function (Blueprint $table) {
            // Username dan email sudah unique by default
            $table->index('role');
        });
    }

    public function down(): void
    {
        // Drop indexes dari books jika ada
        $booksCategoryIndex = DB::select("SHOW INDEX FROM `books` WHERE Key_name = ?", ['books_category_id_index']);
        if (!empty($booksCategoryIndex)) {
            Schema::table('books', function (Blueprint $table) {
                $table->dropIndex(['category_id']);
            });
        }

        $booksIsActiveIndex = DB::select("SHOW INDEX FROM `books` WHERE Key_name = ?", ['books_is_active_index']);
        if (!empty($booksIsActiveIndex)) {
            Schema::table('books', function (Blueprint $table) {
                $table->dropIndex(['is_active']);
            });
        }

        $booksComposite = DB::select("SHOW INDEX FROM `books` WHERE Key_name = ?", ['books_is_active_created_at_index']);
        if (!empty($booksComposite)) {
            Schema::table('books', function (Blueprint $table) {
                $table->dropIndex(['is_active', 'created_at']);
            });
        }

        $booksName = DB::select("SHOW INDEX FROM `books` WHERE Key_name = ?", ['books_name_index']);
        if (!empty($booksName)) {
            Schema::table('books', function (Blueprint $table) {
                $table->dropIndex(['name']);
            });
        }

        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropIndex(['cart_id']);
            $table->dropIndex(['book_id']);
        });

        // Drop unique if exists
        $cartItemUnique = DB::select("SHOW INDEX FROM `cart_items` WHERE Key_name = ?", ['cart_items_cart_id_book_id_unique']);
        if (!empty($cartItemUnique)) {
            Schema::table('cart_items', function (Blueprint $table) {
                $table->dropUnique(['cart_id', 'book_id']);
            });
        }

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['user_id', 'created_at']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex(['order_id']);
            $table->dropIndex(['book_id']);
        });

        // Drop unique on carts.user_id if exists
        $cartsUnique = DB::select("SHOW INDEX FROM `carts` WHERE Key_name = ?", ['carts_user_id_unique']);
        if (!empty($cartsUnique)) {
            Schema::table('carts', function (Blueprint $table) {
                $table->dropUnique(['user_id']);
            });
        }

        Schema::table('wishlists', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['book_id']);
        });

        // Drop unique on wishlists if exists
        $wishlistUnique = DB::select("SHOW INDEX FROM `wishlists` WHERE Key_name = ?", ['wishlists_user_id_book_id_unique']);
        if (!empty($wishlistUnique)) {
            Schema::table('wishlists', function (Blueprint $table) {
                $table->dropUnique(['user_id', 'book_id']);
            });
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
        });
    }
};