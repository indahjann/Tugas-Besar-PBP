<div class="row row-cols-2 row-cols-md-4 g-4">
    @forelse($products as $book)
        <div class="col">
            <div class="card h-100 shadow-sm border-0">
                <img src="{{ $book->cover_url }}" class="card-img-top" alt="{{ $book->name }}">
                <div class="card-body d-flex flex-column">
                    <h6 class="fw-semibold mb-1" title="{{ $book->name }}">{{ Str::limit($book->name,40) }}</h6>
                    <small class="text-muted mb-2">{{ $book->author }}</small>
                    <p class="mb-2 small text-truncate">{{ $book->category?->name }}</p>
                    <div class="mt-auto d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-primary">Rp {{ number_format($book->price,0,',','.') }}</span>
                        <button class="btn btn-sm btn-outline-primary">Tambah</button>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info mb-0">Tidak ada produk ditemukan.</div>
        </div>
    @endforelse
</div>
@if(method_exists($products,'links'))
    <div class="mt-4">
        {{ $products->withQueryString()->links() }}
    </div>
@endif
