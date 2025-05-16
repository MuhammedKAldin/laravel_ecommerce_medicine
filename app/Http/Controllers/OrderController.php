<?php

namespace App\Http\Controllers;

use App\Models\CustomerInvoice;
use App\Models\CustomerInvoiceDetail;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->middleware('auth')->except(['placeOrder', 'confirmation']);
        $this->cartService = $cartService;
    }

    public function index()
    {
        $invoices = CustomerInvoice::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders', compact('invoices'));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:255|regex:/^[a-zA-Z\s\'-]+$/|min:2',
            'lastname' => 'required|string|max:255|regex:/^[a-zA-Z\s\'-]+$/|min:2',
            'country' => 'required|string|max:255|in:Egypt,France,Italy,Philippines,South Korea,Hongkong,Japan',
            'street_address' => 'required|string|max:255|min:5',
            'apartment' => 'nullable|string|max:50',
            'city' => 'required|string|max:255|min:2',
            'postcode' => 'required|string|max:20|min:4|regex:/^[a-zA-Z0-9-\s]+$/',
            'phone' => [
                'required',
                'string',
                'min:6',
                'max:20',
                'regex:/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,8}$/'
            ],
            'email' => 'required|email|max:255',
            'payment_method' => 'required|in:bank_transfer,check,paypal',
            'terms' => 'required|accepted'
        ], [
            'firstname.regex' => 'First name can only contain letters, spaces, hyphens and apostrophes',
            'lastname.regex' => 'Last name can only contain letters, spaces, hyphens and apostrophes',
            'street_address.regex' => 'Street address contains invalid characters',
            'apartment.regex' => 'Apartment/Suite contains invalid characters',
            'city.regex' => 'City name can only contain letters, spaces, hyphens and apostrophes',
            'postcode.regex' => 'Invalid postcode format',
            'phone.regex' => 'Please enter a valid phone number',
            'phone.min' => 'Phone number must be at least 6 digits long',
        ]);

        try {
            DB::beginTransaction();

            // Get cart items using CartService
            $cartItems = $this->cartService->getCartItems();
            
            if ($cartItems->isEmpty()) {
                return back()->with('error', 'Your cart is empty. Please add some items before checking out.');
            }

            $subtotal = $this->cartService->getCartTotal();
            $delivery = 10.00; // Example delivery fee
            $discount = session()->get('discount', 0);
            $total = $subtotal + $delivery - $discount;

            // Set user_id if authenticated, null if guest
            $user_id = auth()->check() ? auth()->id() : null;

            // Only update user data if authenticated
            if (auth()->check()) {
                $user = auth()->user();
                $userUpdates = [];

                if (empty($user->first_name)) {
                    $userUpdates['first_name'] = $request->firstname;
                }
                if (empty($user->last_name)) {
                    $userUpdates['last_name'] = $request->lastname;
                }
                if (empty($user->phone)) {
                    $userUpdates['phone'] = $request->phone;
                }
                if (empty($user->country)) {
                    $userUpdates['country'] = $request->country;
                }
                if (empty($user->street_address)) {
                    $userUpdates['street_address'] = $request->street_address;
                }
                if (empty($user->apartment)) {
                    $userUpdates['apartment'] = $request->apartment;
                }
                if (empty($user->city)) {
                    $userUpdates['city'] = $request->city;
                }
                if (empty($user->postcode)) {
                    $userUpdates['postcode'] = $request->postcode;
                }

                // Only update if there are empty fields
                if (!empty($userUpdates)) {
                    $user->update($userUpdates);
                }
            }

            // Create invoice with user_id if authenticated, null if guest
            $invoice = CustomerInvoice::create([
                'user_id' => $user_id,
                'invoice_number' => 'INV-' . Str::random(10),
                'subtotal' => $subtotal,
                'delivery' => $delivery,
                'discount' => $discount,
                'total' => $total,
                'first_name' => $request->firstname,
                'last_name' => $request->lastname,
                'email' => $request->email,
                'phone' => $request->phone,
                'country' => $request->country,
                'street_address' => $request->street_address,
                'apartment' => $request->apartment,
                'city' => $request->city,
                'postcode' => $request->postcode,
                'payment_method' => $request->payment_method,
                'status' => 'pending'
            ]);

            // Create invoice details
            foreach ($cartItems as $item) {
                CustomerInvoiceDetail::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item->product->id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->price,
                    'total_price' => $item->price * $item->quantity
                ]);
            }

            // Clear cart using CartService
            $this->cartService->clearCart();

            // Clear any discount
            session()->forget('discount');

            DB::commit();

            return redirect()->route('order.confirmation', ['invoice' => $invoice->invoice_number])
                           ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Order Processing Error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'There was an error processing your order. Please try again.');
        }
    }

    public function confirmation($invoice)
    {
        // Redirect to home if no invoice number provided
        if (!$invoice) {
            return redirect()->route('home');
        }

        $query = CustomerInvoice::with(['details.product'])
            ->where('invoice_number', $invoice);

        // If user is authenticated, only show their orders
        if (auth()->check()) {
            $query->where('user_id', auth()->id());
        } 
        else 
        {
            // For guests, only show orders from the current session
            $query->whereNull('user_id');
        }

        // If no matching invoice is found, redirect to home
        $invoice = $query->first();
        if (!$invoice) {
            return redirect()->route('home');
        }

        return view('order-confirmation', compact('invoice'));
    }
} 