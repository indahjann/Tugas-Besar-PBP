@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Kelola Pesanan</h1>
        <p class="mt-2 text-gray-600">Daftar semua pesanan dari pelanggan</p>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Search Bar -->
    <div class="mb-4">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex gap-3">
            <input type="hidden" name="status" value="{{ request('status') }}">
            
            <div class="flex-1">
                <input type="text" 
                       name="search" 
                       id="search" 
                       value="{{ request('search') }}"
                       placeholder="🔍 Cari ID pesanan, nama, atau email pelanggan..."
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
            </div>

            <button type="submit" 
                    class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition text-sm">
                <i class="fas fa-search mr-2"></i>Cari
            </button>
            
            @if(request('search'))
                <a href="{{ route('admin.orders.index', ['status' => request('status')]) }}" 
                   class="px-6 py-2.5 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg font-medium transition text-sm">
                    <i class="fas fa-times mr-2"></i>Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Status Tabs -->
    <div class="bg-white rounded-lg shadow-md mb-6 overflow-hidden">
        <div class="flex border-b border-gray-200">
            @php
                $currentStatus = request('status', '');
                $statusCounts = [
                    '' => \App\Models\Order::count(),
                    'pending' => \App\Models\Order::where('status', 'pending')->count(),
                    'diproses' => \App\Models\Order::where('status', 'diproses')->count(),
                    'dikirim' => \App\Models\Order::where('status', 'dikirim')->count(),
                    'selesai' => \App\Models\Order::where('status', 'selesai')->count(),
                    'batal' => \App\Models\Order::where('status', 'batal')->count(),
                ];
            @endphp
            
            <!-- Tab: Semua -->
            <a href="{{ route('admin.orders.index') }}" 
               class="flex-1 px-6 py-4 text-center text-sm font-medium border-b-2 transition-colors
                      {{ $currentStatus === '' 
                         ? 'border-blue-500 text-blue-600 bg-blue-50' 
                         : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50' }}">
                <div class="flex items-center justify-center gap-2">
                    <span>Semua</span>
                    <span class="px-2.5 py-0.5 text-xs rounded-full font-semibold
                                 {{ $currentStatus === '' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
                        {{ $statusCounts[''] }}
                    </span>
                </div>
            </a>

            <!-- Tab: Menunggu -->
            <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" 
               class="flex-1 px-6 py-4 text-center text-sm font-medium border-b-2 transition-colors
                      {{ $currentStatus === 'pending' 
                         ? 'border-yellow-500 text-yellow-600 bg-yellow-50' 
                         : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50' }}">
                <div class="flex items-center justify-center gap-2">
                    <i class="fas fa-clock"></i>
                    <span>Menunggu</span>
                    <span class="px-2.5 py-0.5 text-xs rounded-full font-semibold
                                 {{ $currentStatus === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-600' }}">
                        {{ $statusCounts['pending'] }}
                    </span>
                </div>
            </a>

            <!-- Tab: Diproses -->
            <a href="{{ route('admin.orders.index', ['status' => 'diproses']) }}" 
               class="flex-1 px-6 py-4 text-center text-sm font-medium border-b-2 transition-colors
                      {{ $currentStatus === 'diproses' 
                         ? 'border-blue-500 text-blue-600 bg-blue-50' 
                         : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50' }}">
                <div class="flex items-center justify-center gap-2">
                    <i class="fas fa-box"></i>
                    <span>Diproses</span>
                    <span class="px-2.5 py-0.5 text-xs rounded-full font-semibold
                                 {{ $currentStatus === 'diproses' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
                        {{ $statusCounts['diproses'] }}
                    </span>
                </div>
            </a>

            <!-- Tab: Dikirim -->
            <a href="{{ route('admin.orders.index', ['status' => 'dikirim']) }}" 
               class="flex-1 px-6 py-4 text-center text-sm font-medium border-b-2 transition-colors
                      {{ $currentStatus === 'dikirim' 
                         ? 'border-purple-500 text-purple-600 bg-purple-50' 
                         : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50' }}">
                <div class="flex items-center justify-center gap-2">
                    <i class="fas fa-shipping-fast"></i>
                    <span>Dikirim</span>
                    <span class="px-2.5 py-0.5 text-xs rounded-full font-semibold
                                 {{ $currentStatus === 'dikirim' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-600' }}">
                        {{ $statusCounts['dikirim'] }}
                    </span>
                </div>
            </a>

            <!-- Tab: Selesai -->
            <a href="{{ route('admin.orders.index', ['status' => 'selesai']) }}" 
               class="flex-1 px-6 py-4 text-center text-sm font-medium border-b-2 transition-colors
                      {{ $currentStatus === 'selesai' 
                         ? 'border-green-500 text-green-600 bg-green-50' 
                         : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50' }}">
                <div class="flex items-center justify-center gap-2">
                    <i class="fas fa-check-circle"></i>
                    <span>Selesai</span>
                    <span class="px-2.5 py-0.5 text-xs rounded-full font-semibold
                                 {{ $currentStatus === 'selesai' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                        {{ $statusCounts['selesai'] }}
                    </span>
                </div>
            </a>

            <!-- Tab: Dibatalkan -->
            <a href="{{ route('admin.orders.index', ['status' => 'batal']) }}" 
               class="flex-1 px-6 py-4 text-center text-sm font-medium border-b-2 transition-colors
                      {{ $currentStatus === 'batal' 
                         ? 'border-red-500 text-red-600 bg-red-50' 
                         : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50' }}">
                <div class="flex items-center justify-center gap-2">
                    <i class="fas fa-times-circle"></i>
                    <span>Dibatalkan</span>
                    <span class="px-2.5 py-0.5 text-xs rounded-full font-semibold
                                 {{ $currentStatus === 'batal' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-600' }}">
                        {{ $statusCounts['batal'] }}
                    </span>
                </div>
            </a>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            #{{ $order->id }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $order->user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $order->user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $order->created_at->format('d M Y') }}
                            <div class="text-xs text-gray-500">{{ $order->created_at->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                            Rp {{ number_format($order->total, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusClass = match($order->status) {
                                    'pending' => 'status-pending',
                                    'diproses' => 'status-diproses',
                                    'dikirim' => 'status-dikirim',
                                    'selesai' => 'status-selesai',
                                    'batal' => 'status-batal',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                            @endphp
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $order->orderItems->count() }} item(s)
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.orders.show', $order) }}" 
                               class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-shopping-bag text-6xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500 text-lg">
                                    @if(request('search') || request('status'))
                                        Tidak ada pesanan yang sesuai dengan filter
                                    @else
                                        Belum ada pesanan
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $orders->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
