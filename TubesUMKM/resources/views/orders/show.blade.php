<x-app-layout>
    @push('styles')
        @vite(['resources/css/orders.css'])
    @endpush

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Pesanan #{{ $order->id }}
            </h2>
            <a href="{{ route('orders.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                ← Kembali ke Riwayat
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="detail-grid">
                
                <!-- Detail Items -->
                <div>
                    <div class="detail-section">
                        <h3 class="detail-section-title">Daftar Item</h3>
                        
                        <div>
                            @foreach($order->orderItems as $item)
                            <div class="detail-item">
                                <img 
                                    src="{{ $item->book->cover_url }}" 
                                    alt="{{ $item->book->name }}"
                                    class="detail-item-image"
                                    onerror="this.onerror=null; this.src='{{ asset('images/default-cover.svg') }}';"
                                >
                                <div class="detail-item-content">
                                    <h4 class="detail-item-title">{{ $item->book->name }}</h4>
                                    <p class="detail-item-author">{{ $item->book->author }}</p>
                                    <p class="detail-item-category">{{ $item->book->category->name ?? 'Uncategorized' }}</p>
                                    <div class="detail-item-quantity">
                                        <span>Qty: {{ $item->qty }}</span>
                                        <span>×</span>
                                        <span>Rp{{ number_format($item->price, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                <div class="detail-item-price">
                                    <p class="detail-item-price-amount">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Informasi Pengiriman -->
                    <div class="detail-section" style="margin-top: 1.5rem;">
                        <h3 class="detail-section-title">Alamat Pengiriman</h3>
                        <div class="shipping-address">{{ $order->address_text }}</div>
                    </div>
                </div>

                <!-- Ringkasan & Status -->
                <div>
                    <!-- Status -->
                    <div class="detail-section" style="margin-bottom: 1.5rem;">
                        <h3 class="detail-section-title">Status Pesanan</h3>
                        
                        <div style="margin-bottom: 1rem;">
                            <span class="status-badge status-{{ $order->status }}">
                                {{ $order->status_label }}
                            </span>
                        </div>

                        <p style="font-size: 0.875rem; color: #6b7280; margin-bottom: 1rem;">
                            Dipesan pada: <span style="font-weight: 500;">{{ $order->created_at->format('d M Y, H:i') }}</span>
                        </p>

                        @if($order->status === 'pending')
                        <form action="{{ route('orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini? Stok akan dikembalikan.')">
                            @csrf
                            <button type="submit" class="btn-danger" style="width: 100%;">
                                Batalkan Pesanan
                            </button>
                        </form>
                        @endif
                    </div>

                    <!-- Ringkasan Pembayaran -->
                    <div class="detail-section sticky-summary">
                        <h3 class="detail-section-title">Ringkasan Pembayaran</h3>
                        
                        <div>
                            <div class="summary-row">
                                <span class="summary-label">Subtotal ({{ $order->orderItems->count() }} item)</span>
                                <span class="summary-value">Rp{{ number_format($order->total, 0, ',', '.') }}</span>
                            </div>
                            <div class="summary-row">
                                <span class="summary-label">Ongkos Kirim</span>
                                <span class="summary-value" style="color: #16a34a;">Gratis</span>
                            </div>
                            <div class="summary-row summary-total">
                                <span class="summary-label">Total</span>
                                <span class="summary-value">Rp{{ number_format($order->total, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <!-- Timeline Status -->
                        <div class="progress-timeline">
                            <h4 class="progress-timeline-title">Progres Pesanan</h4>
                            <div>
                                <div class="timeline-item">
                                    <div class="timeline-dot timeline-dot-active"></div>
                                    <div class="timeline-content">
                                        <p class="timeline-label">Pesanan Dibuat</p>
                                        <p class="timeline-date">{{ $order->created_at->format('d M Y, H:i') }}</p>
                                    </div>
                                </div>
                                
                                @if($order->status !== 'pending' && $order->status !== 'batal')
                                <div class="timeline-item">
                                    <div class="timeline-dot {{ in_array($order->status, ['diproses', 'dikirim', 'selesai']) ? 'timeline-dot-active' : 'timeline-dot-inactive' }}"></div>
                                    <div class="timeline-content">
                                        <p class="timeline-label">Sedang Diproses</p>
                                    </div>
                                </div>
                                @endif

                                @if(in_array($order->status, ['dikirim', 'selesai']))
                                <div class="timeline-item">
                                    <div class="timeline-dot timeline-dot-active"></div>
                                    <div class="timeline-content">
                                        <p class="timeline-label">Dalam Pengiriman</p>
                                    </div>
                                </div>
                                @endif

                                @if($order->status === 'selesai')
                                <div class="timeline-item">
                                    <div class="timeline-dot timeline-dot-active"></div>
                                    <div class="timeline-content">
                                        <p class="timeline-label">Selesai</p>
                                    </div>
                                </div>
                                @endif

                                @if($order->status === 'batal')
                                <div class="timeline-item">
                                    <div class="timeline-dot" style="background-color: #ef4444;"></div>
                                    <div class="timeline-content">
                                        <p class="timeline-label timeline-label-cancelled">Dibatalkan</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
