<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Check if index exists (compatible with MySQL and SQLite)
     */
    private function indexExists(string $table, string $indexName): bool
    {
        $connection = Schema::getConnection();
        $driver = $connection->getDriverName();

        if ($driver === 'sqlite') {
            // SQLite: check via pragma
            $indexes = $connection->select("PRAGMA index_list('{$table}')");
            foreach ($indexes as $index) {
                if ($index->name === $indexName) {
                    return true;
                }
            }
            return false;
        } else {
            // MySQL/MariaDB: use SHOW INDEX
            $indexes = $connection->select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]);
            return !empty($indexes);
        }
    }

    public function up(): void
    {
        // Index untuk Books (buat hanya jika belum ada)
        if (!$this->indexExists('books', 'books_category_id_index')) {
            Schema::table('books', function (Blueprint $table) {
                $table->index('category_id');
            });
        }

        if (!$this->indexExists('books', 'books_is_active_index')) {
            Schema::table('books', function (Blueprint $table) {
                $table->index('is_active');
            });
        }

        if (!$this->indexExists('books', 'books_is_active_created_at_index')) {
            Schema::table('books', function (Blueprint $table) {
                $table->index(['is_active', 'created_at']); // Composite index untuk listing
            });
        }

        if (!$this->indexExists('books', 'books_name_index')) {
            Schema::table('books', function (Blueprint $table) {
                $table->index('name'); // Untuk search
            });
        }

        // Index untuk Cart Items
        if (!$this->indexExists('cart_items', 'cart_items_cart_id_index')) {
            Schema::table('cart_items', function (Blueprint $table) {
                $table->index('cart_id');
            });
        }

        if (!$this->indexExists('cart_items', 'cart_items_book_id_index')) {
            Schema::table('cart_items', function (Blueprint $table) {
                $table->index('book_id');
            });
        }

        // Create unique index for cart_items only if it doesn't exist already
        if (!$this->indexExists('cart_items', 'cart_items_cart_id_book_id_unique')) {
            Schema::table('cart_items', function (Blueprint $table) {
                $table->unique(['cart_id', 'book_id']); // Prevent duplicate items
            });
        }

        // Index untuk Orders
        if (!$this->indexExists('orders', 'orders_user_id_index')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->index('user_id');
            });
        }

        if (!$this->indexExists('orders', 'orders_status_index')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->index('status');
            });
        }

        if (!$this->indexExists('orders', 'orders_user_id_created_at_index')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->index(['user_id', 'created_at']); // Untuk user order history
            });
        }

        // Index untuk Order Items
        if (!$this->indexExists('order_items', 'order_items_order_id_index')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->index('order_id');
            });
        }

        if (!$this->indexExists('order_items', 'order_items_book_id_index')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->index('book_id');
            });
        }

        // Index untuk Carts
        // Create unique index for carts.user_id only if it doesn't exist
        if (!$this->indexExists('carts', 'carts_user_id_unique')) {
            Schema::table('carts', function (Blueprint $table) {
                $table->unique('user_id'); // One cart per user
            });
        }

        // Index untuk Wishlist
        if (!$this->indexExists('wishlists', 'wishlists_user_id_index')) {
            Schema::table('wishlists', function (Blueprint $table) {
                $table->index('user_id');
            });
        }

        if (!$this->indexExists('wishlists', 'wishlists_book_id_index')) {
            Schema::table('wishlists', function (Blueprint $table) {
                $table->index('book_id');
            });
        }

        // Create unique index for wishlists only if it doesn't exist already
        if (!$this->indexExists('wishlists', 'wishlists_user_id_book_id_unique')) {
            Schema::table('wishlists', function (Blueprint $table) {
                $table->unique(['user_id', 'book_id']); // Prevent duplicate wishlist items
            });
        }

        // Index untuk Users
        if (!$this->indexExists('users', 'users_role_index')) {
            Schema::table('users', function (Blueprint $table) {
                // Username dan email sudah unique by default
                $table->index('role');
            });
        }
    }

    public function down(): void
    {
        // Drop indexes dari books jika ada
        if ($this->indexExists('books', 'books_category_id_index')) {
            Schema::table('books', function (Blueprint $table) {
                $table->dropIndex(['category_id']);
            });
        }

        if ($this->indexExists('books', 'books_is_active_index')) {
            Schema::table('books', function (Blueprint $table) {
                $table->dropIndex(['is_active']);
            });
        }

        if ($this->indexExists('books', 'books_is_active_created_at_index')) {
            Schema::table('books', function (Blueprint $table) {
                $table->dropIndex(['is_active', 'created_at']);
            });
        }

        if ($this->indexExists('books', 'books_name_index')) {
            Schema::table('books', function (Blueprint $table) {
                $table->dropIndex(['name']);
            });
        }

        if ($this->indexExists('cart_items', 'cart_items_cart_id_index')) {
            Schema::table('cart_items', function (Blueprint $table) {
                $table->dropIndex(['cart_id']);
            });
        }

        if ($this->indexExists('cart_items', 'cart_items_book_id_index')) {
            Schema::table('cart_items', function (Blueprint $table) {
                $table->dropIndex(['book_id']);
            });
        }

        // Drop unique if exists
        if ($this->indexExists('cart_items', 'cart_items_cart_id_book_id_unique')) {
            Schema::table('cart_items', function (Blueprint $table) {
                $table->dropUnique(['cart_id', 'book_id']);
            });
        }

        if ($this->indexExists('orders', 'orders_user_id_index')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropIndex(['user_id']);
            });
        }

        if ($this->indexExists('orders', 'orders_status_index')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropIndex(['status']);
            });
        }

        if ($this->indexExists('orders', 'orders_user_id_created_at_index')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropIndex(['user_id', 'created_at']);
            });
        }

        if ($this->indexExists('order_items', 'order_items_order_id_index')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->dropIndex(['order_id']);
            });
        }

        if ($this->indexExists('order_items', 'order_items_book_id_index')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->dropIndex(['book_id']);
            });
        }

        // Drop unique on carts.user_id if exists
        if ($this->indexExists('carts', 'carts_user_id_unique')) {
            Schema::table('carts', function (Blueprint $table) {
                $table->dropUnique(['user_id']);
            });
        }

        if ($this->indexExists('wishlists', 'wishlists_user_id_index')) {
            Schema::table('wishlists', function (Blueprint $table) {
                $table->dropIndex(['user_id']);
            });
        }

        if ($this->indexExists('wishlists', 'wishlists_book_id_index')) {
            Schema::table('wishlists', function (Blueprint $table) {
                $table->dropIndex(['book_id']);
            });
        }

        // Drop unique on wishlists if exists
        if ($this->indexExists('wishlists', 'wishlists_user_id_book_id_unique')) {
            Schema::table('wishlists', function (Blueprint $table) {
                $table->dropUnique(['user_id', 'book_id']);
            });
        }

        if ($this->indexExists('users', 'users_role_index')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropIndex(['role']);
            });
        }
    }
};