<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddItemToCartRequest;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Menambahkan item ke dalam keranjang.
     *
     * @param AddItemToCartRequest $request
     * @return JsonResponse
     */
    public function addItem(AddItemToCartRequest $request): JsonResponse
    {
        try {
            $cartItem = $this->cartService->addItem(
                Auth::id(),
                $request->validated('product_id'),
                $request->validated('quantity')
            );

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang.',
                'data' => $cartItem
            ], 201);

        } catch (\Exception $e) {
            Log::error('Gagal menambahkan item ke keranjang: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422); // Unprocessable Entity
        }
    }
}