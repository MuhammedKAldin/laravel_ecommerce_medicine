@extends('admin.layout.layout')

@section('content')
<div class="container-fluid p-0">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="row mb-2 mb-xl-3">
                    <div class="col-auto d-none d-sm-block">
                        <h3>Customer Invoices</h3>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.invoices.index') }}" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="search_invoice">Invoice #</label>
                                    <input type="text" class="form-control" id="search_invoice" name="search_invoice" 
                                           value="{{ request('search_invoice') }}" placeholder="Search by invoice number...">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="search_status">Status</label>
                                    <select class="form-control" id="search_status" name="search_status">
                                        <option value="">All Statuses</option>
                                        @foreach(\App\Enums\InvoiceStatus::cases() as $status)
                                            <option value="{{ $status->value }}" {{ request('search_status') == $status->value ? 'selected' : '' }}>
                                                {{ $status->label() }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="search_customer">Customer</label>
                                    <input type="text" class="form-control" id="search_customer" name="search_customer" 
                                           value="{{ request('search_customer') }}" placeholder="Search by customer name...">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group" style="margin-top: 24px;">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                    <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary">Reset</a>
                                </div>
                            </div>
                        </div>
                    </form>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            <div class="alert-message">
                                {{ session('success') }}
                            </div>
                        </div>
                    @endif

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Invoice #</th>
                                <th>Customer</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoices as $invoice)
                                <tr>
                                    <td>{{ $invoice->invoice_number }}</td>
                                    <td>
                                        @if($invoice->user_id)
                                            {{ $invoice->user->name }}
                                        @else
                                            {{ $invoice->first_name }} {{ $invoice->last_name }}
                                        @endif
                                    </td>
                                    <td>${{ number_format($invoice->total, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $invoice->status->color() }}">
                                            {{ $invoice->status->label() }}
                                        </span>
                                    </td>
                                    <td>{{ $invoice->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.invoices.show', $invoice) }}" 
                                               class="btn btn-info btn-sm">
                                                View/Edit
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-3">
                        {{ $invoices->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 