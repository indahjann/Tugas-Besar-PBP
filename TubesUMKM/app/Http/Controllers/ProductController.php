<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\Models\Category;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Menampilkan halaman katalog publik dengan semua produk aktif.
     *
     * @return View
     */
    public function index(): View
    {
        return redirect()->route('categories.index');
    }

    /**
     * Menangani request pencarian produk melalui AJAX.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request)
    {
        $searchTerm = $request->input('q', $request->input('query', ''));
    $perPage = (int) $request->input('per_page', 12);
    $sort = $request->input('sort');

    $books = $this->productService->searchProducts($searchTerm, $perPage, $sort);

        if ($request->wantsJson() || str_contains($request->header('Accept', ''), 'application/json')) {
            return response()->json($books->map(function($p){
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'author' => $p->author,
                    'price' => $p->price,
                    'cover_url' => $p->cover_url,
                ];
            }));
        }

        // Jika bukan permintaan AJAX, ambil daftar kategori untuk sidebar (sama seperti di CategoriesController).
        // Untuk pencarian dengan AJAX, kita melewati query yang berat ini dan mengirimkan koleksi kosong karena
        // sidebar kategori disembunyikan pada hasil pencarian (`showSidebar => false`).
        $categories = collect();
        $userWishlist = [];
        if (! $request->ajax()) {
            $categories = Category::withCount('books')->orderBy('name')->get();

            // Get user's wishlist
            if (Auth::check()) {
                $userWishlist = Wishlist::where('user_id', Auth::id())->pluck('book_id')->toArray();
            }
        } else {
            // Untuk permintaan AJAX, kita tetap memuat wishlist jika pengguna sudah terautentikasi
            // karena kartu buku mungkin membutuhkannya.
            if (Auth::check()) {
                $userWishlist = Wishlist::where('user_id', Auth::id())->pluck('book_id')->toArray();
            }
        }

        // Jika permintaan AJAX, render fragmen utama dari tampilan kategori agar navbar loadPageContent
        // dapat mengambil elemen #main-content. Tampilan kategori mengharapkan variabel: categories, books, selectedCategory.
        $selectedCategory = null;

        if ($request->ajax()) {
            $html = view('Categories.main', [
                'categories' => $categories,
                'books' => $books,
                'selectedCategory' => $selectedCategory,
                'popularCategories' => $categories->sortByDesc('books_count')->take(5),
                'userWishlist' => $userWishlist,
                'showSidebar' => false,
            ])->render();

            return response($html, 200);
        }

        // Default: render full halaman kategori (blade wrapper)
        return view('categories', [
            'categories' => $categories,
            'books' => $books,
            'selectedCategory' => $selectedCategory,
            'popularCategories' => $categories->sortByDesc('books_count')->take(5),
            'userWishlist' => $userWishlist,
            'showSidebar' => false,
        ]);
    }

    /**
     * Provide search suggestions (simple titles) for navbar autocomplete.
     */
    public function suggestions(Request $request)
    {
        $q = $request->input('q', '');
        // Request a small paginated set and map the items for suggestions
        $booksPage = $this->productService->searchProducts($q, 8);
        $results = collect($booksPage->items())->map(function($p){
            return ['id' => $p->id, 'title' => $p->name, 'author' => $p->author];
        })->values();

        return response()->json($results);
    }
}