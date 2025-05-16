@extends('admin.layout.layout')

@section('content')
<div class="container-fluid p-0">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="row mb-2 mt-3 mb-xl-3">
                    <div class="col-auto d-none d-sm-block">
                        <h3>Invoice #{{ $invoice->invoice_number }}</h3>
                    </div>
                    <div class="col-auto ms-auto text-end mt-n1">
                        <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary">Back to List</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Customer Information</h5>
                            <p><strong>Name:</strong> 
                                @if($invoice->user_id)
                                    {{ $invoice->user->name }}
                                @else
                                    {{ $invoice->first_name }} {{ $invoice->last_name }}
                                @endif
                            </p>
                            <p><strong>Email:</strong> 
                                @if($invoice->user_id)
                                    {{ $invoice->user->email }}
                                @else
                                    {{ $invoice->email }}
                                @endif
                            </p>
                            <p><strong>Phone:</strong> {{ $invoice->phone }}</p>
                            <p><strong>Address:</strong> 
                                {{ $invoice->street_address }}
                                @if($invoice->apartment)
                                    , {{ $invoice->apartment }}
                                @endif
                                , {{ $invoice->city }}
                                , {{ $invoice->postcode }}
                                , {{ $invoice->country }}
                            </p>
                            <p><strong>Date:</strong> {{ $invoice->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Invoice Status</h5>
                            <form action="{{ route('admin.invoices.update', $invoice) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="input-group">
                                    <select name="status" class="form-select">
                                        @foreach($statuses as $status)
                                            <option value="{{ $status->value }}" {{ $invoice->status === $status ? 'selected' : '' }}>
                                                {{ $status->label() }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-primary">Update Status</button>
                                </div>
                            </form>

                            <div class="mt-3">
                                <span class="badge bg-{{ $invoice->status->color() }} p-2">
                                    Current Status: {{ $invoice->status->label() }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <h5>Invoice Items</h5>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($invoice->details as $item)
                                        <tr>
                                            <td>{{ $item->product->name }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>${{ number_format($item->unit_price, 2) }}</td>
                                            <td>${{ number_format($item->total_price, 2) }}</td>
                                            <td>
                                                <form action="{{ route('admin.invoices.delete-item', ['invoice' => $invoice, 'item' => $item]) }}" 
                                                      method="POST" style="display: inline-block;"
                                                      class="delete-item-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger btn-sm delete-item-btn">Remove</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                        <td colspan="2"><strong>${{ number_format($invoice->total, 2) }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <h5>Add New Item</h5>
                            <form action="{{ route('admin.invoices.add-item', $invoice) }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="product_id">Product</label>
                                            <select name="product_id" id="product_id" class="form-select select2" required>
                                                <option value="">Select Product</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                                        {{ $product->name }} - ${{ number_format($product->price, 2) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="quantity">Quantity</label>
                                            <input type="number" name="quantity" id="quantity" 
                                                   class="form-control" min="1" value="1" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group" style="margin-top: 24px;">
                                            <button type="submit" class="btn btn-success">Add Item</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        placeholder: "Search for a product...",
        allowClear: true,
        width: '100%'
    });

    // Update quantity input max based on selected product
    $('#product_id').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var price = selectedOption.data('price');
        
        // You could add additional logic here if needed
        // For example, updating a price display or handling stock quantities
    });

    // SweetAlert2 delete confirmation
    $('.delete-item-btn').on('click', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');
        
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endpush 