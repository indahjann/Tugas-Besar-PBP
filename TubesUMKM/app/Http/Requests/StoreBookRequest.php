<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'author' => 'nullable|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'year' => 'nullable|digits:4|integer|min:1900|max:' . date('Y'),
            'isbn' => 'nullable|string|unique:books,isbn',
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'boolean',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama buku wajib diisi.',
            'name.max' => 'Nama buku maksimal 255 karakter.',
            'price.required' => 'Harga wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'price.min' => 'Harga tidak boleh kurang dari 0.',
            'stock.required' => 'Stok wajib diisi.',
            'stock.integer' => 'Stok harus berupa angka bulat.',
            'stock.min' => 'Stok tidak boleh kurang dari 0.',
            'author.max' => 'Nama penulis maksimal 255 karakter.',
            'publisher.max' => 'Nama penerbit maksimal 255 karakter.',
            'year.digits' => 'Tahun terbit harus 4 digit.',
            'year.min' => 'Tahun terbit minimal 1900.',
            'year.max' => 'Tahun terbit maksimal ' . date('Y') . '.',
            'isbn.unique' => 'ISBN sudah terdaftar. Silakan gunakan ISBN lain.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'cover_image.image' => 'File harus berupa gambar.',
            'cover_image.mimes' => 'Format gambar harus: jpeg, png, jpg, gif, atau webp.',
            'cover_image.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nama buku',
            'price' => 'harga',
            'stock' => 'stok',
            'description' => 'deskripsi',
            'author' => 'penulis',
            'publisher' => 'penerbit',
            'year' => 'tahun terbit',
            'isbn' => 'ISBN',
            'category_id' => 'kategori',
            'is_active' => 'status aktif',
            'cover_image' => 'gambar cover',
        ];
    }
}