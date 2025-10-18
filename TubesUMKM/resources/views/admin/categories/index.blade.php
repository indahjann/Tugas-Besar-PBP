@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Kelola Kategori</h1>
            <p class="mt-2 text-gray-600">Daftar kategori produk buku</p>
        </div>
        <button onclick="openModal('add')" 
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition">
            <i class="fas fa-plus mr-2"></i>
            Tambah Kategori
        </button>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative alert-fade">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($categories as $category)
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
            <div class="p-6">
                <!-- Category Name -->
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-900">{{ $category->name }}</h3>
                        <p class="text-sm text-gray-600 mt-1">{{ $category->books_count }} buku</p>
                    </div>
                    <div class="flex-shrink-0 ml-3">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-tag text-2xl text-blue-600"></i>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-2 pt-4 border-t border-gray-200">
                    <button onclick='openModal("edit", {{ json_encode($category) }})' 
                            class="flex-1 px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-sm font-medium transition">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </button>
                    <form action="{{ route('admin.categories.destroy', $category) }}" 
                          method="POST" 
                          class="flex-1"
                          data-confirm="Apakah Anda yakin ingin menghapus kategori ini?{{ $category->books_count > 0 ? ' Kategori ini memiliki ' . $category->books_count . ' buku.' : '' }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full px-3 py-2 bg-red-50 hover:bg-red-100 text-red-700 rounded-lg text-sm font-medium transition"
                                {{ $category->books_count > 0 ? 'disabled' : '' }}>
                            <i class="fas fa-trash mr-1"></i> Hapus
                        </button>
                    </form>
                </div>

                @if($category->books_count > 0)
                <p class="text-xs text-gray-500 mt-2 text-center">
                    <i class="fas fa-info-circle"></i> Tidak bisa dihapus, masih ada buku
                </p>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-lg shadow-md p-12">
            <div class="text-center">
                <i class="fas fa-tags text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg mb-4">Belum ada kategori</p>
                <button onclick="openModal('add')" 
                        class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Kategori Pertama
                </button>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Summary Stats -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full mr-4">
                    <i class="fas fa-tags text-2xl text-blue-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Kategori</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $categories->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full mr-4">
                    <i class="fas fa-book text-2xl text-green-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Buku</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $categories->sum('books_count') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-full mr-4">
                    <i class="fas fa-chart-bar text-2xl text-yellow-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Rata-rata per Kategori</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $categories->count() > 0 ? number_format($categories->sum('books_count') / $categories->count(), 1) : 0 }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add/Edit Category -->
<div id="categoryModal" 
     class="category-modal hidden"
     onclick="handleModalBackdropClick(event)">
    <div class="flex items-center justify-center min-h-screen px-4 py-6">
        <div class="category-modal-content" onclick="event.stopPropagation()">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 id="modalTitle" class="text-xl font-bold text-gray-900">Tambah Kategori</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <form id="categoryForm" 
                  method="POST" 
                  action="{{ route('admin.categories.store') }}"
                  data-store-url="{{ route('admin.categories.store') }}"
                  data-update-url="{{ route('admin.categories.update', '__ID__') }}"
                  class="p-6">
                @csrf
                <input type="hidden" id="methodField" name="_method" value="">
                
                <!-- Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Kategori <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Contoh: Fiksi, Non-Fiksi, Komik">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex gap-3">
                    <button type="button" 
                            onclick="closeModal()" 
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition">
                        Batal
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                        <i class="fas fa-save mr-2"></i>
                        <span id="submitText">Simpan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('head')
@vite(['resources/css/admin-categories.css'])
@endpush

@push('scripts')
@vite(['resources/js/admin-categories.js'])
@endpush
