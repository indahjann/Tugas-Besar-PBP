<section class="category-section">
    <div class="container">
        <h3 class="text-center">Bestseller Fiction</h3>
        @if(!empty($categories['fiction']) && $categories['fiction']->count() > 0)
            <div class="books-grid-modern" id="welcome-fiction">
                @foreach($categories['fiction'] as $book)
                    <x-book-card :book="$book" :user-wishlist="$userWishlist ?? []" />
                @endforeach
            </div>
        @else
            <p class="text-center">No fiction books to display.</p>
        @endif

        <h3 class="text-center mt-5">Comic and Manga</h3>
        @if(!empty($categories['manga']) && $categories['manga']->count() > 0)
            <div class="books-grid-modern" id="welcome-manga">
                @foreach($categories['manga'] as $book)
                    <x-book-card :book="$book" :user-wishlist="$userWishlist ?? []" />
                @endforeach
            </div>
        @else
            <p class="text-center">No manga books to display.</p>
        @endif

        <h3 class="text-center mt-5">Teen Reading</h3>
        @if(!empty($categories['teen']) && $categories['teen']->count() > 0)
            <div class="books-grid-modern" id="welcome-teen">
                @foreach($categories['teen'] as $book)
                    <x-book-card :book="$book" :user-wishlist="$userWishlist ?? []" />
                @endforeach
            </div>
        @else
            <p class="text-center">No teen books to display.</p>
        @endif


    </div>
</section>
