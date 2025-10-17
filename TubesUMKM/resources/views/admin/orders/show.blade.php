@extends('layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('admin.orders.index') }}" 
           class="text-blue-600 hover:text-blue-800 font-medium mb-2 inline-block">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Pesanan
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Detail Pesanan #{{ $order->id }}</h1>
        <p class="mt-2 text-gray-600">{{ $order->created_at->format('d M Y, H:i') }}</p>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Item Pesanan</h2>
                
                <div class="space-y-4">
                    @foreach($order->orderItems as $item)
                    <div class="flex items-start space-x-4 pb-4 border-b border-gray-200 last:border-b-0">
                        <!-- Book Cover -->
                        <img src="{{ $item->book->cover_url ?? 'https://via.placeholder.com/100x150?text=No+Cover' }}" 
                             alt="{{ $item->book->name }}" 
                             class="w-16 h-24 object-cover rounded shadow-sm"
                             onerror="this.src='https://via.placeholder.com/100x150?text=No+Image'">
                        
                        <!-- Item Details -->
                        <div class="flex-1">
                            <h3 class="text-sm font-semibold text-gray-900">{{ $item->book->name }}</h3>
                            <p class="text-xs text-gray-600 mt-1">{{ $item->book->author }}</p>
                            <div class="flex items-center justify-between mt-2">
                                <div class="text-sm text-gray-700">
                                    <span class="font-medium">{{ $item->quantity }}x</span>
                                    <span class="ml-2">@ Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                </div>
                                <div class="text-sm font-semibold text-gray-900">
                                    Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Order Total -->
                <div class="mt-6 pt-4 border-t-2 border-gray-300">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold text-gray-900">Total Pesanan</span>
                        <span class="text-2xl font-bold text-blue-600">
                            Rp {{ number_format($order->total, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">
                    <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>Alamat Pengiriman
                </h2>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-700 whitespace-pre-line">{{ $order->address_text }}</p>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Customer Info -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">
                    <i class="fas fa-user mr-2"></i>Informasi Pelanggan
                </h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase">Nama</p>
                        <p class="text-sm text-gray-900 font-semibold">{{ $order->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase">Email</p>
                        <p class="text-sm text-gray-900">{{ $order->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase">Total Pesanan</p>
                        <p class="text-sm text-gray-900">
                            {{ \App\Models\Order::where('user_id', $order->user_id)->count() }} pesanan
                        </p>
                    </div>
                </div>
            </div>

            <!-- Order Status -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">
                    <i class="fas fa-info-circle mr-2"></i>Status Pesanan
                </h2>
                
                <!-- Current Status -->
                <div class="mb-4">
                    <p class="text-xs font-medium text-gray-500 uppercase mb-2">Status Saat Ini</p>
                    @php
                        $statusClass = match($order->status) {
                            'pending' => 'status-pending border-yellow-300',
                            'diproses' => 'status-diproses border-blue-300',
                            'dikirim' => 'status-dikirim border-purple-300',
                            'selesai' => 'status-selesai border-green-300',
                            'batal' => 'status-batal border-red-300',
                            default => 'bg-gray-100 text-gray-800 border-gray-300'
                        };
                    @endphp
                    <div class="px-4 py-3 border-2 {{ $statusClass }} rounded-lg font-semibold text-center">
                        {{ $order->status_label }}
                    </div>
                </div>

                <!-- Update Status Form -->
                <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <label for="status" class="block text-xs font-medium text-gray-500 uppercase mb-2">
                        Ubah Status
                    </label>
                    <select name="status" 
                            id="status" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent mb-3">
                        @foreach(\App\Models\Order::getStatuses() as $key => $label)
                            <option value="{{ $key }}" {{ $order->status == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" 
                            class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                        <i class="fas fa-save mr-2"></i>Update Status
                    </button>
                </form>

                <!-- Status Guide -->
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <p class="text-xs font-medium text-gray-500 uppercase mb-2">Panduan Status</p>
                    <ul class="text-xs text-gray-600 space-y-1">
                        <li><span class="font-semibold text-yellow-600">Menunggu:</span> Pesanan baru masuk</li>
                        <li><span class="font-semibold text-blue-600">Diproses:</span> Sedang dikemas</li>
                        <li><span class="font-semibold text-purple-600">Dikirim:</span> Dalam pengiriman</li>
                        <li><span class="font-semibold text-green-600">Selesai:</span> Pesanan diterima</li>
                        <li><span class="font-semibold text-red-600">Dibatalkan:</span> Pesanan batal</li>
                    </ul>
                </div>
            </div>

            <!-- Order Timeline -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">
                    <i class="fas fa-clock mr-2"></i>Riwayat
                </h2>
                <div class="space-y-3">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-2 h-2 bg-blue-500 rounded-full mt-1.5"></div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Pesanan Dibuat</p>
                            <p class="text-xs text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    @if($order->updated_at != $order->created_at)
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-1.5"></div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Terakhir Diupdate</p>
                            <p class="text-xs text-gray-500">{{ $order->updated_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
