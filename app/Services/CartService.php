<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected function getCartIdentifier()
    {
        return Auth::check() ? Auth::id() : Session::getId();
    }

    protected function isGuest()
    {
        return !Auth::check();
    }

    public function addToCart(Product $product, int $quantity = 1)
    {
        if ($this->isGuest()) {
            $cart = Session::get('cart', []);
            
            if (isset($cart[$product->id])) {
                $cart[$product->id]['quantity'] += $quantity;
            } else {
                $cart[$product->id] = [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price
                ];
            }
            
            Session::put('cart', $cart);
            return;
        }

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
        if ($this->isGuest()) {
            $cart = Session::get('cart', []);
            if (isset($cart[$cartItemId])) {
                $cart[$cartItemId]['quantity'] = $quantity;
                Session::put('cart', $cart);
            }
            return true;
        }

        return CartItem::where('user_id', Auth::id())
            ->where('id', $cartItemId)
            ->update(['quantity' => $quantity]);
    }

    public function removeFromCart($cartItemId)
    {
        if ($this->isGuest()) {
            $cart = Session::get('cart', []);
            if (isset($cart[$cartItemId])) {
                unset($cart[$cartItemId]);
                Session::put('cart', $cart);
            }
            return true;
        }

        return CartItem::where('user_id', Auth::id())
            ->where('id', $cartItemId)
            ->delete();
    }

    public function getCartItems()
    {
        if ($this->isGuest()) {
            $cart = Session::get('cart', []);
            $products = collect();
            
            foreach ($cart as $productId => $item) {
                $product = Product::find($item['product_id']);
                if ($product) {
                    $cartItem = new \stdClass();
                    $cartItem->id = $product->id;
                    $cartItem->product = $product;
                    $cartItem->quantity = $item['quantity'];
                    $cartItem->price = $item['price'];
                    $products->push($cartItem);
                }
            }
            
            return $products;
        }

        return CartItem::where('user_id', Auth::id())
            ->with('product')
            ->get();
    }

    public function getCartTotal()
    {
        if ($this->isGuest()) {
            $cart = Session::get('cart', []);
            return collect($cart)->sum(function ($item) {
                return $item['price'] * $item['quantity'];
            });
        }

        return CartItem::where('user_id', Auth::id())
            ->sum(\DB::raw('price * quantity'));
    }

    public function clearCart()
    {
        if ($this->isGuest()) {
            Session::forget('cart');
            return true;
        }

        return CartItem::where('user_id', Auth::id())->delete();
    }

    public function getCartCount()
    {
        if ($this->isGuest()) {
            $cart = Session::get('cart', []);
            return collect($cart)->sum('quantity');
        }

        return CartItem::where('user_id', Auth::id())->sum('quantity');
    }

    public function transferGuestCartToUser()
    {
        if (!Auth::check() || !Session::has('cart')) {
            return;
        }

        $cart = Session::get('cart', []);
        foreach ($cart as $productId => $item) {
            $this->addToCart(Product::find($item['product_id']), $item['quantity']);
        }

        Session::forget('cart');
    }
} 