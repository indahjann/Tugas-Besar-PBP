<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'UMKM Mini-Commerce')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { font-family: Arial, sans-serif; }
        .promo-banner { background: linear-gradient(135deg, #ff6b6b, #ffd93d); color: white; padding: 20px; text-align: center; }
        .book-card { border: 1px solid #ddd; border-radius: 8px; padding: 15px; margin: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); transition: transform 0.2s; }
        .book-card:hover { transform: scale(1.02); }
        .book-image { width: 100%; height: 250px; object-fit: cover; border-radius: 5px; }
        .discount { color: red; font-weight: bold; font-size: 1.2em; }
        .original-price { text-decoration: line-through; color: #999; }
        .new-label { background: #ffc107; color: #000; padding: 3px 8px; border-radius: 12px; font-size: 0.8em; }
        .fast-delivery { background: #28a745; color: white; padding: 3px 8px; border-radius: 12px; font-size: 0.8em; }
        .category-section { margin: 40px 0; }
        .testimonial-card { background: #f8f9fa; border-radius: 10px; padding: 20px; margin: 10px; }
        .stars { color: #ffc107; }
        footer { background: #343a40; color: white; padding: 20px; text-align: center; }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">UMKM Mini-Commerce</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/books') }}">Semua Buku</a></li>
                    <li class="nav-item"><a class="nav-link" href="/products">Semua Produk</a></li>
                    <li class="nav-item"><a class="nav-link" href="/cart">Cart</a></li>
                    <li class="nav-item"><a class="nav-link" href="/login">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Konten halaman -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; {{ date('Y') }} UMKM Mini-Commerce. Semua hak cipta dilindungi.</p>
        <p>Hubungi kami: info@umkmcommerce.com | +62 21 12345678</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
