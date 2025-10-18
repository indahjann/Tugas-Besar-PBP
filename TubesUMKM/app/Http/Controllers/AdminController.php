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
     * Manage Books - Index
     */
    public function booksIndex()
    {
        $books = Book::with('category')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.books.index', compact('books'));
    }

    /**
     * Manage Books - Create Form
     */
    public function booksCreate()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.books.create', compact('categories'));
    }

    /**
     * Manage Books - Store
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

        // Handle cover image: priority to file upload, then URL
        if ($request->hasFile('cover_upload')) {
            $path = $request->file('cover_upload')->store('book-covers', 'public');
            $validated['cover_image'] = $path;
        } elseif (empty($validated['cover_image'])) {
            // Default placeholder if no cover provided
            $validated['cover_image'] = 'https://via.placeholder.com/200x300?text=No+Cover';
        }

        $validated['is_active'] = $request->boolean('is_active', true);
        
        // Remove cover_upload from validated data
        unset($validated['cover_upload']);

        Book::create($validated);

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil ditambahkan!');
    }

    /**
     * Manage Books - Edit Form
     */
    public function booksEdit(Book $book)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.books.edit', compact('book', 'categories'));
    }

    /**
     * Manage Books - Update
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

        // Handle cover image upload
        if ($request->hasFile('cover_upload')) {
            // Delete old image if it's a local file
            if ($book->cover_image && !filter_var($book->cover_image, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $path = $request->file('cover_upload')->store('book-covers', 'public');
            $validated['cover_image'] = $path;
        }

        $validated['is_active'] = $request->boolean('is_active', true);
        
        // Remove cover_upload from validated data
        unset($validated['cover_upload']);

        $book->update($validated);

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil diperbarui!');
    }

    /**
     * Manage Books - Delete
     */
    public function booksDestroy(Book $book)
    {
        // Delete cover image if exists
        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil dihapus!');
    }

    /**
     * Manage Orders - Index
     */
    public function ordersIndex(Request $request)
    {
        $query = Order::with(['user', 'orderItems.book']);

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Search by order number or customer name
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
     * Manage Orders - Show Detail
     */
    public function ordersShow(Order $order)
    {
        $order->load(['user', 'orderItems.book']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Manage Orders - Update Status
     */
    public function ordersUpdateStatus(Request $request, Order $order)
    {
        // Build allowed status list from Order model to keep in sync with DB
        $allowed = implode(',', array_keys(Order::getStatuses()));

        $validated = $request->validate([
            'status' => "required|in:$allowed",
        ]);

        $newStatus = $validated['status'];
        $currentStatus = $order->status;

        // Define status hierarchy (order level)
        $statusHierarchy = [
            'pending' => 1,
            'diproses' => 2,
            'dikirim' => 3,
            'selesai' => 4,
            'batal' => 0, // special case, only user can set this
        ];

        // Validation: Status must be sequential (cannot go backward)
        
        // Case 1: Cannot change from 'selesai' or 'batal'
        if (in_array($currentStatus, ['selesai', 'batal'])) {
            return redirect()->back()
                ->with('error', "Pesanan dengan status '{$order->status_label}' tidak dapat diubah lagi.");
        }

        // Case 2: Admin cannot set status to 'batal' - only user can cancel
        if ($newStatus === 'batal') {
            return redirect()->back()
                ->with('error', "Hanya pelanggan yang dapat membatalkan pesanan. Admin hanya dapat memproses pesanan ke depan.");
        }

        // Case 3: Cannot go backward (e.g., dikirim -> diproses)
        if ($statusHierarchy[$newStatus] <= $statusHierarchy[$currentStatus]) {
            return redirect()->back()
                ->with('error', "Status pesanan tidak dapat mundur. Status saat ini: '{$order->status_label}'.");
        }

        $order->update($validated);

        return redirect()->back()
            ->with('success', 'Status pesanan berhasil diperbarui!');
    }

    /**
     * Manage Categories - Index
     */
    public function categoriesIndex()
    {
        $categories = Category::withCount('books')->orderBy('name')->get();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Manage Categories - Store
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
     * Manage Categories - Update
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
     * Manage Categories - Delete
     */
    public function categoriesDestroy(Category $category)
    {
        // Check if category has books
        if ($category->books()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Kategori tidak bisa dihapus karena masih memiliki buku!');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }
}