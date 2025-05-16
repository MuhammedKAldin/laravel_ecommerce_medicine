<?php

namespace App\Services;

use App\Models\CustomerInvoice;
use App\Models\CustomerInvoiceDetail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class OrderService
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function getUserOrders($userId, $perPage = 10)
    {
        return CustomerInvoice::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function placeOrder(array $data)
    {
        try {
            DB::beginTransaction();

            $cartItems = $this->cartService->getCartItems();
            
            if ($cartItems->isEmpty()) {
                throw new \Exception('Cart is empty');
            }

            $subtotal = $this->cartService->getCartTotal();
            $delivery = 10.00;
            $discount = session()->get('discount', 0);
            $total = $subtotal + $delivery - $discount;

            // Create invoice
            $invoice = CustomerInvoice::create([
                'user_id' => auth()->id(),
                'invoice_number' => 'INV-' . Str::random(10),
                'subtotal' => $subtotal,
                'delivery' => $delivery,
                'discount' => $discount,
                'total' => $total,
                'first_name' => $data['firstname'],
                'last_name' => $data['lastname'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'country' => $data['country'],
                'street_address' => $data['street_address'],
                'apartment' => $data['apartment'],
                'city' => $data['city'],
                'postcode' => $data['postcode'],
                'payment_method' => $data['payment_method'],
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

            // Clear cart and discount
            $this->cartService->clearCart();
            session()->forget('discount');

            DB::commit();
            return $invoice;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getOrderConfirmation($invoiceNumber)
    {
        $query = CustomerInvoice::with(['details.product'])
            ->where('invoice_number', $invoiceNumber);

        if (auth()->check()) {
            $query->where('user_id', auth()->id());
        } else {
            $query->whereNull('user_id');
        }

        return $query->first();
    }

    public function updateUserData($user, array $data)
    {
        $userUpdates = [];

        $fields = [
            'first_name' => 'firstname',
            'last_name' => 'lastname',
            'phone' => 'phone',
            'country' => 'country',
            'street_address' => 'street_address',
            'apartment' => 'apartment',
            'city' => 'city',
            'postcode' => 'postcode'
        ];

        foreach ($fields as $userField => $dataField) {
            if (empty($user->$userField) && !empty($data[$dataField])) {
                $userUpdates[$userField] = $data[$dataField];
            }
        }

        if (!empty($userUpdates)) {
            $user->update($userUpdates);
        }

        return $user;
    }
} 