<div class="categories-page">
    <div class="container-fluid">
        <!-- Simple Header Section -->
        <div class="simple-header">
            <div class="container">
                <!-- Breadcrumb -->
                <div class="breadcrumb-simple">
                    <a href="/" class="breadcrumb-item">Home</a>
                    <span class="breadcrumb-separator">›</span>
                    <a href="/categories" class="breadcrumb-item">Buku</a>
                    @if($selectedCategory)
                        <span class="breadcrumb-separator">›</span>
                        <span class="breadcrumb-current">{{ $selectedCategory->name }}</span>
                    @endif
                </div>
                
                <!-- Page Title -->
                <h1 class="simple-title">
                    @if($selectedCategory)
                        {{ $selectedCategory->name }}
                    @else
                        All Categories
                    @endif
                </h1>
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
                        <!-- Results Info with Sort By -->
                        <div class="results-info">
                            <div class="results-count">
                                Showing {{ $books->count() }} of {{ $books->total() }} books
                                @if($selectedCategory)
                                    in "{{ $selectedCategory->name }}"
                                @endif
                            </div>
                            
                            <div class="sort-dropdown">
                                <select class="sort-select" id="sortSelect">
                                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
                                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name Z-A</option>
                                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                                </select>
                                <i class="fas fa-chevron-down sort-icon"></i>
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