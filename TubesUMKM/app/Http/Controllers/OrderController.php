<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display user's order history
     */
    public function index()
    {
        $userId = Auth::id();
        
        if (!$userId) {
            return redirect()->route('login');
        }

        $orders = Order::where('user_id', $userId)
            ->with(['orderItems.book'])
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Show order detail
     */
    public function show(Order $order)
    {
        $userId = Auth::id();
        
        // Validasi ownership
        if ($order->user_id !== $userId) {
            abort(403, 'Unauthorized action.');
        }

        $order->load(['orderItems.book.category']);

        return view('orders.show', compact('order'));
    }

    /**
     * Cancel order (only if status is pending)
     */
    public function cancel(Order $order)
    {
        $userId = Auth::id();
        
        // Validasi ownership
        if ($order->user_id !== $userId) {
            abort(403, 'Unauthorized action.');
        }

        // Hanya bisa cancel jika status masih pending
        if ($order->status !== Order::STATUS_PENDING) {
            return redirect()->back()
                ->with('error', 'Pesanan tidak dapat dibatalkan karena sudah diproses.');
        }

        // Update status ke cancelled
        $order->update(['status' => Order::STATUS_CANCELLED]);

        // Kembalikan stok
        foreach ($order->orderItems as $item) {
            $book = $item->book;
            if ($book) {
                $book->stock += $item->qty;
                $book->save();
            }
        }

        return redirect()->route('orders.index')
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }
}