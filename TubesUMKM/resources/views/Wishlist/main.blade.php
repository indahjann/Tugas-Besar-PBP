<div class="categories-page" data-page="wishlist">
    <div class="container">
        <!-- Simple Header -->
        <div class="simple-header">
            <div class="breadcrumb-simple">
                <a href="/">Home</a>
                <span>›</span>
                <span>Wishlist</span>
            </div>
            <h1 class="simple-title">My Wishlist</h1>
        </div>

        <!-- Books Content -->
        <div class="books-content">
            @if(isset($wishlistItems) && $wishlistItems->count() > 0)
                <!-- Results Info -->
                <div class="results-info">
                    <div class="results-count">
                        Showing <strong>{{ $wishlistItems->count() }}</strong> of <strong>{{ $wishlistItems->count() }}</strong> books
                    </div>
                </div>

                <!-- Books Grid -->
                <div class="books-grid-modern" id="wishlistContainer">
                    @foreach($wishlistItems as $wishlistItem)
                        @if(isset($wishlistItem->book))
                            <x-book-card 
                                :book="$wishlistItem->book" 
                                :userWishlist="$wishlistItems->pluck('book_id')->toArray()" 
                            />
                        @endif
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="empty-state">
                    <i class="far fa-heart"></i>
                    <h3>Wishlist Kamu Kosong</h3>
                    <p>Mulai tambahkan buku yang kamu suka!</p>
                    <a href="{{ url('/categories') }}" class="btn-primary">
                         Jelajahi Buku!
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>