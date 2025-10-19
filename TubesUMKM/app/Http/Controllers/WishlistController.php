<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WishlistController extends Controller
{
    /**
     * Ambil wishlist untuk pengguna saat ini.
     */
    public function index()
    {
        $userId = Auth::id();
        
        if (!$userId) {
            return redirect()->route('login');
        }

        try {
            // Load wishlist dengan eager loading dan filter only existing books
            $wishlistItems = Wishlist::with(['book.category'])
                ->where('user_id', $userId)
                ->whereHas('book') // Filter hanya wishlist yang book-nya masih ada
                ->latest()
                ->get();

            return view('wishlist', compact('wishlistItems'));
        } catch (\Exception $e) {
            Log::error('Wishlist Index Error: ' . $e->getMessage());
            
            $wishlistItems = collect();
            return view('wishlist', compact('wishlistItems'));
        }
    }

    /**
     * Toggle wishlist item (add/remove)
     */
    public function toggle(Request $request): JsonResponse
    {
        // Validate dulu sebelum get user_id
        $request->validate([
            'book_id' => 'required|exists:books,id'
        ]);

        $userId = Auth::id();

        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $bookId = $request->input('book_id');

        try {
            // Cek apakah sudah ada di wishlist
            $wishlistItem = Wishlist::where('user_id', $userId)
                ->where('book_id', $bookId)
                ->first();

            if ($wishlistItem) {
                $wishlistItem->delete();
                $wishlistCount = Wishlist::where('user_id', $userId)->count();
                
                return response()->json([
                    'success' => true,
                    'action' => 'removed',
                    'message' => 'Buku dihapus dari wishlist',
                    'wishlist_count' => $wishlistCount
                ]);
            } else {
                Wishlist::create([
                    'user_id' => $userId,
                    'book_id' => $bookId
                ]);
                
                $wishlistCount = Wishlist::where('user_id', $userId)->count();
                
                return response()->json([
                    'success' => true,
                    'action' => 'added',
                    'message' => 'Buku ditambahkan ke wishlist',
                    'wishlist_count' => $wishlistCount
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Wishlist Toggle Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate wishlist: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get wishlist count untuk pengguna saat ini
     */
    public function count(): JsonResponse
    {
        $userId = Auth::id();
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'count' => 0
            ], 401);
        }

        try {
            $count = Wishlist::where('user_id', $userId)->count();
            
            return response()->json([
                'success' => true,
                'count' => $count
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'count' => 0
            ], 500);
        }
    }

    /**
     * Clear all wishlist items untuk pengguna saat ini
     */
    public function clear(): JsonResponse
    {
        $userId = Auth::id();
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        try {
            Wishlist::where('user_id', $userId)->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Wishlist berhasil dikosongkan'
            ]);
        } catch (\Exception $e) {
            Log::error('Wishlist Clear Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengosongkan wishlist'
            ], 500);
        }
    }
}