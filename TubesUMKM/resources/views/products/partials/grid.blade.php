{{-- Using modern book card grid --}}
<div class="books-grid-modern" id="productsGrid">
    @forelse($products as $book)
        <x-book-card :book="$book" :user-wishlist="$userWishlist ?? []" />
    @empty
        <div class="no-books-found" style="grid-column: 1 / -1; text-align: center; padding: 2rem;">
            <div class="no-books-icon">
                <i class="fas fa-search"></i>
            </div>
            <h4>Tidak ada produk ditemukan</h4>
            <p>Coba dengan kata kunci atau filter yang berbeda.</p>
        </div>
    @endforelse
</div>
@if(method_exists($products,'links'))
    <div class="mt-4">
        {{ $products->withQueryString()->links() }}
    </div>
@endif
