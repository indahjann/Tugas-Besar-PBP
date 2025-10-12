<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    // Relasi ke item keranjang
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
    
    // Alias untuk items 
    public function items()
    {
        return $this->cartItems();
    }

    // Relasi ke pengguna
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}