@extends('layouts.app')

@push('head')
    {{-- wishlist styles are compiled via Vite (app.css) --}}
@endpush

@section('content')
<div class="categories-page">
    <div class="container">
        <!-- Header Section -->
        <div class="simple-header">
            <!-- Breadcrumb -->
            <div class="breadcrumb-simple">
                <a href="/" class="breadcrumb-item">Home</a>
                <span class="breadcrumb-separator">›</span>
                <span class="breadcrumb-current">Wishlist</span>
            </div>
            
            <!-- Page Title -->
            <h1 class="simple-title">My Wishlist</h1>
        </div>

        <!-- Wishlist Content -->
        <div class="row">
            <div class="col-12">
                <div class="books-content">
                    @if($wishlistItems->count() > 0)
                        <!-- Results Info -->
                        <div class="results-info">
                            <div class="results-count">
                                {{ $wishlistItems->count() }} book{{ $wishlistItems->count() > 1 ? 's' : '' }} in your wishlist
                            </div>
                        </div>

                        <!-- Wishlist Grid -->
                        <div class="books-grid-modern" id="wishlistContainer">
                            @foreach($wishlistItems as $wishlistItem)
                                <x-book-card :book="$wishlistItem->book" :user-wishlist="[$wishlistItem->book_id]" />
                            @endforeach
                        </div>
                    @else
                        <!-- Empty Wishlist -->
                        <div class="no-books-found">
                            <div class="no-books-icon">
                                <i class="fas fa-heart"></i>
                            </div>
                            <h4>Your Wishlist is Empty</h4>
                            <p>Discover amazing books and add them to your wishlist!</p>
                            <a href="/" class="btn btn-primary">Browse Books</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    {{-- Wishlist logic is bundled from resources/js/components/book-card.js via Vite --}}
@endpush
