<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Toggle wishlist item (add/remove)
     */
    public function toggle(Request $request): JsonResponse
    {
        $userId = Auth::id();
        $bookId = $request->input('book_id');

        if (!$userId) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'book_id' => 'required|exists:books,id'
        ]);

        try {
            // Check if item already exists in wishlist
            $wishlistItem = Wishlist::where('user_id', $userId)
                                  ->where('book_id', $bookId)
                                  ->first();

            if ($wishlistItem) {
                // Remove from wishlist
                $wishlistItem->delete();
                return response()->json([
                    'success' => true,
                    'action' => 'removed',
                    'message' => 'Buku dihapus dari wishlist'
                ]);
            } else {
                // Add to wishlist
                Wishlist::create([
                    'user_id' => $userId,
                    'book_id' => $bookId
                ]);
                return response()->json([
                    'success' => true,
                    'action' => 'added',
                    'message' => 'Buku ditambahkan ke wishlist'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate wishlist'
            ], 500);
        }
    }

    /**
     * Get user's wishlist
     */
    public function index()
    {
        $userId = Auth::id();
        
        if (!$userId) {
            return redirect()->route('login');
        }

        $wishlistItems = Wishlist::where('user_id', $userId)
                               ->with(['book.category'])
                               ->get();

        return view('wishlist', compact('wishlistItems'));
    }
}
