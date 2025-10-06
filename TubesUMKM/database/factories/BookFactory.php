<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(3), // Judul buku
            'price' => fake()->randomFloat(2, 50000, 500000), // harga antara 50rb - 500rb
            'stock' => fake()->numberBetween(0, 100),
            'description' => fake()->paragraph(),
            'author' => fake()->name(),
            'publisher' => fake()->company(),
            'year' => fake()->year(),
            'isbn' => fake()->unique()->isbn13(),
            'cover_image' => fake()->imageUrl(400, 600, 'books', true, 'Book Cover'),
            'category_id' => Category::factory(), // relasi ke kategori
            'is_active' => fake()->boolean(90), // 90% aktif
        ];
    }
}
