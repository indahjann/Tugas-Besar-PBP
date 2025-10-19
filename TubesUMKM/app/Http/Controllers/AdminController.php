<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Dashboard admin
     */
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    /**
     * Manajemen buku: indeks
     */
    public function booksIndex()
    {
        $books = Book::with('category')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.books.index', compact('books'));
    }

    /**
     * Manajemen buku: buat form
     */
    public function booksCreate()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.books.create', compact('categories'));
    }

    /**
     * Manajemen buku: store
     */
    public function booksStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'author' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'year' => 'nullable|digits:4|integer|min:1900|max:' . date('Y'),
            'isbn' => 'nullable|string|unique:books,isbn',
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'boolean',
            'cover_image' => 'nullable|string',
            'cover_upload' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Handle cover image: prioritaskan file upload, baru URL
        if ($request->hasFile('cover_upload')) {
            $path = $request->file('cover_upload')->store('book-covers', 'public');
            $validated['cover_image'] = $path;
        } elseif (empty($validated['cover_image'])) {
            // Default jika tidak ada cover
            $validated['cover_image'] = 'https://via.placeholder.com/200x300?text=No+Cover';
        }

        $validated['is_active'] = $request->boolean('is_active', true);
        
        unset($validated['cover_upload']);

        Book::create($validated);

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil ditambahkan!');
    }

    /**
     * Manajemen buku: form edit
     */
    public function booksEdit(Book $book)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.books.edit', compact('book', 'categories'));
    }

    /**
     * Manajemen buku: update
     */
    public function booksUpdate(Request $request, Book $book)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'author' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'year' => 'nullable|digits:4|integer|min:1900|max:' . date('Y'),
            'isbn' => 'nullable|string|unique:books,isbn,' . $book->id,
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'boolean',
            'cover_image' => 'nullable|string',
            'cover_upload' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Handle upload cover image
        if ($request->hasFile('cover_upload')) {
            // Delete old image jika file lokal
            if ($book->cover_image && !filter_var($book->cover_image, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $path = $request->file('cover_upload')->store('book-covers', 'public');
            $validated['cover_image'] = $path;
        }

        $validated['is_active'] = $request->boolean('is_active', true);
        
        unset($validated['cover_upload']);

        $book->update($validated);

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil diperbarui!');
    }

    /**
     * Manajemen buku: delete
     */
    public function booksDestroy(Book $book)
    {
        // Delete cover image jika ada
        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil dihapus!');
    }

    /**
     * Manajemen order: indeks
     */
    public function ordersIndex(Request $request)
    {
        $query = Order::with(['user', 'orderItems.book']);

        // Filter berdasarkan status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Search berdasarkan order number atau customer name
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->latest()->paginate(15);
        $statuses = Order::getStatuses();

        return view('admin.orders.index', compact('orders', 'statuses'));
    }

    /**
     * Manajemen order: show detail
     */
    public function ordersShow(Order $order)
    {
        $order->load(['user', 'orderItems.book']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Manajemen order: update status
     */
    public function ordersUpdateStatus(Request $request, Order $order)
    {
        $allowed = implode(',', array_keys(Order::getStatuses()));

        $validated = $request->validate([
            'status' => "required|in:$allowed",
        ]);

        $newStatus = $validated['status'];
        $currentStatus = $order->status;

        // Hirarki status (order level)
        $statusHierarchy = [
            'pending' => 1,
            'diproses' => 2,
            'dikirim' => 3,
            'selesai' => 4,
            'batal' => 0, // special case, hanya bisa diset oleh user
        ];

        // Validasi: status harus sekuensial
        
        // Case 1: tidak bisa ganti dari 'selesai' atau 'batal'
        if (in_array($currentStatus, ['selesai', 'batal'])) {
            return redirect()->back()
                ->with('error', "Pesanan dengan status '{$order->status_label}' tidak dapat diubah lagi.");
        }

        // Case 2: admin tidak bisa set status ke 'batal' - hanya user yang bisa cancel
        if ($newStatus === 'batal') {
            return redirect()->back()
                ->with('error', "Hanya pelanggan yang dapat membatalkan pesanan. Admin hanya dapat memproses pesanan ke depan.");
        }

        // Case 3: tidak boleh mundur (contoh: dikirim -> diproses)
        if ($statusHierarchy[$newStatus] <= $statusHierarchy[$currentStatus]) {
            return redirect()->back()
                ->with('error', "Status pesanan tidak dapat mundur. Status saat ini: '{$order->status_label}'.");
        }

        $order->update($validated);

        return redirect()->back()
            ->with('success', 'Status pesanan berhasil diperbarui!');
    }

    /**
     * Manajemen kategori: indeks
     */
    public function categoriesIndex()
    {
        $categories = Category::withCount('books')->orderBy('name')->get();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Manajemen kategori: store
     */
    public function categoriesStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    /**
     * Manajemen kategori: update
     */
    public function categoriesUpdate(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Manajemen kategori: delete
     */
    public function categoriesDestroy(Category $category)
    {
        // Cek jika ada buku di kategori ini
        if ($category->books()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Kategori tidak bisa dihapus karena masih memiliki buku!');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }
}