<?php

namespace App\Services\Admin;

use App\Models\CustomerInvoice;
use App\Models\CustomerInvoiceDetail;
use App\Models\Product;
use Illuminate\Http\Request;

class InvoiceService
{
    public function getInvoices(Request $request)
    {
        $query = CustomerInvoice::with(['user'])
            ->orderBy('created_at', 'desc');

        if ($request->search_invoice) {
            $query->where('invoice_number', 'like', '%' . $request->search_invoice . '%');
        }

        if ($request->search_status) {
            $query->where('status', $request->search_status);
        }

        if ($request->search_customer) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search_customer . '%');
            });
        }

        return $query->paginate(10);
    }

    public function getInvoiceDetails(CustomerInvoice $invoice)
    {
        $invoice->load(['user', 'details.product']);
        $products = Product::all();
        return [
            'invoice' => $invoice,
            'products' => $products,
        ];
    }

    public function updateInvoiceStatus(CustomerInvoice $invoice, string $status)
    {
        return $invoice->update([
            'status' => $status
        ]);
    }

    public function deleteInvoiceItem(CustomerInvoice $invoice, CustomerInvoiceDetail $item)
    {
        if ($item->invoice_id !== $invoice->id) {
            return false;
        }

        $item->delete();
        
        // Recalculate invoice total
        $invoice->total = $invoice->details()->sum('total_price');
        $invoice->save();

        return true;
    }

    public function addInvoiceItem(CustomerInvoice $invoice, array $data)
    {
        $product = Product::findOrFail($data['product_id']);

        $invoiceDetail = new CustomerInvoiceDetail([
            'product_id' => $product->id,
            'quantity' => $data['quantity'],
            'unit_price' => $product->price,
            'total_price' => $product->price * $data['quantity']
        ]);

        $invoice->details()->save($invoiceDetail);

        // Recalculate invoice total
        $invoice->total = $invoice->details()->sum('total_price');
        $invoice->save();

        return $invoiceDetail;
    }
} 