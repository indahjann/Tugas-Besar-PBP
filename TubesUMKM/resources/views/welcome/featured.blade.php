<section class="featured-section">
    <div class="container">
        <div class="carousel-container-bg">
            <button class="nav-btn prev" id="prevBtn"><i class="fas fa-chevron-left"></i></button>
            <button class="nav-btn next" id="nextBtn"><i class="fas fa-chevron-right"></i></button>
            <h2 class="section-title text-center mb-4">Buku Tersedia</h2>
            <div class="carousel-viewport">
                <div class="custom-carousel" id="bookCarousel">
                    <div class="carousel-track" id="carouselTrack">
                        {{-- Loop books using new card component --}}
                        @foreach(($books ?? collect()) as $book)
                            <div class="carousel-item-custom">
                                <x-book-card :book="$book" :user-wishlist="$userWishlist ?? []" />
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="text-center mt-4">
                    <button class="btn view-more-btn">VIEW MORE</button>
                </div>
            </div>
        </div>
    </div>
</section>
