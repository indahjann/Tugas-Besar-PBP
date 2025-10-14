<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Services\CheckoutService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    protected $checkoutService;

    public function __construct(CheckoutService $checkoutService)
    {
        $this->checkoutService = $checkoutService;
    }

    /**
     * Tampilkan halaman checkout
     */
    public function index()
    {
        $userId = Auth::id();
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Ambil cart user dengan items dan book details
        $cart = Auth::user()->cart()->with('items.book.category')->first();

        // Redirect jika cart kosong
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        // Hitung total
        $subtotal = $cart->items->sum(function ($item) {
            return $item->book->price * $item->qty;
        });

        return view('checkout.index', compact('cart', 'subtotal'));
    }

    /**
     * Menyimpan pesanan baru.
     *
     * @param StoreOrderRequest $request
     * @return JsonResponse
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {
            $order = $this->checkoutService->processCheckout($userId, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Checkout berhasil! Pesanan Anda sedang diproses.',
                'data' => $order
            ], 201);

        } catch (\Exception $e) {
            Log::error('Gagal saat checkout: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}