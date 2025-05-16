<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CartItem;
use App\Services\CartService;
use App\Enums\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
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

        $cartCount = $this->cartService->getCartCount();

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully!',
            'cart_count' => $cartCount
        ]);
    }

    public function updateCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required|integer|min:1'
        ]);

        $this->cartService->updateQuantity($request->product_id, $request->quantity);
        
        $cartCount = $this->cartService->getCartCount();

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully!',
            'cart_count' => $cartCount
        ]);
    }

    public function removeFromCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required'
        ]);

        $this->cartService->removeFromCart($request->product_id);
        $cartCount = $this->cartService->getCartCount();

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
        $user = Auth::user();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty');
        }

        // Calculate cart totals
        $subtotal = $total;
        $delivery = 0.00;
        $discount = 0.00;
        
        $paymentMethods = PaymentMethod::cases();
        
        return view('checkout', compact('cartItems', 'total', 'user', 'subtotal', 'delivery', 'discount', 'paymentMethods'));
    }

    public function orderConfirmation()
    {
        return redirect()->route('home');
    }
}
