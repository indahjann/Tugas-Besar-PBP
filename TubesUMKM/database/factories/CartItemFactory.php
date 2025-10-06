<?php

namespace Database\Factories;

use App\Models\CartItem;
use App\Models\Cart;
use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartItemFactory extends Factory
{
    protected $model = CartItem::class;

    public function definition(): array
    {
        return [
            'cart_id' => Cart::inRandomOrder()->value('id') ?? Cart::factory(),
            'book_id' => Book::inRandomOrder()->value('id') ?? Book::factory(),
            'qty' => $this->faker->numberBetween(1, 3),
        ];
    }
}
