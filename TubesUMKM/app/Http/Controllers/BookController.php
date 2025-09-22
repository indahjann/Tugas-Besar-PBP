<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $books = [
    [
        'title' => 'The Little Prince',
        'author' => 'Antoine de Saint-Exupéry',
        'price' => 150000,
        'image' => 'https://phoenixgramedia.id/wp-content/uploads/2025/07/WhatsApp-Image-2025-07-04-at-1.39.12-PM.jpeg'
    ],
    [
        'title' => 'Blue Lock 21',
        'author' => 'Muneyuki Kaneshiro',
        'price' => 75000,
        'image' => 'https://www.google.com/url?sa=i&url=https%3A%2F%2Fphoenixgramedia.id%2Fbooks%2Fblue-lock-vol-1%2F&psig=AOvVaw3OdB1bazPMazy0taWa31-H&ust=1758627924437000&source=images&cd=vfe&opi=89978449&ved=0CBIQjRxqFwoTCKDVnNel7I8DFQAAAAAdAAAAABAE'
    ],
    [
        'title' => 'Thorn Season',
        'author' => 'Iris Mwangi',
        'price' => 200000,
        'image' => 'https://example.com/thorn-season.jpg'
    ],
    ];

        return view('books.index', compact('books'));
    }
}
