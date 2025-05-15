@extends('layout.layout')

@section('content')
<style>
    /* Global table fixes to prevent horizontal scrolling */
    .table-responsive {
        overflow-x: visible !important;
        overflow: visible !important;
    }
    
    .table {
        width: 100% !important;
        margin-bottom: 0;
    }
    
    .table td {
        max-width: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal;
        word-wrap: break-word;
    }
    
    /* Ensure proper text wrapping in all table cells */
    .table th,
    .table td {
        white-space: normal !important;
        min-width: auto !important;
        max-width: none !important;
    }
    
    /* Specific column widths for better control */
    .table th:first-child,
    .table td:first-child {
        width: 40%;
    }
    
    .table th:not(:first-child),
    .table td:not(:first-child) {
        width: 20%;
    }
    
    @media (max-width: 768px) {
        .table td {
            font-size: 0.9rem;
        }
        
        .table th:first-child,
        .table td:first-child {
            width: 35%;
        }
        
        .table th:not(:first-child),
        .table td:not(:first-child) {
            width: 21.66%;
        }
    }
</style>

<div class="hero-wrap hero-bread d-flex align-items-center justify-content-center" style="background-image: url('{{ asset('images/bg_1.jpg') }}');">
  <div class="container text-center">
    <p class="breadcrumbs mb-2">
      <a href="{{ route('home') }}" class="text-white me-2">Home</a>
      <span class="text-white-50">/ Order Confirmation</span>
    </p>
    <h1 class="mb-0 text-white">Thank You!</h1>
  </div>
</div>

<section class="ftco-section py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-10">
        @if(session('success'))
          <div class="alert alert-success text-center mb-5">
            {{ session('success') }}
          </div>
        @endif

        <div class="card shadow-sm mb-4">
          <div class="card-header bg-white">
            <h3 class="card-title">Order #{{ $invoice->invoice_number }}</h3>
          </div>
          <div class="card-body">

            {{-- Billing & Order Details --}}
            <div class="row mb-4">
              <div class="col-12 col-sm-6 mb-3 mb-sm-0">
                <h6 class="mb-3">Billing Details</h6>
                <p class="mb-1"><strong>Name:</strong> {{ $invoice->first_name }} {{ $invoice->last_name }}</p>
                <p class="mb-1"><strong>Email:</strong> {{ $invoice->email }}</p>
                <p class="mb-1"><strong>Phone:</strong> {{ $invoice->phone }}</p>
                <p class="mb-1"><strong>Address:</strong> {{ $invoice->street_address }}</p>
                @if($invoice->apartment)
                  <p class="mb-1"><strong>Apartment:</strong> {{ $invoice->apartment }}</p>
                @endif
                <p class="mb-1"><strong>City:</strong> {{ $invoice->city }}</p>
                <p class="mb-1"><strong>Country:</strong> {{ $invoice->country }}</p>
                <p class="mb-0"><strong>Postcode:</strong> {{ $invoice->postcode }}</p>
              </div>
              <div class="col-12 col-sm-6">
                <h6 class="mb-3">Order Details</h6>
                <p class="mb-1"><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $invoice->payment_method)) }}</p>
                <p class="mb-1">
                  <strong>Status:</strong>
                  <span class="badge bg-{{ $invoice->status === 'paid' ? 'success' : 'warning' }}">
                    {{ ucfirst($invoice->status) }}
                  </span>
                </p>
                <p class="mb-0"><strong>Date:</strong> {{ $invoice->created_at->format('M d, Y H:i') }}</p>
              </div>
            </div>

            {{-- Order Items --}}
            <div class="card mb-4">
              <div class="card-header bg-light py-2">
                <h6 class="mb-0 text-uppercase">Order Items</h6>
              </div>
              <div class="card-body">
                {{-- Headers --}}
                <div class="row d-none d-sm-flex mb-2">
                  <div class="col-sm-5">
                    <span class="text-muted">Product</span>
                  </div>
                  <div class="col-sm-2 text-center">
                    <span class="text-muted">Quantity</span>
                  </div>
                  <div class="col-sm-2 text-end">
                    <span class="text-muted">Price</span>
                  </div>
                  <div class="col-sm-3 text-end">
                    <span class="text-muted">Total</span>
                  </div>
                </div>

                <hr class="my-3">

                {{-- Items --}}
                @foreach($invoice->details as $detail)
                  <div class="row align-items-center {{ !$loop->last ? 'mb-3' : '' }}">
                    <div class="col-12 col-sm-5">
                      <p class="mb-2 mb-sm-0 fw-bold">{{ $detail->product->name }}</p>
                    </div>
                    <div class="col-4 col-sm-2 text-sm-center">
                      <span class="d-inline-block d-sm-none text-muted">Quantity: </span>
                      {{ $detail->quantity }}
                    </div>
                    <div class="col-4 col-sm-2 text-sm-end">
                      <span class="d-inline-block d-sm-none text-muted">Price: </span>
                      ${{ number_format($detail->unit_price, 2) }}
                    </div>
                    <div class="col-4 col-sm-3 text-sm-end">
                      <span class="d-inline-block d-sm-none text-muted">Total: </span>
                      <span class="fw-bold">${{ number_format($detail->total_price, 2) }}</span>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>

            {{-- Order Summary --}}
            <div class="card mb-4">
              <div class="card-header bg-light py-2">
                <h6 class="mb-0 text-uppercase">Order Summary</h6>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-6 text-end">Subtotal:</div>
                  <div class="col-6 text-end">${{ number_format($invoice->subtotal, 2) }}</div>

                  <div class="col-6 text-end">Delivery:</div>
                  <div class="col-6 text-end">${{ number_format($invoice->delivery, 2) }}</div>

                  @if($invoice->discount > 0)
                    <div class="col-6 text-end text-danger">Discount:</div>
                    <div class="col-6 text-end text-danger">- ${{ number_format($invoice->discount, 2) }}</div>
                  @endif

                  <div class="col-6 text-end fw-bold border-top pt-2">Total:</div>
                  <div class="col-6 text-end fw-bold border-top pt-2">${{ number_format($invoice->total, 2) }}</div>
                </div>
              </div>
            </div>

            {{-- Actions --}}
            <div class="text-center">
              <a href="{{ route('home') }}" class="btn btn-primary me-2">Continue Shopping</a>
              <button onclick="window.print()" class="btn btn-secondary">Print Order</button>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection