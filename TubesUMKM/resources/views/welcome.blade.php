<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BUKUKU - Premium Books Collection</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        
        /* NAVBAR STYLES */
        .navbar {
            background-color: white !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 1rem 0;
        }
        
        .navbar-brand {
            font-weight: 700;
            color: #2c5aa0 !important;
            font-size: 1.5rem;
        }
        
        .nav-link {
            font-weight: 500;
            color: #333 !important;
            margin: 0 10px;
            transition: color 0.3s ease;
        }
        
        .nav-link:hover {
            color: #2c5aa0 !important;
        }
        
        .btn-login {
            background-color: #2c5aa0;
            color: white;
            border: 1px solid #2c5aa0;
            padding: 8px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            margin-left: 10px;
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            background-color: #1e3d6f;
            color: white;
        }
        
        .btn-register {
            background-color: transparent;
            color: #2c5aa0;
            border: 1px solid #2c5aa0;
            padding: 8px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            margin-left: 10px;
            transition: all 0.3s ease;
        }
        
        .btn-register:hover {
            background-color: #2c5aa0;
            color: white;
        }
        
        /* Account Dropdown Styles */
        .account-dropdown {
            position: relative;
            display: inline-block;
        }
        
        .account-trigger {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 15px;
            color: #333;
            text-decoration: none;
            border-radius: 6px;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .account-trigger:hover {
            background-color: #f8f9fa;
            color: #2c5aa0;
            text-decoration: none;
        }
        
        .account-icon {
            width: 24px;
            height: 24px;
            background-color: #6c757d;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .account-trigger:hover .account-icon {
            background-color: #2c5aa0;
        }
        
        .dropdown-menu-custom {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            min-width: 200px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 1000;
            padding: 0;
            overflow: hidden;
        }
        
        .account-dropdown:hover .dropdown-menu-custom {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .dropdown-item-custom {
            display: block;
            padding: 12px 20px;
            color: #333;
            text-decoration: none;
            transition: all 0.2s ease;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .dropdown-item-custom:last-child {
            border-bottom: none;
        }
        
        .dropdown-item-custom:hover {
            background-color: #f8f9fa;
            color: #2c5aa0;
            text-decoration: none;
        }
        
        .dropdown-item-custom i {
            margin-right: 10px;
            width: 16px;
        }
        
        /* User greeting styles */
        .user-greeting {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-name {
            color: #333;
            font-weight: 500;
        }
        
        .cart-link {
            position: relative;
            color: #333;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        
        .cart-link:hover {
            background-color: #f8f9fa;
            color: #2c5aa0;
            text-decoration: none;
        }
        
        .logout-btn {
            background: transparent;
            border: 1px solid #dc3545;
            color: #dc3545;
            padding: 6px 15px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .logout-btn:hover {
            background: #dc3545;
            color: white;
        }
        
        /* Promo Banner */
        .promo-banner { 
            background: linear-gradient(135deg, #2c5aa0, #4a90e2); 
            color: white; 
            padding: 30px 20px; 
            text-align: center; 
        }
        
        /* Featured Section */
        .featured-section {
            padding: 60px 0;
            background-color: #f8f9fa;
        }
        
        .carousel-container-bg {
            background: white;
            border-radius: 12px;
            padding: 30px 80px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            position: relative;
        }
        
        .section-title {
            font-size: 2.5rem;
            font-weight: 300;
            color: #2c3e50;
            margin-bottom: 2rem;
        }
        
        /* Custom Carousel */
        .carousel-viewport {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            z-index: 100;
        }
        
        .custom-carousel {
            position: relative;
            z-index: 200;
        }
        
        .carousel-track {
            display: flex;
            transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            gap: 20px;
        }
        
        .carousel-item-custom {
            flex: 0 0 220px;
            display: flex;
            flex-direction: column;
        }
        
        /* Navigation Buttons */
        .nav-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.98);
            border: 1px solid #ddd;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 1000;
            transition: all 0.3s ease;
            font-size: 18px;
            color: #666;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .nav-btn:hover {
            background: white;
            box-shadow: 0 4px 16px rgba(0,0,0,0.2);
            color: #2c5aa0;
            z-index: 1001;
            transform: translateY(-50%) scale(1.05);
        }
        
        .nav-btn.prev {
            left: 10px;
        }
        
        .nav-btn.next {
            right: 10px;
        }
        
        /* Book Cards - Modern Style */
        .book-card-modern {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .book-card-modern:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .book-image-container {
            position: relative;
            height: 220px;
            overflow: hidden;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .book-image {
            width: 75%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            transition: transform 0.3s ease;
            border-radius: 4px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .placeholder-box {
            width: 120px;
            height: 160px;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: #adb5bd;
            border-radius: 4px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        /* Hover Overlay */
        .hover-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(44, 90, 160, 0.9);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .book-card-modern:hover .hover-overlay {
            opacity: 1;
        }
        
        .book-card-modern:hover .book-image {
            transform: scale(1.05);
        }
        
        .hover-actions {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }
        
        .btn-buy-now {
            background: white;
            color: #2c5aa0;
            border: none;
            padding: 10px 24px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .btn-buy-now:hover {
            background: #f8f9fa;
            transform: translateY(-1px);
        }
        
        .action-buttons {
            display: flex;
            gap: 8px;
        }
        
        .btn-cart, .btn-favorite {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .btn-cart:hover, .btn-favorite:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-1px);
        }
        
        /* Book Info */
        .book-info {
            padding: 12px 16px 16px 16px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        
        .book-title {
            font-size: 14px;
            font-weight: 600;
            color: #2c3e50;
            margin: 0 0 4px 0;
            line-height: 1.3;
            height: 2.6em;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }
        
        .book-author {
            font-size: 12px;
            color: #7f8c8d;
            margin: 0 0 2px 0;
        }
        
        .book-format {
            font-size: 12px;
            color: #95a5a6;
            margin: 0 0 8px 0;
        }
        
        .price-section {
            margin-top: auto;
        }
        
        .current-price {
            font-size: 16px;
            font-weight: 700;
            color: #e74c3c;
            margin: 0 0 4px 0;
        }
        
        .discount-info {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .discount-badge {
            background: #e74c3c;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: 600;
        }
        
        .original-price {
            font-size: 12px;
            color: #95a5a6;
            text-decoration: line-through;
        }
        
        /* View More Button */
        .view-more-btn {
            background: #2c5aa0;
            color: white;
            border: 1px solid #2c5aa0;
            padding: 12px 30px;
            border-radius: 6px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }
        
        .view-more-btn:hover {
            background: #1e3d6f;
            border-color: #1e3d6f;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(44, 90, 160, 0.3);
        }
        
        /* Legacy styles for other sections */
        .category-section { margin: 40px 0; }
        .testimonial-card { background: #f8f9fa; border-radius: 10px; padding: 20px; margin: 10px; }
        .stars { color: #ffc107; }
        footer { background: #343a40; color: white; padding: 20px; text-align: center; }
        
        /* Responsive */
        @media (max-width: 768px) {
            .carousel-item-custom {
                flex: 0 0 180px;
            }
            .nav-btn {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }
            .nav-btn.prev {
                left: -15px;
            }
            .nav-btn.next {
                right: -15px;
            }
            .section-title {
                font-size: 2rem;
            }
            .carousel-container-bg {
                padding: 20px 30px;
            }
        }
    </style>
</head>
<body>

    <!-- Header/Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="/">BUKUKU</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="#">Semua Buku</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Kategori</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Bestsellers</a></li>
                </ul>
                <ul class="navbar-nav">
                    @if (Route::has('login'))
                        @auth
                            <div class="user-greeting">
                                <a href="#" class="cart-link">
                                    <i class="fas fa-shopping-cart"></i>
                                    <span class="d-none d-md-inline ms-1">Cart</span>
                                </a>
                                
                                <div class="account-dropdown">
                                    <div class="account-trigger">
                                        <div class="account-icon">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                                    </div>
                                    
                                    <div class="dropdown-menu-custom">
                                        @if(Auth::user()->role === 'admin')
                                            <a href="{{ route('dashboard') }}" class="dropdown-item-custom">
                                                <i class="fas fa-tachometer-alt"></i>Admin Dashboard
                                            </a>
                                            <div style="border-bottom: 1px solid #f0f0f0; margin: 8px 0;"></div>
                                        @endif
                                        
                                        <a href="#" class="dropdown-item-custom">
                                            <i class="fas fa-user"></i>My Profile
                                        </a>
                                        
                                        @if(Auth::user()->role === 'user')
                                            <a href="#" class="dropdown-item-custom">
                                                <i class="fas fa-shopping-bag"></i>View Orders History
                                            </a>
                                            <a href="#" class="dropdown-item-custom">
                                                <i class="fas fa-heart"></i>Wishlist
                                            </a>
                                        @endif
                                        <form method="POST" action="{{ route('logout') }}" class="d-inline w-100">
                                            @csrf
                                            <button type="submit" class="dropdown-item-custom w-100 text-start" style="border: none; background: none;">
                                                <i class="fas fa-sign-out-alt"></i>Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <li class="nav-item">
                                <div class="account-dropdown">
                                    <div class="account-trigger">
                                        <div class="account-icon">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <span class="d-none d-md-inline">Sign In</span>
                                    </div>
                                    
                                    <div class="dropdown-menu-custom">
                                        <a href="{{ route('login') }}" class="dropdown-item-custom">
                                            <i class="fas fa-sign-in-alt"></i>Login
                                        </a>
                                        @if (Route::has('register'))
                                            <a href="{{ route('register') }}" class="dropdown-item-custom">
                                                <i class="fas fa-user-plus"></i>Register
                                            </a>
                                        @endif
                                        <a href="{{ route('password.request') }}" class="dropdown-item-custom">
                                            <i class="fas fa-key"></i>Forgotten Password
                                        </a>
                                    </div>
                                </div>
                            </li>
                        @endauth
                    @endif
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

    <!-- buku -->
    <section class="featured-section">
        <div class="container">
            <div class="carousel-container-bg">
                <button class="nav-btn prev" id="prevBtn"><i class="fas fa-chevron-left"></i></button>
                <button class="nav-btn next" id="nextBtn"><i class="fas fa-chevron-right"></i></button>
                <h2 class="section-title text-center mb-4">Buku Tersedia</h2>
                <div class="carousel-viewport">

                    <div class="custom-carousel" id="bookCarousel">
                        
                        
                        <div class="carousel-track" id="carouselTrack">
                            <!-- Book 1 - Beautiful Ugly -->
                            <div class="carousel-item-custom">
                                <div class="book-card book-card-modern">
                                    <div class="book-image-container">
                                        <img src="https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1722587059i/211004123.jpg" 
                                             class="book-image" 
                                             alt="Beautiful Ugly">
                                        <div class="hover-overlay">
                                            <div class="hover-actions">
                                                <button class="btn-buy-now">Buy Now</button>
                                                <div class="action-buttons">
                                                    <button class="btn-cart" title="Add to Cart">
                                                        <i class="fas fa-shopping-cart"></i>
                                                    </button>
                                                    <button class="btn-favorite" title="Add to Favorites">
                                                        <i class="far fa-heart"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="book-info">
                                        <h6 class="book-title">Beautiful Ugly</h6>
                                        <p class="book-author">Feeney, Alice</p>
                                        <p class="book-format">Paperback</p>
                                        <div class="price-section">
                                            <p class="current-price">Rp 285,000</p>
                                            <div class="discount-info">
                                                <span class="discount-badge">-20%</span>
                                                <span class="original-price">Rp 357,000</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Book 2 - Harry Potter -->
                            <div class="carousel-item-custom">
                                <div class="book-card book-card-modern">
                                    <div class="book-image-container">
                                        <img src="https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1630547330i/5.jpg"
                                             class="book-image" 
                                             alt="Harry Potter and the Sorcerer's Stone">
                                        <div class="hover-overlay">
                                            <div class="hover-actions">
                                                <button class="btn-buy-now">Buy Now</button>
                                                <div class="action-buttons">
                                                    <button class="btn-cart" title="Add to Cart">
                                                        <i class="fas fa-shopping-cart"></i>
                                                    </button>
                                                    <button class="btn-favorite" title="Add to Favorites">
                                                        <i class="far fa-heart"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="book-info">
                                        <h6 class="book-title">Harry Potter and the...</h6>
                                        <p class="book-author">Rowling, J.K.</p>
                                        <p class="book-format">Hardcover</p>
                                        <div class="price-section">
                                            <p class="current-price">Rp 610,000</p>
                                            <div class="discount-info">
                                                <span class="discount-badge">-20%</span>
                                                <span class="original-price">Rp 763,000</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Book 3 - 86--Eighty-Six -->
                            <div class="carousel-item-custom">
                                <div class="book-card book-card-modern">
                                    <div class="book-image-container">
                                        <img src="https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1549932153i/41825371.jpg" 
                                             class="book-image" 
                                             alt="86--Eighty-Six">
                                        <div class="hover-overlay">
                                            <div class="hover-actions">
                                                <button class="btn-buy-now">Buy Now</button>
                                                <div class="action-buttons">
                                                    <button class="btn-cart" title="Add to Cart">
                                                        <i class="fas fa-shopping-cart"></i>
                                                    </button>
                                                    <button class="btn-favorite" title="Add to Favorites">
                                                        <i class="far fa-heart"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="book-info">
                                        <h6 class="book-title">86--Eighty-Six, Vol. ...</h6>
                                        <p class="book-author">Asato, Asato</p>
                                        <p class="book-format">Paperback</p>
                                        <div class="price-section">
                                            <p class="current-price">Rp 227,000</p>
                                            <div class="discount-info">
                                                <span class="discount-badge">-20%</span>
                                                <span class="original-price">Rp 284,000</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Book 4 - The Noma Guide -->
                            <div class="carousel-item-custom">
                                <div class="book-card book-card-modern">
                                    <div class="book-image-container">
                                        <img src="https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1523893043i/37590384.jpg" 
                                             class="book-image" 
                                             alt="The Noma Guide">
                                        <div class="hover-overlay">
                                            <div class="hover-actions">
                                                <button class="btn-buy-now">Buy Now</button>
                                                <div class="action-buttons">
                                                    <button class="btn-cart" title="Add to Cart">
                                                        <i class="fas fa-shopping-cart"></i>
                                                    </button>
                                                    <button class="btn-favorite" title="Add to Favorites">
                                                        <i class="far fa-heart"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="book-info">
                                        <h6 class="book-title">The Noma Guide to</h6>
                                        <p class="book-author">Redzepi, René</p>
                                        <p class="book-format">Hardcover</p>
                                        <div class="price-section">
                                            <p class="current-price">Rp 668,000</p>
                                            <div class="discount-info">
                                                <span class="discount-badge">-20%</span>
                                                <span class="original-price">Rp 835,000</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Book 5 - Your Letter -->
                            <div class="carousel-item-custom">
                                <div class="book-card book-card-modern">
                                    <div class="book-image-container">
                                        <img src="https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1712760931i/205761314.jpg" 
                                             class="book-image" 
                                             alt="Your Letter">
                                        <div class="hover-overlay">
                                            <div class="hover-actions">
                                                <button class="btn-buy-now">Buy Now</button>
                                                <div class="action-buttons">
                                                    <button class="btn-cart" title="Add to Cart">
                                                        <i class="fas fa-shopping-cart"></i>
                                                    </button>
                                                    <button class="btn-favorite" title="Add to Favorites">
                                                        <i class="far fa-heart"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="book-info">
                                        <h6 class="book-title">Your Letter</h6>
                                        <p class="book-author">Cho, Hyeon A.</p>
                                        <p class="book-format">Paperback</p>
                                        <div class="price-section">
                                            <p class="current-price">Rp 330,000</p>
                                            <div class="discount-info">
                                                <span class="discount-badge">-20%</span>
                                                <span class="original-price">Rp 413,000</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Book 6 -  -->
                            <div class="carousel-item-custom">
                                <div class="book-card book-card-modern">
                                    <div class="book-image-container">
                                        <img src="https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1364848267i/16248196.jpg" 
                                             class="book-image" 
                                             alt="The Art of Thinking Clearly">
                                        <div class="hover-overlay">
                                            <div class="hover-actions">
                                                <button class="btn-buy-now">Buy Now</button>
                                                <div class="action-buttons">
                                                    <button class="btn-cart" title="Add to Cart">
                                                        <i class="fas fa-shopping-cart"></i>
                                                    </button>
                                                    <button class="btn-favorite" title="Add to Favorites">
                                                        <i class="far fa-heart"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="book-info">
                                        <h6 class="book-title">The Art of Thinking Clearly</h6>
                                        <p class="book-author">Rolf Dobelli</p>
                                        <p class="book-format">Paperback</p>
                                        <div class="price-section">
                                            <p class="current-price">Rp 190,000</p>
                                            <div class="discount-info">
                                                <span class="discount-badge">-20%</span>
                                                <span class="original-price">Rp 238,000</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Book 7 - The Psychology of Money -->
                            <div class="carousel-item-custom">
                                <div class="book-card book-card-modern">
                                    <div class="book-image-container">
                                        <img src="https://cdn.gramedia.com/uploads/items/psychology_of_money.jpg" 
                                             class="book-image" 
                                             alt="Psychology of Money">
                                        <div class="hover-overlay">
                                            <div class="hover-actions">
                                                <button class="btn-buy-now">Buy Now</button>
                                                <div class="action-buttons">
                                                    <button class="btn-cart" title="Add to Cart">
                                                        <i class="fas fa-shopping-cart"></i>
                                                    </button>
                                                    <button class="btn-favorite" title="Add to Favorites">
                                                        <i class="far fa-heart"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="book-info">
                                        <h6 class="book-title">The Psychology of Money</h6>
                                        <p class="book-author">Housel, Morgan</p>
                                        <p class="book-format">Paperback</p>
                                        <div class="price-section">
                                            <p class="current-price">Rp 185,000</p>
                                            <div class="discount-info">
                                                <span class="discount-badge">-20%</span>
                                                <span class="original-price">Rp 231,000</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Book 8 - Atomic Habits -->
                            <div class="carousel-item-custom">
                                <div class="book-card book-card-modern">
                                    <div class="book-image-container">
                                        <img src="https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1655988385i/40121378.jpg" 
                                             class="book-image" 
                                             alt="Atomic Habits">
                                        <div class="hover-overlay">
                                            <div class="hover-actions">
                                                <button class="btn-buy-now">Buy Now</button>
                                                <div class="action-buttons">
                                                    <button class="btn-cart" title="Add to Cart">
                                                        <i class="fas fa-shopping-cart"></i>
                                                    </button>
                                                    <button class="btn-favorite" title="Add to Favorites">
                                                        <i class="far fa-heart"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="book-info">
                                        <h6 class="book-title">Atomic Habits</h6>
                                        <p class="book-author">Clear, James</p>
                                        <p class="book-format">Paperback</p>
                                        <div class="price-section">
                                            <p class="current-price">Rp 195,000</p>
                                            <div class="discount-info">
                                                <span class="discount-badge">-20%</span>
                                                <span class="original-price">Rp 244,000</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <button class="btn view-more-btn">VIEW MORE</button>
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
        <p>&copy; 2023 BUKUKU - Premium Books Collection. Semua hak cipta dilindungi.</p>
        <p>Hubungi kami: info@bukuku.com | +62 21 12345678</p>
    </footer>

    <!-- Bootstrap JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom Carousel JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const carousel = document.getElementById('bookCarousel');
            const track = document.getElementById('carouselTrack');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            
            if (!carousel || !track || !prevBtn || !nextBtn) return;
            
            const originalItems = Array.from(track.querySelectorAll('.carousel-item-custom'));
            const itemWidth = 240; // 220px + 20px gap
            const totalItems = originalItems.length;
            
            // Clone items for infinite loop
            // Clone first few items and append to end
            const clonesToAppend = Math.min(4, totalItems);
            for (let i = 0; i < clonesToAppend; i++) {
                const clone = originalItems[i].cloneNode(true);
                clone.classList.add('carousel-clone');
                track.appendChild(clone);
            }
            
            // Clone last few items and prepend to beginning
            const clonesToPrepend = Math.min(4, totalItems);
            for (let i = clonesToPrepend - 1; i >= 0; i--) {
                const clone = originalItems[totalItems - 1 - i].cloneNode(true);
                clone.classList.add('carousel-clone');
                track.insertBefore(clone, track.firstChild);
            }
            
            // Update all items array to include clones
            const allItems = Array.from(track.querySelectorAll('.carousel-item-custom'));
            const startIndex = clonesToPrepend;
            let currentIndex = startIndex;
            
            // Set initial position (start at first real item, after prepended clones)
            let currentTranslate = startIndex * itemWidth;
            track.style.transform = `translateX(-${currentTranslate}px)`;
            
            let autoSlideInterval;
            let isTransitioning = false;
            let lastSlideTime = 0;
            const slideThrottle = 1000; // Minimum 1 second between slides
            
            function updateCarousel(animate = true) {
                if (animate) {
                    track.style.transition = 'transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
                } else {
                    track.style.transition = 'none';
                }
                track.style.transform = `translateX(-${currentTranslate}px)`;
                
                // Remove transition disable after a brief moment
                if (!animate) {
                    setTimeout(() => {
                        track.style.transition = 'transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
                    }, 50);
                }
            }
            
            function nextSlide() {
                const now = Date.now();
                if (isTransitioning || (now - lastSlideTime) < slideThrottle) return;
                
                lastSlideTime = now;
                isTransitioning = true;
                currentIndex++;
                currentTranslate += itemWidth;
                
                updateCarousel(true);
                
                // Check if we need to loop back
                setTimeout(() => {
                    if (currentIndex >= totalItems + startIndex) {
                        // We're at a clone at the end, jump back to real start
                        currentIndex = startIndex;
                        currentTranslate = startIndex * itemWidth;
                        updateCarousel(false);
                    }
                    isTransitioning = false;
                }, 600); // Match transition duration
            }
            
            function prevSlide() {
                const now = Date.now();
                if (isTransitioning || (now - lastSlideTime) < slideThrottle) return;
                
                lastSlideTime = now;
                isTransitioning = true;
                currentIndex--;
                currentTranslate -= itemWidth;
                
                updateCarousel(true);
                
                // Check if we need to loop forward
                setTimeout(() => {
                    if (currentIndex < startIndex) {
                        // We're at a clone at the beginning, jump to real end
                        currentIndex = totalItems + startIndex - 1;
                        currentTranslate = currentIndex * itemWidth;
                        updateCarousel(false);
                    }
                    isTransitioning = false;
                }, 600); // Match transition duration
            }
            
            function startAutoSlide() {
                autoSlideInterval = setInterval(nextSlide, 6000); // 6 detik
            }
            
            function stopAutoSlide() {
                clearInterval(autoSlideInterval);
            }
            
            // Event listeners
            nextBtn.addEventListener('click', function() {
                stopAutoSlide();
                nextSlide();
                setTimeout(startAutoSlide, 8000); // Resume after 8 seconds
            });
            
            prevBtn.addEventListener('click', function() {
                stopAutoSlide();
                prevSlide();
                setTimeout(startAutoSlide, 8000); // Resume after 8 seconds
            });
            
            // Pause auto-slide on carousel hover (as backup)
            carousel.addEventListener('mouseenter', stopAutoSlide);
            carousel.addEventListener('mouseleave', startAutoSlide);
            
            // Individual card hover pause with debounce
            let hoverTimeout;
            let cardsWithListeners = new Set();
            
            function setupCardHoverPause() {
                const cards = document.querySelectorAll('.book-card-modern');
                
                cards.forEach(card => {
                    // Skip if this card already has listeners
                    if (cardsWithListeners.has(card)) return;
                    
                    cardsWithListeners.add(card);
                    
                    const mouseEnterHandler = function() {
                        clearTimeout(hoverTimeout);
                        stopAutoSlide();
                        // Add visual feedback
                        this.style.zIndex = '10';
                        this.style.transition = 'transform 0.3s ease, box-shadow 0.3s ease';
                    };
                    
                    const mouseLeaveHandler = function() {
                        clearTimeout(hoverTimeout);
                        // Remove visual feedback
                        this.style.zIndex = '1';
                        
                        // Delay restart to prevent flickering when moving between cards
                        hoverTimeout = setTimeout(() => {
                            startAutoSlide();
                        }, 300);
                    };
                    
                    card.addEventListener('mouseenter', mouseEnterHandler);
                    card.addEventListener('mouseleave', mouseLeaveHandler);
                    
                    // Store handlers for potential cleanup
                    card._hoverHandlers = {
                        mouseenter: mouseEnterHandler,
                        mouseleave: mouseLeaveHandler
                    };
                });
            }
            
            // Initialize
            updateCarousel(false);
            startAutoSlide();
            
            // Setup hover pause for all cards (including clones)
            setTimeout(() => {
                setupCardHoverPause();
            }, 100);
            
            // Handle window resize
            let resizeTimeout;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(function() {
                    // Recalculate position on resize
                    currentTranslate = currentIndex * itemWidth;
                    updateCarousel(false);
                }, 250);
            });
            
            // Re-enable event handlers for cloned items
            setTimeout(() => {
                setupButtonHandlers();
                setupCardHoverPause(); // Re-setup hover for cloned items too
            }, 100);
        });
        
        // Button interaction handlers
        function setupButtonHandlers() {
            // Buy Now buttons
            document.querySelectorAll('.btn-buy-now').forEach(btn => {
                // Remove existing listeners to avoid duplicates
                btn.replaceWith(btn.cloneNode(true));
            });
            
            document.querySelectorAll('.btn-buy-now').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    alert('Redirecting to checkout... (Demo)');
                });
            });
            
            // Add to Cart buttons
            document.querySelectorAll('.btn-cart').forEach(btn => {
                btn.replaceWith(btn.cloneNode(true));
            });
            
            document.querySelectorAll('.btn-cart').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const card = this.closest('.book-card-modern');
                    const title = card.querySelector('.book-title').textContent;
                    alert(`"${title}" added to cart! (Demo)`);
                });
            });
            
            // Favorite buttons
            document.querySelectorAll('.btn-favorite').forEach(btn => {
                btn.replaceWith(btn.cloneNode(true));
            });
            
            document.querySelectorAll('.btn-favorite').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const icon = this.querySelector('i');
                    if (icon.classList.contains('far')) {
                        icon.classList.remove('far');
                        icon.classList.add('fas');
                        this.style.color = '#e74c3c';
                    } else {
                        icon.classList.remove('fas');
                        icon.classList.add('far');
                        this.style.color = 'white';
                    }
                });
            });
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            // Initial setup of button handlers
            setupButtonHandlers();
            
            // View More button
            const viewMoreBtn = document.querySelector('.view-more-btn');
            if (viewMoreBtn) {
                viewMoreBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    alert('Redirecting to full catalog... (Demo)');
                });
            }
            
            // Legacy Buy Now buttons for other sections
            document.querySelectorAll('.btn-primary').forEach(btn => {
                if (!btn.classList.contains('btn-buy-now')) {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        alert('Buku ditambahkan ke cart! (Demo)');
                    });
                }
            });
        });
    </script>
</body>
</html>