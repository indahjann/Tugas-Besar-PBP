<!-- Cart Page -->
<div class="cart-page">
    <!-- Empty cart illustration (ketika keranjang kosong) -->
    @if($cartItems->isEmpty())
    <div class="empty-cart-container">
        <div class="empty-cart-content">
            <div class="empty-cart-illustration"></div>
            <div class="empty-cart-text">
                <h2>Keranjang Kamu Kosong</h2>
                <p>Kami punya banyak barang yang siap memberi kamu kebahagiaan. Yuk, belanja sekarang!</p>
                <a href="{{ route('categories.index') }}" class="btn btn-primary start-shopping-btn">Mulai Belanja</a>
            </div>
        </div>
    </div>
    @else
    <!-- Cart with items -->
    <div class="container">
        <div class="cart-content">
            <h1 class="cart-title">Keranjang</h1>
            
            <div class="cart-layout">
                <!-- Cart Items Section -->
                <div class="cart-items-section">
                    <!-- Select All & Delete Options -->
                    <div class="cart-actions">
                        <div class="select-all">
                            <input type="checkbox" id="select-all" class="cart-checkbox">
                            <label for="select-all">Semua</label>
                        </div>
                        <button class="delete-selected-btn fas fa-trash" id="delete-selected">

                        </button>
                    </div>
                    
                    <!-- Store Section -->
                    <div class="store-section">
                        
                        <!-- Cart Items -->
                        <div class="cart-items">
                            @foreach($cartItems as $item)
                            <div class="cart-item" data-item-id="{{ $item->id }}">
                                <div class="item-checkbox">
                                    <input type="checkbox" class="cart-checkbox item-checkbox-input" 
                                           data-item-id="{{ $item->id }}">
                                </div>
                                
                                <div class="item-image">
                                    <img src="{{ $item->book->cover_url }}" 
                                         alt="{{ $item->book->name }}">
                                </div>
                                
                                <div class="item-details">
                                    <div class="item-format">{{ $item->book->category->name ?? 'Book' }}</div>
                                    <h3 class="item-title">{{ $item->book->name }}</h3>
                                    <div class="item-author">{{ $item->book->author }}</div>
                                    <div class="item-price">Rp{{ number_format($item->book->price, 0, ',', '.') }}</div>
                                </div>
                                
                                <div class="item-actions">
                                    <button class="delete-item-btn fas fa-trash" data-item-id="{{ $item->id }}">
                                    </button>
                                    
                                    <div class="quantity-controls">
                                        <button class="qty-btn minus" data-item-id="{{ $item->id }}" {{ $item->qty <= 1 ? 'disabled' : '' }}>-</button>
                                        <input type="text" class="qty-input" value="{{ $item->qty }}" 
                                               min="1" max="99" data-item-id="{{ $item->id }}" readonly>
                                        <button class="qty-btn plus" data-item-id="{{ $item->id }}">+</button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <!-- Cart Summary Section -->
                <div class="cart-summary-section">
                    <div class="cart-summary">
                        <h3>Ringkasan Keranjang</h3>
                        
                        <div class="summary-row">
                            <span>Total Harga ({{ $cartItems->count() }} Barang)</span>
                            <span class="total-price">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="summary-row">
                            <span>Diskon Belanja</span>
                            <span class="discount">-Rp0</span>
                        </div>
                        
                        <hr class="summary-divider">
                        
                        <div class="summary-row subtotal-row">
                            <span>Subtotal</span>
                            <span class="subtotal-price">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        
                        <button class="checkout-btn" id="checkout-btn">Checkout</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>