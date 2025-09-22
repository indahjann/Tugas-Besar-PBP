@extends('layouts.app')

@section('title', 'Semua Buku')

@section('content')
    <div class="container my-5">
        <h2 class="text-center">Daftar Buku</h2>
        <div class="row">
            @foreach ($books as $book)
                <div class="col-md-3">
                    <div class="book-card text-center p-3 border rounded shadow-sm">
                        <img src="{{ $book['image'] }}" alt="{{ $book['title'] }}" class="img-fluid mb-3 book-image" style="height: 200px; object-fit: cover;">
                        <h5>{{ $book['title'] }}</h5>
                        <p class="text-muted">by {{ $book['author'] }}</p>
                        <p>Rp {{ number_format($book['price'], 0, ',', '.') }}</p>
                        <a href="#" class="btn btn-primary btn-sm">Buy Now</a>
                        <a href="#" class="btn btn-outline-secondary btn-sm">Add to Cart</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection