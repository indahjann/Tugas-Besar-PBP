<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

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
        $products = $this->productService->getActiveProducts();
        
        // 'products.index' sesuai dengan path view: resources/views/products/index.blade.php
        return view('products.index', compact('products'));
    }

    /**
     * Menangani request pencarian produk melalui AJAX.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $searchTerm = $request->input('query', '');
        $products = $this->productService->searchProducts($searchTerm);

        return response()->json($products);
    }
}