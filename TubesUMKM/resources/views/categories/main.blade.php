<div class="categories-page">
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="page-header">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h1 class="page-title">
                            @if($selectedCategory)
                                {{ $selectedCategory->name }}
                            @else
                                All Categories
                            @endif
                        </h1>
                        <p class="page-subtitle">
                            @if($selectedCategory)
                                Discover books in {{ $selectedCategory->name }} category
                            @else
                                Browse books by categories
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <div class="breadcrumb-custom">
                            <a href="/" class="breadcrumb-item">Home</a>
                            <span class="breadcrumb-separator">/</span>
                            <a href="/categories" class="breadcrumb-item">Categories</a>
                            @if($selectedCategory)
                                <span class="breadcrumb-separator">/</span>
                                <span class="breadcrumb-current">{{ $selectedCategory->name }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-4">
                    @includeWhen(true, 'Categories.sidebar')
                </div>

                <!-- Main Content -->
                <div class="col-lg-9 col-md-8">
                    <div class="books-content">
                        <!-- Results Info -->
                        <div class="results-info">
                            <div class="results-count">
                                Showing {{ $books->count() }} of {{ $books->total() }} books
                                @if($selectedCategory)
                                    in "{{ $selectedCategory->name }}"
                                @endif
                            </div>
                            
                            <div class="view-options">
                                <button class="view-btn active" data-view="grid">
                                    <i class="fas fa-th"></i>
                                </button>
                                <button class="view-btn" data-view="list">
                                    <i class="fas fa-list"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Books Grid -->
                        @if($books->count() > 0)
                            <div class="books-grid" id="booksContainer">
                                @foreach($books as $book)
                                    <div class="book-card">
                                        <div class="book-image-container">
                                            <img src="{{ $book->cover_url }}" alt="{{ $book->name }}" class="book-image">
                                            <div class="book-overlay">
                                                <div class="book-actions">
                                                    <button class="btn-action btn-cart" title="Add to Cart">
                                                        <i class="fas fa-shopping-cart"></i>
                                                    </button>
                                                    <button class="btn-action btn-wishlist" title="Add to Wishlist">
                                                        <i class="far fa-heart"></i>
                                                    </button>
                                                    <button class="btn-action btn-preview" title="Quick View">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            @if($book->category)
                                                <span class="book-category-badge">{{ $book->category->name }}</span>
                                            @endif
                                        </div>
                                        
                                        <div class="book-info">
                                            <h6 class="book-title">{{ $book->name }}</h6>
                                            <p class="book-author">{{ $book->author }}</p>
                                            <div class="book-rating">
                                                <div class="stars">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star {{ $i <= 4 ? 'filled' : '' }}"></i>
                                                    @endfor
                                                </div>
                                                <span class="rating-count">(24)</span>
                                            </div>
                                            <div class="book-price">
                                                <span class="current-price">Rp {{ number_format($book->price, 0, ',', '.') }}</span>
                                                @if(rand(0,1))
                                                    <span class="original-price">Rp {{ number_format($book->price * 1.2, 0, ',', '.') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Pagination -->
                            <div class="pagination-container">
                                {{ $books->appends(request()->query())->links('pagination.custom') }}
                            </div>
                        @else
                            <div class="no-books-found">
                                <div class="no-books-icon">
                                    <i class="fas fa-search"></i>
                                </div>
                                <h4>No Books Found</h4>
                                <p>
                                    @if($selectedCategory)
                                        No books found in "{{ $selectedCategory->name }}" category.
                                    @else
                                        No books found. Try browsing different categories.
                                    @endif
                                </p>
                                <a href="/categories" class="btn btn-primary">Browse All Categories</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>