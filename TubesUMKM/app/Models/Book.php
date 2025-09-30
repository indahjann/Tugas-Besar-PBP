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
        'image',
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
}
