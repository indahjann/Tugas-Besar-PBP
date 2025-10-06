<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $books = [
            // Fiction (category_id:1)
            [
                'name' => 'The Little Prince',
                'price' => 150000,
                'stock' => 25,
                'description' => 'Cerita klasik seorang pangeran kecil menjelajah alam.',
                'author' => 'Antoine de Saint-Exupéry',
                'publisher' => 'Reynal & Hitchcock',
                'year' => 1943,
                'isbn' => '9780156012195',
                'category_id' => 1,
                'is_active' => true,
                'cover_image' => null,
            ],
            [
                'name' => 'Laskar Pelangi',
                'price' => 85000,
                'stock' => 30,
                'description' => 'Perjuangan anak-anak Belitung mengejar pendidikan.',
                'author' => 'Andrea Hirata',
                'publisher' => 'Bentang Pustaka',
                'year' => 2005,
                'isbn' => '9789793062794',
                'category_id' => 1,
                'is_active' => true,
                'cover_image' => null,
            ],
            // Non-Fiction (category_id:2)
            [
                'name' => 'Sapiens',
                'price' => 180000,
                'stock' => 20,
                'description' => 'Sejarah singkat umat manusia.',
                'author' => 'Yuval Noah Harari',
                'publisher' => 'Harper',
                'year' => 2014,
                'isbn' => '9780062316097',
                'category_id' => 2,
                'is_active' => true,
                'cover_image' => null,
            ],
            [
                'name' => 'Atomic Habits',
                'price' => 165000,
                'stock' => 35,
                'description' => 'Membentuk kebiasaan baik & hilangkan kebiasaan buruk.',
                'author' => 'James Clear',
                'publisher' => 'Avery',
                'year' => 2018,
                'isbn' => '9780735211292',
                'category_id' => 2,
                'is_active' => true,
                'cover_image' => null,
            ],
            // Comic & Manga (category_id:3)
            [
                'name' => 'One Piece Vol. 1',
                'price' => 45000,
                'stock' => 50,
                'description' => 'Awal petualangan Luffy mencari One Piece.',
                'author' => 'Eiichiro Oda',
                'publisher' => 'Shueisha',
                'year' => 1997,
                'isbn' => '9784088725095',
                'category_id' => 3,
                'is_active' => true,
                'cover_image' => null,
            ],
            [
                'name' => 'Naruto Vol. 1',
                'price' => 42000,
                'stock' => 40,
                'description' => 'Perjalanan ninja muda Naruto.',
                'author' => 'Masashi Kishimoto',
                'publisher' => 'Shueisha',
                'year' => 1999,
                'isbn' => '9784088730226',
                'category_id' => 3,
                'is_active' => true,
                'cover_image' => null,
            ],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}
