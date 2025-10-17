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

    <!-- Filters & Search -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-col md:flex-row gap-4">
            <!-- Search -->
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Pesanan</label>
                <input type="text" 
                       name="search" 
                       id="search" 
                       value="{{ request('search') }}"
                       placeholder="ID pesanan, nama, atau email pelanggan..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <!-- Status Filter -->
            <div class="md:w-48">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" 
                        id="status" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Semua Status</option>
                    @foreach($statuses as $key => $label)
                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex items-end gap-2">
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                @if(request('search') || request('status'))
                    <a href="{{ route('admin.orders.index') }}" 
                       class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg font-medium transition">
                        <i class="fas fa-times mr-2"></i>Reset
                    </a>
                @endif
            </div>
        </form>
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

    <!-- Summary Stats -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-5 gap-4">
        @foreach($statuses as $key => $label)
            @php
                $count = \App\Models\Order::where('status', $key)->count();
                $statusColors = [
                    'pending' => 'border-yellow-400 bg-yellow-50',
                    'diproses' => 'border-blue-400 bg-blue-50',
                    'dikirim' => 'border-purple-400 bg-purple-50',
                    'selesai' => 'border-green-400 bg-green-50',
                    'batal' => 'border-red-400 bg-red-50',
                ];
                $color = $statusColors[$key] ?? 'border-gray-400 bg-gray-50';
            @endphp
            <div class="border-l-4 {{ $color }} p-4 rounded">
                <p class="text-xs font-medium text-gray-600 uppercase">{{ $label }}</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $count }}</p>
            </div>
        @endforeach
    </div>
</div>
@endsection
