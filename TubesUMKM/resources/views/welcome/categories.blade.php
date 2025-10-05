<section class="category-section">
    <div class="container">
        <h3 class="text-center">Bestseller Fiction</h3>
        <div class="row">
            @foreach($categories['fiction'] ?? [] as $item)
                <div class="col-md-3">
                    <div class="book-card text-center">
                        <img src="{{ $item['cover'] ?? '#' }}" alt="{{ $item['title'] ?? '' }}" class="book-image">
                        <h5>{{ $item['title'] ?? '' }}</h5>
                        <p>{{ $item['price'] ?? '' }}</p>
                        <a href="#" class="btn btn-outline-primary btn-sm">Lihat Detail</a>
                    </div>
                </div>
            @endforeach
        </div>

        <h3 class="text-center mt-5">Comic and Manga</h3>
        <div class="row">
            @foreach($categories['manga'] ?? [] as $item)
                <div class="col-md-3">
                    <div class="book-card text-center">
                        <img src="{{ $item['cover'] ?? '#' }}" alt="{{ $item['title'] ?? '' }}" class="book-image">
                        <h5>{{ $item['title'] ?? '' }}</h5>
                        <p>{{ $item['price'] ?? '' }}</p>
                        <a href="#" class="btn btn-outline-primary btn-sm">Lihat Detail</a>
                    </div>
                </div>
            @endforeach
        </div>

        <h3 class="text-center mt-5">Teen Reading</h3>
        <div class="row">
            @foreach($categories['teen'] ?? [] as $item)
                <div class="col-md-3">
                    <div class="book-card text-center">
                        <img src="{{ $item['cover'] ?? '#' }}" alt="{{ $item['title'] ?? '' }}" class="book-image">
                        <h5>{{ $item['title'] ?? '' }}</h5>
                        <p>{{ $item['price'] ?? '' }}</p>
                        <a href="#" class="btn btn-outline-primary btn-sm">Lihat Detail</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
<!-- duplicate removed -->