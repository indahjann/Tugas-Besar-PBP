<div class="categories-sidebar">
    <!-- All Categories Link -->
    <div class="sidebar-section">
        <h5 class="sidebar-title">
            <i class="fas fa-th-large"></i> Browse Categories
        </h5>
        <div class="categories-list">
            <a href="/categories" class="category-item {{ !$selectedCategory ? 'active' : '' }}">
                <i class="fas fa-th-large"></i>
                <span>All Categories</span>
                <span class="category-count">{{ $books->total() }}</span>
            </a>
            
            @foreach($categories as $category)
                <a href="/categories?category={{ $category->id }}" 
                   class="category-item {{ $selectedCategory && $selectedCategory->id == $category->id ? 'active' : '' }}">
                    <i class="fas fa-bookmark"></i>
                    <span>{{ $category->name }}</span>
                    <span class="category-count">{{ $category->books_count }}</span>
                </a>
            @endforeach
        </div>
    </div>

</div>