@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('admin.books.index') }}" 
           class="text-blue-600 hover:text-blue-800 font-medium mb-2 inline-block">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Produk
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Edit Produk</h1>
        <p class="mt-2 text-gray-600">Perbarui informasi produk buku</p>
    </div>

    <!-- Error Messages -->
    @if($errors->any())
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <p class="font-bold">Terdapat kesalahan:</p>
            <ul class="list-disc list-inside mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form -->
    <form action="{{ route('admin.books.update', $book) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-md p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Current Cover Preview -->
            @if($book->cover_image)
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Cover Saat Ini</label>
                <img src="{{ $book->cover_url }}" 
                     alt="{{ $book->name }}" 
                     class="h-40 w-auto object-cover rounded shadow-md"
                     onerror="this.src='https://via.placeholder.com/200x300?text=No+Image'">
            </div>
            @endif

            <!-- Nama Buku -->
            <div class="md:col-span-2">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Buku <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="name" 
                       id="name" 
                       value="{{ old('name', $book->name) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       required>
            </div>

            <!-- Penulis -->
            <div>
                <label for="author" class="block text-sm font-medium text-gray-700 mb-2">
                    Penulis <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="author" 
                       id="author" 
                       value="{{ old('author', $book->author) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       required>
            </div>

            <!-- Penerbit -->
            <div>
                <label for="publisher" class="block text-sm font-medium text-gray-700 mb-2">
                    Penerbit <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="publisher" 
                       id="publisher" 
                       value="{{ old('publisher', $book->publisher) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       required>
            </div>

            <!-- Kategori -->
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Kategori <span class="text-red-500">*</span>
                </label>
                <select name="category_id" 
                        id="category_id" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required>
                    <option value="">Pilih Kategori</option>
                    @foreach(\App\Models\Category::orderBy('name')->get() as $category)
                        <option value="{{ $category->id }}" 
                                {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Harga -->
            <div>
                <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                    Harga (Rp) <span class="text-red-500">*</span>
                </label>
                <input type="number" 
                       name="price" 
                       id="price" 
                       value="{{ old('price', $book->price) }}"
                       min="0"
                       step="1000"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       required>
            </div>

            <!-- Stok -->
            <div>
                <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">
                    Stok <span class="text-red-500">*</span>
                </label>
                <input type="number" 
                       name="stock" 
                       id="stock" 
                       value="{{ old('stock', $book->stock) }}"
                       min="0"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       required>
            </div>

            <!-- Status Aktif -->
            <div>
                <label for="is_active" class="block text-sm font-medium text-gray-700 mb-2">
                    Status
                </label>
                <select name="is_active" 
                        id="is_active" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="1" {{ old('is_active', $book->is_active) == 1 ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('is_active', $book->is_active) == 0 ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <!-- Cover Image -->
            <div class="md:col-span-2">
                <label for="cover_image" class="block text-sm font-medium text-gray-700 mb-2">
                    Cover Buku (URL atau Upload Baru)
                </label>
                <input type="text" 
                       name="cover_image" 
                       id="cover_image" 
                       value="{{ old('cover_image', $book->cover_image) }}"
                       placeholder="https://example.com/cover.jpg"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent mb-2">
                <input type="file" 
                       name="cover_upload" 
                       id="cover_upload" 
                       accept="image/*"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah cover</p>
            </div>

            <!-- Deskripsi -->
            <div class="md:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Deskripsi
                </label>
                <textarea name="description" 
                          id="description" 
                          rows="4"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description', $book->description) }}</textarea>
            </div>
        </div>

        <!-- Buttons -->
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.books.index') }}" 
               class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition">
                Batal
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                <i class="fas fa-save mr-2"></i>Update Produk
            </button>
        </div>
    </form>
</div>
@endsection
