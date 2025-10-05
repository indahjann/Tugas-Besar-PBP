<section class="featured-section">
    <div class="container">
        <div class="carousel-container-bg">
            <button class="nav-btn prev" id="prevBtn"><i class="fas fa-chevron-left"></i></button>
            <button class="nav-btn next" id="nextBtn"><i class="fas fa-chevron-right"></i></button>
            <h2 class="section-title text-center mb-4">Buku Tersedia</h2>
            <div class="carousel-viewport">
                <div class="custom-carousel" id="bookCarousel">
                    <div class="carousel-track" id="carouselTrack">
                        {{-- Loop books here (static for now) --}}
                        @foreach($books ?? [] as $book)
                            <div class="carousel-item-custom">
                                <div class="book-card book-card-modern">
                                    <div class="book-image-container">
                                        <img src="{{ $book['cover'] ?? '#' }}" class="book-image" alt="{{ $book['title'] ?? 'Book' }}">
                                        <div class="hover-overlay">
                                            <div class="hover-actions">
                                                <button class="btn-buy-now">Buy Now</button>
                                                <div class="action-buttons">
                                                    <button class="btn-cart"><i class="fas fa-shopping-cart"></i></button>
                                                    <button class="btn-favorite"><i class="far fa-heart"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="book-info">
                                        <h6 class="book-title">{{ $book['title'] ?? 'Untitled' }}</h6>
                                        <p class="book-author">{{ $book['author'] ?? '' }}</p>
                                        <p class="book-format">{{ $book['format'] ?? '' }}</p>
                                        <div class="price-section">
                                            <p class="current-price">{{ $book['price'] ?? '' }}</p>
                                        </div>
                                    </div>
                                </div>
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
<!-- duplicate removed -->