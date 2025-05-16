@extends('layout.layout')

@section('content')

<div class="hero-wrap hero-bread" style="background-image: url('images/bg_1.jpg');">
      <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center">
          <div class="col-md-9 ftco-animate text-center">
          	<p class="breadcrumbs"><span class="mr-2"><a href="{{route('home')}}">Home</a></span> <span>Cart</span></p>
            <h1 class="mb-0 bread">My Cart</h1>
          </div>
        </div>
      </div>
    </div>

    <section class="ftco-section ftco-cart">
			<div class="container">
				<div class="row">
    			<div class="col-md-12 ftco-animate">
    				@if(session('success'))
    					<div class="mb-3">
    						<span class="badge bg-success">{{ session('success') }}</span>
    					</div>
    				@endif

    				<div class="cart-list">
	    				<table class="table">
						    <thead class="thead-primary">
						      <tr class="text-center">
						        <th>&nbsp;</th>
						        <th>Product</th>
						        <th>Price</th>
						        <th>Quantity</th>
						        <th>Total</th>
						      </tr>
						    </thead>
						    <tbody id="cart-items">
						      @php $total = 0 @endphp
						      @forelse($cartItems as $item)
						          @php $total += $item->price * $item->quantity @endphp
						          <tr class="text-center" id="cart-row-{{ $item->id }}">
						            <td class="product-remove">
						              <a href="javascript:void(0);" onclick="removeFromCart({{ $item->id }})" class="remove-from-cart">
						                <span class="ion-ios-close"></span>
						              </a>
						            </td>
						            <td class="product-name">
						              <div class="img-prod">
						                <img src="{{ asset($item->product->image) }}" class="img-fluid" alt="{{ $item->product->name }}" style="width: 100px;">
						              </div>
						              <h3>{{ $item->product->name }}</h3>
						            </td>
						            <td class="price">${{ number_format($item->price, 2) }}</td>
						            <td class="quantity">
						              <div class="input-group mb-3">
					                <input type="number" 
					                       name="quantity" 
					                       class="quantity form-control input-number" 
					                       value="{{ $item->quantity }}" 
					                       min="1"
					                       onchange="updateQuantity({{ $item->id }}, this.value)"
					                       data-price="{{ $item->price }}">
					              </div>
					            </td>
						            <td class="total" id="total-{{ $item->id }}">${{ number_format($item->price * $item->quantity, 2) }}</td>
						          </tr>
						      @empty
						          <tr>
						            <td colspan="6" class="text-center">Your cart is empty</td>
						          </tr>
						      @endforelse
						    </tbody>
						  </table>
					  </div>
    			</div>
    		</div>
    		@if(count($cartItems) > 0)
    		<div class="row justify-content-end">
    			<div class="col-lg-4 mt-5 cart-wrap ftco-animate">
    				<div class="cart-total mb-3">
    					<h3>Cart Totals</h3>
    					<p class="d-flex">
    						<span>Subtotal</span>
    						<span id="cart-subtotal">${{ $total }}</span>
    					</p>
    					<p class="d-flex">
    						<span>Delivery</span>
    						<span>$0.00</span>
    					</p>
    					<hr>
    					<p class="d-flex total-price">
    						<span>Total</span>
    						<span id="cart-total">${{ $total }}</span>
    					</p>
    				</div>
    				<p><a href="{{ route('checkout') }}" class="btn btn-primary py-3 px-4">Proceed to Checkout</a></p>
    			</div>
    		</div>
    		@endif
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
</script>
@endsection