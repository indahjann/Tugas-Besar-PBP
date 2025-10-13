@props(['book', 'userWishlist' => []])

<div class="book-card-modern">
    <div class="book-image-container">
        <a href="{{ route('books.show', $book) }}" class="book-link">
            <img src="{{ $book->cover_url }}" alt="{{ $book->name }}" class="book-image">
        </a>
        
        <!-- Favorites button (Modern style) - positioned in top right -->
        <button class="btn-favorites {{ in_array($book->id, $userWishlist) ? 'active' : '' }}" 
                data-book-id="{{ $book->id }}" 
                title="{{ in_array($book->id, $userWishlist) ? 'Remove from favorites' : 'Add to favorites' }}">
            <i class="{{ in_array($book->id, $userWishlist) ? 'fas' : 'far' }} fa-heart"></i>
        </button>
    </div>
    
    <div class="book-info">
        <a href="{{ route('books.show', $book) }}" class="book-title-link">
            <h6 class="book-title">{{ $book->name }}</h6>
        </a>
        <p class="book-author">{{ $book->author }}</p>
        <div class="book-rating">
            <div class="stars">
                @for($i = 1; $i <= 5; $i++)
                    <i class="fas fa-star {{ $i <= 4 ? 'filled' : '' }}"></i>
                @endfor
            </div>
            <span class="rating-count">({{ rand(15, 50) }})</span>
        </div>
        <div class="book-price">
            <span class="current-price">Rp {{ number_format($book->price, 0, ',', '.') }}</span>
            @if(rand(0,1))
                <span class="original-price">Rp {{ number_format($book->price * 1.2, 0, ',', '.') }}</span>
            @endif
        </div>
    </div>
</div>