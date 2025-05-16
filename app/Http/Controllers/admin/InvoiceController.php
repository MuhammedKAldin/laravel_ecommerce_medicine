<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerInvoice;
use App\Models\CustomerInvoiceDetail;
use App\Models\Product;
use App\Enums\InvoiceStatus;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rule;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = CustomerInvoice::with(['user'])
            ->orderBy('created_at', 'desc');

        // Apply filters
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

        $invoices = $query->paginate(10);

        return view('admin.invoices.list', compact('invoices'));
    }

    public function show(CustomerInvoice $invoice)
    {
        $invoice->load(['user', 'details.product']);
        $products = Product::all(); // For adding new items
        $statuses = InvoiceStatus::cases();
        return view('admin.invoices.show', compact('invoice', 'products', 'statuses'));
    }

    public function update(Request $request, CustomerInvoice $invoice)
    {
        $request->validate([
            'status' => ['required', Rule::enum(InvoiceStatus::class)]
        ]);

        $invoice->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Invoice status updated successfully');
    }

    public function deleteItem(CustomerInvoice $invoice, CustomerInvoiceDetail $item)
    {
        if ($item->invoice_id !== $invoice->id) {
            return redirect()->back()->with('error', 'Invalid item');
        }

        $item->delete();
        
        // Recalculate invoice total using total_price from details
        $invoice->total = $invoice->details()->sum('total_price');
        $invoice->save();

        return redirect()->back()->with('success', 'Item removed successfully');
    }

    public function addItem(Request $request, CustomerInvoice $invoice)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);

        $invoiceDetail = new CustomerInvoiceDetail([
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'unit_price' => $product->price,
            'total_price' => $product->price * $request->quantity
        ]);

        $invoice->details()->save($invoiceDetail);

        // Recalculate invoice total using total_price from details
        $invoice->total = $invoice->details()->sum('total_price');
        $invoice->save();

        return redirect()->back()->with('success', 'Item added successfully');
    }
} 