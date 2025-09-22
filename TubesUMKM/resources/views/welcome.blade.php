<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Buku Online - Mirip Periplus</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
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

    <!-- Header/Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Toko Buku Online</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Semua Buku</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Kategori</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Cart</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Promo Banner -->
    <section class="promo-banner">
        <h2>Splendid September - Diskon Hingga 20% untuk Buku Terpilih!</h2>
        <p>Belanja sekarang dan dapatkan pengiriman cepat.</p>
        <a href="#" class="btn btn-light btn-lg">Lihat Promo</a>
    </section>

    <!-- Featured Books -->
    <section class="container">
        <h2 class="text-center my-4">Featured Books</h2>
        <div class="row">
            <!-- Buku 1: The Little Prince -->
            <div class="col-md-3">
                <div class="book-card text-center">
                    <img src="https://images.unsplash.com/photo-1512820790803-83ca3b5e?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=250&q=80" alt="The Little Prince" class="book-image">
                    <h5>The Little Prince</h5>
                    <p class="text-muted">by Antoine de Saint-Exupéry</p>
                    <p>Paperback</p>
                    <p class="discount">Rp 150.000 <span class="original-price">Rp 187.500</span> -20%</p>
                    <span class="fast-delivery">Fast Delivery</span>
                    <div class="mt-2">
                        <a href="#" class="btn btn-primary btn-sm">Buy Now</a>
                        <a href="#" class="btn btn-outline-secondary btn-sm">Add to Cart</a>
                        <a href="#" class="btn btn-link btn-sm">Wishlist</a>
                    </div>
                </div>
            </div>

            <!-- Buku 2: Blue Lock 21 -->
            <div class="col-md-3">
                <div class="book-card text-center">
                    <img src="https://images.unsplash.com/photo-1544947950-fa07a98d237f?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=250&q=80" alt="Blue Lock 21" class="book-image">
                    <h5>Blue Lock 21</h5>
                    <p class="text-muted">by Muneyuki Kaneshiro</p>
                    <p>Paperback</p>
                    <p>Rp 75.000</p>
                    <span class="new-label">NEW</span>
                    <div class="mt-2">
                        <a href="#" class="btn btn-primary btn-sm">Buy Now</a>
                        <a href="#" class="btn btn-outline-secondary btn-sm">Add to Cart</a>
                        <a href="#" class="btn btn-link btn-sm">Wishlist</a>
                    </div>
                </div>
            </div>

            <!-- Buku 3: Thorn Season -->
            <div class="col-md-3">
                <div class="book-card text-center">
                    <img src="https://images.unsplash.com/photo-1543002588-25d3a4b1e5a3?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=250&q=80" alt="Thorn Season" class="book-image">
                    <h5>Thorn Season</h5>
                    <p class="text-muted">by Iris Mwangi</p>
                    <p>Hardcover</p>
                    <p class="discount">Rp 200.000 <span class="original-price">Rp 250.000</span> -20%</p>
                    <span class="new-label">NEW</span>
                    <div class="mt-2">
                        <a href="#" class="btn btn-primary btn-sm">Buy Now</a>
                        <a href="#" class="btn btn-outline-secondary btn-sm">Add to Cart</a>
                        <a href="#" class="btn btn-link btn-sm">Wishlist</a>
                    </div>
                </div>
            </div>

            <!-- Tambah buku lain jika perlu, ulangi col-md-3 -->
            <div class="col-md-3">
                <div class="book-card text-center">
                    <img src="https://images.unsplash.com/photo-1532012197267-da84d127e765?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=250&q=80" alt="Another Book" class="book-image">
                    <h5>Another Bestseller</h5>
                    <p class="text-muted">by Author Name</p>
                    <p>Paperback</p>
                    <p>Rp 120.000</p>
                    <span class="fast-delivery">Fast Delivery</span>
                    <div class="mt-2">
                        <a href="#" class="btn btn-primary btn-sm">Buy Now</a>
                        <a href="#" class="btn btn-outline-secondary btn-sm">Add to Cart</a>
                        <a href="#" class="btn btn-link btn-sm">Wishlist</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Kategori Sections -->
    <section class="category-section">
        <div class="container">
            <!-- Bestseller Fiction -->
            <h3 class="text-center">Bestseller Fiction</h3>
            <div class="row">
                <div class="col-md-3">
                    <div class="book-card text-center">
                        <img src="https://images.unsplash.com/photo-1512820790803-83ca3b5e?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=250&q=80" alt="The Little Prince" class="book-image">
                        <h5>The Little Prince</h5>
                        <p>Rp 150.000</p>
                        <a href="#" class="btn btn-outline-primary btn-sm">Lihat Detail</a>
                    </div>
                </div>
                <!-- Tambah 3 buku lagi untuk kategori ini -->
                <div class="col-md-3">
                    <div class="book-card text-center">
                        <img src="https://images.unsplash.com/photo-1543002588-25d3a4b1e5a3?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=250&q=80" alt="Fiction Book" class="book-image">
                        <h5>Fiction Book 1</h5>
                        <p>Rp 180.000</p>
                        <a href="#" class="btn btn-outline-primary btn-sm">Lihat Detail</a>
                    </div>
                </div>
                <!-- Ulangi untuk 4 item -->
            </div>

            <!-- Comic and Manga -->
            <h3 class="text-center mt-5">Comic and Manga</h3>
            <div class="row">
                <div class="col-md-3">
                    <div class="book-card text-center">
                        <img src="https://images.unsplash.com/photo-1544947950-fa07a98d237f?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=250&q=80" alt="Blue Lock" class="book-image">
                        <h5>Blue Lock 21</h5>
                        <p>Rp 75.000</p>
                        <a href="#" class="btn btn-outline-primary btn-sm">Lihat Detail</a>
                    </div>
                </div>
                <!-- Tambah item lain -->
            </div>

            <!-- Teen Reading -->
            <h3 class="text-center mt-5">Teen Reading</h3>
            <div class="row">
                <div class="col-md-3">
                    <div class="book-card text-center">
                        <img src="https://images.unsplash.com/photo-1532012197267-da84d127e765?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=250&q=80" alt="Teen Book" class="book-image">
                        <h5>Thorn Season</h5>
                        <p>Rp 200.000</p>
                        <a href="#" class="btn btn-outline-primary btn-sm">Lihat Detail</a>
                    </div>
                </div>
                <!-- Tambah item lain -->
            </div>
        </div>
    </section>

    <!-- Testimonial -->
    <section class="container my-5">
        <h2 class="text-center">Testimonial Pelanggan</h2>
        <div class="row">
            <div class="col-md-3">
                <div class="testimonial-card">
                    <p>"Pesanan kesekian kalinya dan tidak pernah kecewa. Buku berkualitas dan pengiriman cepat!"</p>
                    <p><strong>Anna S.</strong> - Jakarta</p>
                    <p class="stars">⭐⭐⭐⭐⭐</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="testimonial-card">
                    <p>"Koleksi manganya lengkap, diskonnya mantap. Rekomendasi banget!"</p>
                    <p><strong>Budi K.</strong> - Bandung</p>
                    <p class="stars">⭐⭐⭐⭐⭐</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="testimonial-card">
                    <p>"Buku fiksi favorit saya ada semua. Layanan customer service ramah."</p>
                    <p><strong>Citra L.</strong> - Surabaya</p>
                    <p class="stars">⭐⭐⭐⭐</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="testimonial-card">
                    <p>"Fast delivery beneran cepat, buku baru sampai dalam 2 hari."</p>
                    <p><strong>Dedi M.</strong> - Medan</p>
                    <p class="stars">⭐⭐⭐⭐⭐</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2023 Toko Buku Online. Terinspirasi dari Periplus. Semua hak cipta dilindungi.</p>
        <p>Hubungi kami: info@tokobuku.com | +62 21 12345678</p>
    </footer>

    <!-- Bootstrap JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS (opsional, untuk interaksi nanti) -->
    <script>
        // Contoh: Alert saat klik Buy Now (untuk demo)
        document.querySelectorAll('.btn-primary').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                alert('Buku ditambahkan ke cart! (Demo)');
            });
        });
    </script>
</body>
</html>