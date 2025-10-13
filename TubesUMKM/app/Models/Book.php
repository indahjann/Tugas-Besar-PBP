<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'name',
        'price',
        'stock',
        'description',
        'author',
        'publisher',
        'year',
        'isbn',
        'category_id',
        'is_active',
        'cover_image',
    ];

    // Relasi ke kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi ke order items
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relasi ke cart items
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    // Accessor: generate a usable cover image URL or fallback
    public function getCoverUrlAttribute(): string
    {
        $cover = $this->cover_image;
        if (!$cover) {
            return asset('images/default-cover.png');
        }
        if (str_starts_with($cover, 'http://') || str_starts_with($cover, 'https://')) {
            return $cover;
        }
        return asset('storage/' . ltrim($cover, '/'));
    }

    // Accessor: format price with currency
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp' . number_format($this->price, 0, ',', '.');
    }

    // Accessor: check if book is available
    public function getIsAvailableAttribute(): bool
    {
        return $this->is_active && $this->stock > 0;
    }

    // Accessor: get short description
    public function getShortDescriptionAttribute(): string
    {
        return $this->description ? \Illuminate\Support\Str::limit($this->description, 150) : 'Deskripsi tidak tersedia.';
    }
}
