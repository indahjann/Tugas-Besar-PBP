<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddItemToCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Services\CartService;
use App\Models\Cart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
     * Menampilkan halaman keranjang belanja.
     */
    public function index()
    {
        $userId = Auth::id();
        
        if (!$userId) {
            return redirect()->route('login');
        }

        $cart = Cart::where('user_id', $userId)
                   ->with(['items.book.category'])
                   ->first();

        $cartItems = $cart ? $cart->items : collect();
        
        // Hitung total harga
        $subtotal = $cartItems->sum(function ($item) {
            return $item->book->price * $item->qty;
        });

        return view('cart', compact('cartItems', 'subtotal'));
    }

    /**
     * Mendapatkan data keranjang untuk AJAX.
     */
    public function getCartData(): JsonResponse
    {
        $userId = Auth::id();
        
        if (!$userId) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $cart = Cart::where('user_id', $userId)
                   ->with(['items.book.category'])
                   ->first();

        $cartItems = $cart ? $cart->items : collect();
        
        $subtotal = $cartItems->sum(function ($item) {
            return $item->book->price * $item->qty;
        });

        return response()->json([
            'success' => true,
            'data' => [
                'items' => $cartItems,
                'subtotal' => $subtotal,
                'count' => $cartItems->sum('qty')
            ]
        ]);
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

        // Pengecekan tambahan untuk memastikan user terotentikasi
        if (!$userId) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {
            $cartItem = $this->cartService->addItem(
                $userId,
                $validatedData['product_id'],
                $validatedData['quantity']
            );

            // Get updated cart count
            $cart = Cart::where('user_id', $userId)->with('items')->first();
            $cartCount = $cart ? $cart->items->sum('qty') : 0;

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang.',
                'data' => $cartItem,
                'cart_count' => $cartCount
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
        $userId = Auth::id();

        if (!$userId) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {
            $validatedData = $request->validated();
            $cartItem = $this->cartService->updateItem(
                $cartItemId,
                $validatedData['quantity'],
                $userId
            );

            // Get updated cart data
            $cart = Cart::where('user_id', $userId)->with('items')->first();
            $cartCount = $cart ? $cart->items->sum('qty') : 0;

            $subtotal = $cart->items->sum(function ($item) {
                return $item->book->price * $item->qty;
            });

            return response()->json([
                'success' => true,
                'message' => 'Jumlah produk berhasil diperbarui.',
                'data' => $cartItem,
                'cart_count' => $cartCount,
                'subtotal' => $subtotal
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
     * 
     * @param int $cartItemId
     * @return JsonResponse
     */
    public function removeItem(int $cartItemId): JsonResponse
    {
        $userId = Auth::id();

        if (!$userId) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {
            $this->cartService->removeItem($cartItemId, $userId);

            // Get update cart count
            $cart = Cart::where('user_id', $userId)->with('items')->first();
            $cartCount = $cart ? $cart->items->sum('qty') : 0;

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus dari keranjang.',
                'cart_count' => $cartCount
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