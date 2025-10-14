<x-app-layout>
    @push('styles')
        @vite(['resources/css/orders.css'])
    @endpush

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Pesanan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Success Alert -->
            @if(request('success'))
            <div class="alert-success">
                <div class="alert-success-content">
                    <svg class="alert-icon" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <h3 class="alert-title">Pesanan Berhasil Dibuat!</h3>
                        <p class="alert-message">Terima kasih atas pesanan Anda. Kami akan segera memproses pesanan Anda.</p>
                    </div>
                </div>
            </div>
            @endif

            @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                <p class="text-green-800">{{ session('success') }}</p>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <p class="text-red-800">{{ session('error') }}</p>
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($orders->isEmpty())
                        <div class="empty-state">
                            <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="empty-state-title">Belum ada pesanan</h3>
                            <p class="empty-state-message">Mulai belanja dan buat pesanan pertama Anda!</p>
                            <div class="empty-state-action">
                                <a href="{{ route('books.index') }}" class="btn-primary">
                                    Mulai Belanja
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($orders as $order)
                            <div class="order-card">
                                <div class="order-card-header">
                                    <div class="order-info">
                                        <h3>Order #{{ $order->id }}</h3>
                                        <p class="order-date">{{ $order->created_at->format('d M Y, H:i') }}</p>
                                    </div>
                                    <span class="status-badge status-{{ $order->status }}">
                                        {{ $order->status_label }}
                                    </span>
                                </div>

                                <!-- Items Preview -->
                                <div class="order-items-preview">
                                    @foreach($order->orderItems->take(2) as $item)
                                    <div class="order-item-preview">
                                        <img 
                                            src="{{ $item->book->cover_url }}" 
                                            alt="{{ $item->book->name }}"
                                            class="order-item-image"
                                            onerror="this.onerror=null; this.src='{{ asset('images/default-cover.svg') }}';"
                                        >
                                        <div class="order-item-info">
                                            <p class="order-item-name">{{ $item->book->name }}</p>
                                            <p class="order-item-details">{{ $item->qty }} x Rp{{ number_format($item->price, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                    @endforeach
                                    @if($order->orderItems->count() > 2)
                                    <p class="order-more-items">+{{ $order->orderItems->count() - 2 }} item lainnya</p>
                                    @endif
                                </div>

                                <div class="order-card-footer">
                                    <div>
                                        <span class="order-total-label">Total: </span>
                                        <span class="order-total-amount">Rp{{ number_format($order->total, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="order-actions">
                                        <a href="{{ route('orders.show', $order) }}" class="btn-secondary">
                                            Detail
                                        </a>
                                        @if($order->status === 'pending')
                                        <form action="{{ route('orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
                                            @csrf
                                            <button type="submit" class="btn-danger">
                                                Batalkan
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $orders->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
