<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Checkout') }}
        </h2>
    </x-slot>

    <div class="checkout-container">
        @if($cart->items->isEmpty())
            <div class="empty-cart-message">
                <p><strong>Keranjang Anda kosong!</strong></p>
                <p>Tambahkan produk ke keranjang terlebih dahulu sebelum melakukan checkout.</p>
                <a href="{{ route('welcome') }}" class="back-to-shop-btn">Mulai Belanja</a>
            </div>
        @else
            <div class="checkout-grid">
                <!-- Form Section -->
                <div class="checkout-form-section">
                    <h2 class="form-section-title">Informasi Pengiriman</h2>
                    
                    <form id="checkout-form">
                        @csrf
                        
                        <!-- Alamat Pengiriman -->
                        <div class="form-group">
                            <label for="address_text" class="form-label required">
                                Alamat Lengkap
                            </label>
                            <textarea 
                                id="address_text" 
                                name="address_text" 
                                class="form-textarea" 
                                required
                                placeholder="Masukkan alamat lengkap dengan detail jalan, RT/RW, Kelurahan, Kecamatan, Kota, Provinsi, Kode Pos"
                            >{{ old('address_text', auth()->user()->address ?? '') }}</textarea>
                        </div>

                        <!-- Nomor Telepon -->
                        <div class="form-group">
                            <label for="phone_number" class="form-label">
                                Nomor Telepon
                            </label>
                            <input 
                                type="tel" 
                                id="phone_number" 
                                name="phone_number"
                                class="form-input"
                                placeholder="08xxxxxxxxxx"
                                value="{{ old('phone_number', auth()->user()->phone ?? '') }}"
                            >
                        </div>

                        <!-- Metode Pengiriman -->
                        <div class="form-group">
                            <label class="form-label">Metode Pengiriman</label>
                            <div class="radio-group">
                                <label class="radio-option selected">
                                    <input type="radio" name="shipping_method" value="standard" checked>
                                    <div class="radio-label-content">
                                        <span class="radio-label-title">Reguler (3-5 hari)</span>
                                        <span class="radio-label-desc">Gratis ongkir</span>
                                    </div>
                                    <span class="radio-label-price">Gratis</span>
                                </label>
                                <label class="radio-option">
                                    <input type="radio" name="shipping_method" value="express">
                                    <div class="radio-label-content">
                                        <span class="radio-label-title">Express (1-2 hari)</span>
                                        <span class="radio-label-desc">Pengiriman cepat</span>
                                    </div>
                                    <span class="radio-label-price">Rp 25.000</span>
                                </label>
                            </div>
                        </div>

                        <!-- Catatan -->
                        <div class="form-group">
                            <label for="notes" class="form-label">
                                Catatan (Opsional)
                            </label>
                            <textarea 
                                id="notes" 
                                name="notes" 
                                class="form-textarea"
                                placeholder="Tambahkan catatan untuk pesanan Anda"
                            >{{ old('notes') }}</textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="checkout-submit-section">
                            <button type="submit" id="submit-order-btn" class="submit-btn">
                                <span id="btn-text">Proses Pesanan</span>
                                <span id="btn-loading" class="hidden">
                                    <svg class="animate-spin h-5 w-5 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Memproses...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Cart Summary Section -->
                <div class="cart-summary-section">
                    <h2 class="summary-title">Ringkasan Pesanan</h2>
                    
                    <!-- Cart Items -->
                    <div class="cart-items-preview">
                        @foreach($cart->items as $item)
                        <div class="cart-item-preview">
                            <img 
                                src="{{ $item->book->cover_url }}" 
                                alt="{{ $item->book->name }}"
                                class="item-image"
                                onerror="this.onerror=null; this.src='{{ asset('images/default-cover.svg') }}';"
                            >
                            <div class="item-details">
                                <h4 class="item-name">{{ $item->book->name }}</h4>
                                <p class="item-quantity">Jumlah: {{ $item->qty }}</p>
                                <p class="item-price">Rp{{ number_format($item->book->price * $item->qty, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Order Summary -->
                    <div class="order-summary">
                        <div class="summary-row">
                            <span class="summary-label">Subtotal ({{ $cart->items->count() }} item)</span>
                            <span class="summary-value" id="subtotal-amount">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Ongkos Kirim</span>
                            <span class="summary-value" id="shipping-amount">Gratis</span>
                        </div>
                        <div class="summary-row summary-total">
                            <span class="summary-label">Total</span>
                            <span class="summary-value" id="total-amount">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Info Keamanan -->
                    <div class="security-info">
                        <svg class="security-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Transaksi Anda aman dan terenkripsi</span>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
