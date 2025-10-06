<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Fiction'],
            ['name' => 'Non-Fiction'],
            ['name' => 'Comic & Manga'],
            ['name' => 'Teen Reading'],
            ['name' => 'Self-Help'],
            ['name' => 'Technology'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}