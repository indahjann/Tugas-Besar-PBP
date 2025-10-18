<div class="categories-sidebar">
    <div class="sidebar-section">
        <h5 class="sidebar-title">
            <i class="fas fa-th-large"></i> Browse Categories
        </h5>
        <div class="categories-list">
            <a href="/categories" class="category-item {{ !$selectedCategory ? 'active' : '' }}">
                <span>
                    <i class="fas fa-th-large"></i>
                    All Categories
                </span>
                <span class="category-count">{{ $categories->sum('books_count') }}</span>
            </a>
            
            @foreach($categories as $category)
                <a href="/categories?category={{ $category->id }}" 
                   class="category-item {{ $selectedCategory && $selectedCategory->id == $category->id ? 'active' : '' }}">
                    <span>
                        <i class="fas fa-bookmark"></i>
                        {{ $category->name }}
                    </span>
                    <span class="category-count">{{ $category->books_count }}</span>
                </a>
            @endforeach
        </div>
    </div>
</div>