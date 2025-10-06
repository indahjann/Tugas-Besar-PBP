<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->value('id') ?? User::factory(),
            'total' => 0, // akan dihitung ulang setelah item ditambahkan
            'status' => $this->faker->randomElement(['pending','diproses','dikirim','selesai']),
            'address_text' => $this->faker->address(),
        ];
    }
}
