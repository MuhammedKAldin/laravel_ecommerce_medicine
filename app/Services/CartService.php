<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartService
{
    public function addToCart(Product $product, int $quantity = 1)
    {
        $cartItem = CartItem::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->update([
                'quantity' => $cartItem->quantity + $quantity,
            ]);
        } else {
            CartItem::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price
            ]);
        }
    }

    public function updateQuantity($cartItemId, $quantity)
    {
        return CartItem::where('user_id', Auth::id())
            ->where('id', $cartItemId)
            ->update(['quantity' => $quantity]);
    }

    public function removeFromCart($cartItemId)
    {
        return CartItem::where('user_id', Auth::id())
            ->where('id', $cartItemId)
            ->delete();
    }

    public function getCartItems()
    {
        return CartItem::where('user_id', Auth::id())
            ->with('product')
            ->get();
    }

    public function getCartTotal()
    {
        return CartItem::where('user_id', Auth::id())
            ->sum(\DB::raw('price * quantity'));
    }

    public function clearCart()
    {
        return CartItem::where('user_id', Auth::id())->delete();
    }
} 