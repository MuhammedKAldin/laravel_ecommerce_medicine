<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use App\Services\CartService;
use App\Enums\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

class OrderController extends Controller
{
    protected $orderService;
    protected $cartService;

    public function __construct(OrderService $orderService, CartService $cartService)
    {
        $this->middleware('auth')->except(['placeOrder', 'confirmation']);
        $this->orderService = $orderService;
        $this->cartService = $cartService;
    }

    public function index()
    {
        $invoices = $this->orderService->getUserOrders(auth()->id());
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
            'payment_method' => ['required', new Enum(PaymentMethod::class)],
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
            'payment_method' => 'Please select a valid payment method'
        ]);

        try {
            // Place order using service
            $invoice = $this->orderService->placeOrder($request->all());

            // Update user data if authenticated
            if (auth()->check()) {
                $this->orderService->updateUserData(auth()->user(), $request->all());
            }

            return redirect()->route('order.confirmation', ['invoice' => $invoice->invoice_number])
                           ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'There was an error processing your order. Please try again.');
        }
    }

    public function confirmation($invoice)
    {
        if (!$invoice) {
            return redirect()->route('home');
        }

        $invoice = $this->orderService->getOrderConfirmation($invoice);
        if (!$invoice) {
            return redirect()->route('home');
        }

        return view('order-confirmation', compact('invoice'));
    }
} 