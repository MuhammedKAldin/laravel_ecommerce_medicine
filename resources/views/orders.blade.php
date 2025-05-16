@extends('layout.layout')

@section('content')

<div class="hero-wrap hero-bread" style="background-image: url('images/bg_1.jpg');">
      <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center">
          <div class="col-md-9 ftco-animate text-center">
          	<p class="breadcrumbs"><span class="mr-2"><a href="{{route('home')}}">Home</a></span> <span>Orders</span></p>
            <h1 class="mb-0 bread">My Orders</h1>
          </div>
        </div>
      </div>
    </div>

    <section class="ftco-section">
			<div class="container">
				<div class="row">
    			<div class="col-md-12 ftco-animate">
    				@if(session('success'))
    					<div class="mb-3">
    						<span class="badge bg-success">{{ session('success') }}</span>
    					</div>
    				@endif

    				<div class="table-responsive">
	    				<table class="table">
						    <thead class="thead-primary">
						      <tr class="text-center">
						        <th>Order #</th>
						        <th>Date</th>
						        <th>Total</th>
						        <th>Status</th>
						        <th>Action</th>
						      </tr>
						    </thead>
						    <tbody>
						      @forelse($invoices as $invoice)
						          <tr class="text-center">
						            <td>{{ $invoice->invoice_number }}</td>
						            <td class="order-date" data-timestamp="{{ $invoice->created_at->timestamp }}">
						                {{ $invoice->created_at->format('M d, Y h:i A') }}
						            </td>
						            <td>${{ number_format($invoice->total, 2) }}</td>
						            <td>
						              <span class="badge badge-{{ $invoice->status->color() }}">
						                {{ $invoice->status->label() }}
						              </span>
						            </td>
						            <td>
						              <a href="{{ route('order.confirmation', ['invoice' => $invoice->invoice_number]) }}" 
						                 class="btn btn-primary btn-sm">View Details</a>
						            </td>
						          </tr>
						      @empty
						          <tr>
						            <td colspan="5" class="text-center">No orders found</td>
						          </tr>
						      @endforelse
						    </tbody>
						  </table>
					  </div>

					  <div class="row mt-5">
					    <div class="col d-flex justify-content-center">
					      {{ $invoices->links() }}
					    </div>
					  </div>
    			</div>
    		</div>
			</div>
		</section>

		<section class="ftco-section ftco-no-pt ftco-no-pb py-5 bg-light">
      <div class="container py-4">
        <div class="row d-flex justify-content-center py-5">
          <div class="col-md-6">
          	<h2 style="font-size: 22px;" class="mb-0">Subcribe to our Newsletter</h2>
          	<span>Get e-mail updates about our latest shops and special offers</span>
          </div>
          <div class="col-md-6 d-flex align-items-center">
            <form action="#" class="subscribe-form">
              <div class="form-group d-flex">
                <input type="text" class="form-control" placeholder="Enter email address">
                <input type="submit" value="Subscribe" class="submit px-3">
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>

@endsection

@section('scripts')
<script>
function updateQuantity(cartItemId, quantity) {
    $.ajax({
        url: '{{ route("cart.update") }}',
        method: 'POST',
        data: {
            product_id: cartItemId,
            quantity: quantity,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if(response.success) {
                // Update the total for this item
                let price = $(`#cart-row-${cartItemId} .quantity input`).data('price');
                let newTotal = price * quantity;
                $(`#total-${cartItemId}`).text('$' + newTotal.toFixed(2));

                // Update cart totals
                updateCartTotals();
            }
        },
        error: function() {
            alert('Failed to update quantity. Please try again.');
        }
    });
}

function removeFromCart(cartItemId) {
    if(confirm('Are you sure you want to remove this item?')) {
        $.ajax({
            url: '{{ route("cart.remove") }}',
            method: 'POST',
            data: { 
                product_id: cartItemId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if(response.success) {
                    $(`#cart-row-${cartItemId}`).fadeOut(300, function() {
                        $(this).remove();
                        updateCartTotals();
                        $('.icon-shopping_cart').next().text('[' + response.cart_count + ']');
                        
                        if(response.cart_count === 0) {
                            $('#cart-items').html('<tr><td colspan="5" class="text-center">Your cart is empty</td></tr>');
                            $('.row.justify-content-end').hide();
                        }
                    });
                }
            },
            error: function() {
                alert('Failed to remove item. Please try again.');
            }
        });
    }
}

function updateCartTotals() {
    let subtotal = 0;
    // Calculate new subtotal
    $('.quantity input').each(function() {
        let price = $(this).data('price');
        let quantity = $(this).val();
        subtotal += price * quantity;
    });

    // Update subtotal and total displays
    $('#cart-subtotal').text('$' + subtotal.toFixed(2));
    $('#cart-total').text('$' + subtotal.toFixed(2));
}

// Setup CSRF token for all AJAX requests
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
    }
});

document.addEventListener('DOMContentLoaded', function() {
    // Convert all timestamps to local time
    document.querySelectorAll('.order-date').forEach(function(element) {
        const timestamp = parseInt(element.getAttribute('data-timestamp')) * 1000; // Convert to milliseconds
        const date = new Date(timestamp);
        
        // Format the date in local time
        const formattedDate = date.toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric',
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });
        
        element.textContent = formattedDate;
    });
});
</script>
@endsection