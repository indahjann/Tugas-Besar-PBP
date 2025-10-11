<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddItemToCartRequest;
use App\Http\Requests\UpdateCartRequest;
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
        $validatedData = $request->validated();
        $userId = Auth::id();

        // Pengecekan tambahan untuk memastikan user terotentikasi (Defensive Programming)
        if (!$userId) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {
            $cartItem = $this->cartService->addItem(
                $userId,
                $validatedData['product_id'],
                $validatedData['quantity']
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
            ], 422);
        }
    }

    /**
     * Mengubah item di dalam keranjang.
     *
     * @param UpdateCartRequest $request
     * @param int $cartItemId
     * @return JsonResponse
     */
    public function updateItem(UpdateCartRequest $request, int $cartItemId): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $cartItem = $this->cartService->updateItem(
                $cartItemId,
                $validatedData['quantity']
            );

            return response()->json([
                'success' => true,
                'message' => 'Jumlah produk berhasil diperbarui.',
                'data' => $cartItem
            ]);

        } catch (\Exception $e) {
            Log::error('Gagal memperbarui item keranjang: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Menghapus item dari keranjang.
     * ... (Metode removeItem tidak perlu diubah karena tidak menggunakan $request->validated()) ...
     */
    public function removeItem(int $cartItemId): JsonResponse
    {
        // ... (kode tetap sama)
        try {
            $this->cartService->removeItem($cartItemId);

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus dari keranjang.'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Gagal menghapus item keranjang: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus produk dari keranjang.'
            ], 500);
        }
    }
}