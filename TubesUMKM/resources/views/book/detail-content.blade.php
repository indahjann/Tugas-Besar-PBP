<div class="book-detail-page bg-white min-h-screen">
    <!-- Breadcrumb -->
    <nav class="breadcrumb-nav">
        <div class="container mx-auto px-4 py-4">
            <ol class="breadcrumb-list">
                <li class="breadcrumb-item">
                    <a href="{{ route('books.index') }}" class="hover:text-blue-600 transition-colors">Home</a>
                </li>
                <li class="breadcrumb-separator">&gt;</li>
                <li class="breadcrumb-item">
                    <a href="{{ route('categories.show', $book->category->id) }}" 
                       class="hover:text-blue-600 transition-colors">
                        {{ $book->category->name }}
                    </a>
                </li>
                <li class="breadcrumb-separator">&gt;</li>
                <li class="breadcrumb-current">{{ $book->name }}</li>
            </ol>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="book-detail-container">
        <div class="container mx-auto px-4 py-8">
            <div class="book-detail-grid">
                
                <!-- Left Section: Book Image & Format -->
                <div class="book-image-section">
                    <div class="book-image-sticky">
                        <!-- Main Book Cover -->
                        <div class="book-cover-container">
                            <img src="{{ $book->cover_url }}" 
                                 alt="{{ $book->name }}" 
                                 class="book-cover-image">
                        </div>
                    </div>
                </div>

                <!-- Right Section: Book Details -->
                <div class="book-info-section">
                    <!-- Book Header -->
                    <div class="book-header">
                        @if($book->author)
                        <p class="book-author">{{ $book->author }}</p>
                        @endif
                        <h1 class="book-title">{{ $book->name }}</h1>
                        <div class="book-price">
                            Rp{{ number_format($book->price, 0, ',', '.') }}
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="book-actions">
                            <button 
                                class="wishlist-btn {{ $isWishlisted ? 'wishlist-active' : '' }}"
                                data-book-id="{{ $book->id }}"
                                @if(!auth()->check()) onclick="window.location.href='{{ route('login') }}'" @endif>
                                <i class="fa-heart {{ $isWishlisted ? 'fas' : 'far' }}"></i>
                                <span>Favorit</span>
                            </button>
                        </div>
                        
                        <!-- Add to Cart Button -->
                        @if($book->stock > 0)
                            <button 
                                class="add-to-cart-btn"
                                data-book-id="{{ $book->id }}"
                                @if(!auth()->check()) onclick="window.location.href='{{ route('login') }}'" @endif>
                                <i class="fas fa-plus"></i>
                                <span>Keranjang</span>
                            </button>
                        @else
                            <button class="add-to-cart-btn add-to-cart-disabled" disabled>
                                <i class="fas fa-times-circle"></i>
                                <span>Stok Habis</span>
                            </button>
                        @endif
                    </div>

                    <!-- Description Section -->
                    <div class="book-description-section">
                        <h2 class="section-title">Deskripsi</h2>
                        
                        <div class="description-content">
                            <h3 class="description-subtitle">Sinopsis Buku</h3>
                            @if($book->description)
                                <div class="book-description" data-full-text="{{ $book->description }}">
                                    <p class="description-preview">
                                        {{ Str::limit($book->description, 300) }}
                                    </p>
                                    @if(strlen($book->description) > 300)
                                        <button class="read-more-btn">
                                            Baca Selengkapnya <i class="fas fa-chevron-down"></i>
                                        </button>
                                    @endif
                                </div>
                            @else
                                <p class="no-description">Deskripsi tidak tersedia.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Book Details Table -->
                    <div class="book-details-section">
                        <h2 class="section-title">Detail Buku</h2>
                        
                        <div class="details-grid">
                            <div class="details-column">
                                @if($book->publisher)
                                <div class="detail-item">
                                    <dt class="detail-label">Penerbit</dt>
                                    <dd class="detail-value">{{ $book->publisher }}</dd>
                                </div>
                                @endif
                                
                                @if($book->isbn)
                                <div class="detail-item">
                                    <dt class="detail-label">ISBN</dt>
                                    <dd class="detail-value">{{ $book->isbn }}</dd>
                                </div>
                                @endif
                                
                                <div class="detail-item">
                                    <dt class="detail-label">Bahasa</dt>
                                    <dd class="detail-value">Indonesia</dd>
                                </div>

                                <div class="detail-item">
                                    <dt class="detail-label">Lebar</dt>
                                    <dd class="detail-value">15.2 cm</dd>
                                </div>
                            </div>
                            
                            <div class="details-column">
                                @if($book->year)
                                <div class="detail-item">
                                    <dt class="detail-label">Tanggal Terbit</dt>
                                    <dd class="detail-value">{{ $book->year }}</dd>
                                </div>
                                @endif
                                
                                <div class="detail-item">
                                    <dt class="detail-label">Halaman</dt>
                                    <dd class="detail-value">320</dd>
                                </div>
                                
                                <div class="detail-item">
                                    <dt class="detail-label">Panjang</dt>
                                    <dd class="detail-value">-</dd>
                                </div>

                                <div class="detail-item">
                                    <dt class="detail-label">Berat</dt>
                                    <dd class="detail-value">0.39 kg</dd>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Related Books Section -->
                    @if($relatedBooks->isNotEmpty())
                    <div class="related-books-section">
                        <h2 class="section-title">Buku Terkait</h2>
                        
                        <div class="books-grid-modern" id="relatedBooksGrid">
                            @foreach($relatedBooks as $relatedBook)
                                <x-book-card :book="$relatedBook" :user-wishlist="$userWishlist ?? []" />
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>