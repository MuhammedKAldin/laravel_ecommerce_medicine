<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CartItem;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->middleware('auth');
        $this->cartService = $cartService;
    }

    public function cart()
    {
        $cartItems = $this->cartService->getCartItems();
        $total = $this->cartService->getCartTotal();
        return view('cart', compact('cartItems', 'total'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);
        $this->cartService->addToCart($product, $request->quantity);

        $cartCount = CartItem::where('user_id', Auth::id())->sum('quantity');

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully!',
            'cart_count' => $cartCount
        ]);
    }

    public function updateCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:cart_items,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $this->cartService->updateQuantity($request->product_id, $request->quantity);
        
        $cartCount = CartItem::where('user_id', Auth::id())->sum('quantity');

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully!',
            'cart_count' => $cartCount
        ]);
    }

    public function removeFromCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:cart_items,id'
        ]);

        $this->cartService->removeFromCart($request->product_id);
        $cartCount = CartItem::where('user_id', Auth::id())->sum('quantity');

        return response()->json([
            'success' => true,
            'message' => 'Product removed from cart successfully!',
            'cart_count' => $cartCount
        ]);
    }

    public function checkout()
    {
        $cartItems = $this->cartService->getCartItems();
        $total = $this->cartService->getCartTotal();
        return view('checkout', compact('cartItems', 'total'));
    }

    public function orderConfirmation()
    {
        return view('order-confirmation');
    }
}
