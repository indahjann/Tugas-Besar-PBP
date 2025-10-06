<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'book_id',
        'qty', // jumlah item dalam keranjang
    ];

    // Relasi ke keranjang
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    // Relasi ke buku
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}