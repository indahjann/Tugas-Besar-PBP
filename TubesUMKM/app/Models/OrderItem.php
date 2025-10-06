<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'book_id',
        'price', // harga satuan saat transaksi
        'qty', // jumlah buku dalam pesanan
        'subtotal', // price * qty
    ];

    // Relasi ke pesanan
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relasi ke buku
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}