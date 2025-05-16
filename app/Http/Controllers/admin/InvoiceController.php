<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerInvoice;
use App\Models\CustomerInvoiceDetail;
use App\Services\Admin\InvoiceService;
use App\Enums\InvoiceStatus;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rule;

class InvoiceController extends Controller
{
    protected $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    public function index(Request $request)
    {
        $invoices = $this->invoiceService->getInvoices($request);
        return view('admin.invoices.list', compact('invoices'));
    }

    public function show(CustomerInvoice $invoice)
    {
        $data = $this->invoiceService->getInvoiceDetails($invoice);
        $statuses = InvoiceStatus::cases();
        return view('admin.invoices.show', array_merge($data, ['statuses' => $statuses]));
    }

    public function update(Request $request, CustomerInvoice $invoice)
    {
        $request->validate([
            'status' => ['required', Rule::enum(InvoiceStatus::class)]
        ]);

        $this->invoiceService->updateInvoiceStatus($invoice, $request->status);
        return redirect()->back()->with('success', 'Invoice status updated successfully');
    }

    public function deleteItem(CustomerInvoice $invoice, CustomerInvoiceDetail $item)
    {
        if ($this->invoiceService->deleteInvoiceItem($invoice, $item)) {
            return redirect()->back()->with('success', 'Item removed successfully');
        }
        
        return redirect()->back()->with('error', 'Invalid item');
    }

    public function addItem(Request $request, CustomerInvoice $invoice)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $this->invoiceService->addInvoiceItem($invoice, $request->all());
        return redirect()->back()->with('success', 'Item added successfully');
    }
} 