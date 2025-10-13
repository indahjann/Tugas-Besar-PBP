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
        // Redirect to categories index to reuse the canonical catalog UI.
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

    // Use service to get paginated books so categories view can be reused
    $books = $this->productService->searchProducts($searchTerm, $perPage, $sort);

        // If the client explicitly wants JSON (Accept: application/json), return JSON
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

        // If not an AJAX request, gather categories for sidebar (same as CategoriesController).
        // For AJAX search we skip this expensive query and pass empty collections because
        // the categories sidebar is hidden for search results (`showSidebar => false`).
        $categories = collect();
        $userWishlist = [];
        if (! $request->ajax()) {
            $categories = Category::withCount('books')->orderBy('name')->get();

            // Get user's wishlist (for rendering book card state)
            if (Auth::check()) {
                $userWishlist = Wishlist::where('user_id', Auth::id())->pluck('book_id')->toArray();
            }
        } else {
            // For AJAX we still load wishlist if authenticated because the book card may need it
            if (Auth::check()) {
                $userWishlist = Wishlist::where('user_id', Auth::id())->pluck('book_id')->toArray();
            }
        }

        // If AJAX request, render the main fragment from categories view so navbar loadPageContent
        // can extract #main-content. The categories view expects variables: categories, books, selectedCategory.
        $selectedCategory = null;

        if ($request->ajax()) {
            // Render the categories main partial (Categories.main) which is used by resources/views/categories.blade.php
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

        // Default: render full categories page (blade wrapper)
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