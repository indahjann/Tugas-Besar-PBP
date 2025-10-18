{{-- Promo Carousel Section --}}
<section class="promo-section">
    <div class="promo-container">
        {{-- Navigation Arrows --}}
        <button class="promo-nav promo-prev" id="promoPrev" aria-label="Previous promo">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="promo-nav promo-next" id="promoNext" aria-label="Next promo">
            <i class="fas fa-chevron-right"></i>
        </button>

        {{-- Promo Carousel Track --}}
        <div class="promo-carousel" id="promoCarousel">
            <div class="promo-track" id="promoTrack">
                
                {{-- Promo 1: Back to Campus Sale --}}
                <div class="promo-slide">
                    <div class="promo-content promo-purple">
                        <div class="promo-text">
                            <h2 class="promo-title">Back to Campus</h2>
                            <p class="promo-subtitle">Diskon hingga 50%</p>
                            <p class="promo-desc">Buku Penunjang Kuliah, Perlengkapan Kuliah, Alat Tulis & Perlengkapan IT</p>
                            <p class="promo-period">1 SEP - 19 OKT 2025</p>
                            <a href="{{ route('categories.index') }}" class="promo-btn">Belanja Sekarang</a>
                        </div>
                        <div class="promo-image">
                            <div class="promo-illustration">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Promo 2: Fiction Festival --}}
                <div class="promo-slide">
                    <div class="promo-content promo-blue">
                        <div class="promo-text">
                            <h2 class="promo-title">Fiction Festival</h2>
                            <p class="promo-subtitle">Diskon hingga 40%</p>
                            <p class="promo-desc">Novel terbaik dari penulis favorit dengan harga spesial!</p>
                            <p class="promo-period">PROMO TERBATAS</p>
                            <a href="{{ route('categories.show', 1) }}" class="promo-btn">Belanja Sekarang</a>
                        </div>
                        <div class="promo-image">
                            <div class="promo-illustration">
                                <i class="fas fa-book-open"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Promo 3: Manga Madness --}}
                <div class="promo-slide">
                    <div class="promo-content promo-orange">
                        <div class="promo-text">
                            <h2 class="promo-title">Koleksi Manga Terbaru</h2>
                            <p class="promo-subtitle">Beli 3 Gratis 1</p>
                            <p class="promo-desc">Komik & Manga terpopuler dari Jepang dan Korea!</p>
                            <p class="promo-period">STOK TERBATAS</p>
                            <a href="{{ route('categories.show', 3) }}" class="promo-btn">Belanja Sekarang</a>
                        </div>
                        <div class="promo-image">
                            <div class="promo-illustration">
                                <i class="fas fa-book-reader"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Promo 4: Free Shipping --}}
                <div class="promo-slide">
                    <div class="promo-content promo-green">
                        <div class="promo-text">
                            <h2 class="promo-title">Gratis Pengiriman</h2>
                            <p class="promo-subtitle">Min. belanja Rp 150.000</p>
                            <p class="promo-desc">Pengiriman cepat ke seluruh Indonesia tanpa biaya tambahan!</p>
                            <p class="promo-period">BERLAKU HARI INI</p>
                            <a href="{{ route('categories.index') }}" class="promo-btn">Belanja Sekarang</a>
                        </div>
                        <div class="promo-image">
                            <div class="promo-illustration">
                                <i class="fas fa-shipping-fast"></i>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Indicator Dots --}}
        <div class="promo-indicators" id="promoIndicators">
            <button class="indicator active" data-index="0" aria-label="Go to promo 1"></button>
            <button class="indicator" data-index="1" aria-label="Go to promo 2"></button>
            <button class="indicator" data-index="2" aria-label="Go to promo 3"></button>
            <button class="indicator" data-index="3" aria-label="Go to promo 4"></button>
        </div>
    </div>
</section>