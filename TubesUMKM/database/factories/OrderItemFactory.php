<?php

namespace Database\Factories;

use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        $price = $this->faker->randomElement([45000,55000,65000,75000,125000,150000,180000]);
        $qty = $this->faker->numberBetween(1,4);
        return [
            'order_id' => Order::inRandomOrder()->value('id') ?? Order::factory(),
            'book_id' => Book::inRandomOrder()->value('id') ?? Book::factory(),
            'price' => $price,
            'qty' => $qty,
            'subtotal' => $price * $qty,
        ];
    }
}
