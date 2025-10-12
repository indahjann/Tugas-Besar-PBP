<!-- Wishlist Page -->
<div class="wishlist-page">
    <div class="container">
        <div class="wishlist-header">
            <h1 class="wishlist-title">My Wishlist</h1>
            <p class="wishlist-subtitle">Books you've saved for later</p>
        </div>
        
        @if($wishlistItems->isEmpty())
            <!-- Empty Wishlist -->
            <div class="empty-wishlist">
                <div class="empty-wishlist-icon">
                    <i class="far fa-heart"></i>
                </div>
                <h3>Your wishlist is empty</h3>
                <p>Start adding books you love to your wishlist</p>
                <a href="{{ route('categories.index') }}" class="btn btn-primary">Browse Books</a>
            </div>
        @else
            <!-- Wishlist Items -->
            <div class="wishlist-items">
                @foreach($wishlistItems as $wishlistItem)
                    @php $book = $wishlistItem->book; @endphp
                    <div class="wishlist-item" data-book-id="{{ $book->id }}">
                        <div class="wishlist-item-image">
                            <img src="{{ $book->cover_url }}" alt="{{ $book->name }}">
                        </div>
                        
                        <div class="wishlist-item-details">
                            <h4 class="wishlist-item-title">{{ $book->name }}</h4>
                            <p class="wishlist-item-author">{{ $book->author }}</p>
                            
                            @if($book->category)
                                <span class="wishlist-item-category">{{ $book->category->name }}</span>
                            @endif
                            
                            <div class="wishlist-item-rating">
                                <div class="stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= 4 ? 'filled' : '' }}"></i>
                                    @endfor
                                </div>
                                <span class="rating-text">(24 reviews)</span>
                            </div>
                            
                            <div class="wishlist-item-price">
                                <span class="current-price">Rp {{ number_format($book->price, 0, ',', '.') }}</span>
                                @if(rand(0,1))
                                    <span class="original-price">Rp {{ number_format($book->price * 1.2, 0, ',', '.') }}</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="wishlist-item-actions">
                            <button class="btn-remove-wishlist" data-book-id="{{ $book->id }}" title="Remove from wishlist">
                                <i class="fas fa-times"></i>
                            </button>
                            
                            <button class="btn-add-to-cart" data-book-id="{{ $book->id }}" title="Add to Cart">
                                <i class="fas fa-shopping-cart"></i>
                                Add to Cart
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Continue Shopping -->
            <div class="continue-shopping">
                <a href="{{ route('categories.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left"></i>
                    Continue Shopping
                </a>
            </div>
        @endif
    </div>
</div>