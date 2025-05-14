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
    				<div class="cart-list">
	    				<table class="table">
						    <thead class="thead-primary">
						      <tr class="text-center">
						        <th>&nbsp;</th>
						        <th>&nbsp;</th>
						        <th>Product name</th>
						        <th>Price</th>
						        <th>Quantity</th>
						        <th>Total</th>
						      </tr>
						    </thead>
						    <tbody id="cart-items">
						      @php $total = 0 @endphp
						      @forelse($cartItems as $id => $details)
						          @php $total += $details['price'] * $details['quantity'] @endphp
						          <tr class="text-center" id="cart-row-{{ $id }}">
						            <td class="product-remove">
						              <a href="javascript:void(0);" onclick="removeFromCart({{ $id }})" class="remove-from-cart">
						                <span class="ion-ios-close"></span>
						              </a>
						            </td>
						            <td class="image-prod">
						              <div class="img" style="background-image:url({{ $details['image'] }});"></div>
						            </td>
						            <td class="product-name">
						              <h3>{{ $details['name'] }}</h3>
						            </td>
						            <td class="price">${{ $details['price'] }}</td>
						            <td class="quantity">
						              <div class="input-group mb-3">
					                <input type="number" 
					                       name="quantity" 
					                       class="quantity form-control input-number" 
					                       value="{{ $details['quantity'] }}" 
					                       min="1"
					                       onchange="updateQuantity({{ $id }}, this.value)"
					                       data-price="{{ $details['price'] }}">
					              </div>
					            </td>
						            <td class="total" id="total-{{ $id }}">${{ $details['price'] * $details['quantity'] }}</td>
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
function updateQuantity(productId, quantity) {
    $.ajax({
        url: '{{ route("cart.update") }}',
        method: 'POST',
        data: {
            product_id: productId,
            quantity: quantity
        },
        success: function(response) {
            if(response.success) {
                // Update the total for this item
                let price = $(`#cart-row-${productId} .quantity input`).data('price');
                let newTotal = price * quantity;
                $(`#total-${productId}`).text('$' + newTotal.toFixed(2));

                // Update cart totals
                updateCartTotals();
            }
        }
    });
}

function removeFromCart(productId) {
    $.ajax({
        url: '{{ route("cart.remove") }}',
        method: 'POST',
        data: { product_id: productId },
        success: function(response) {
            if(response.success) {
                $(`#cart-row-${productId}`).fadeOut(300, function() {
                    $(this).remove();
                    updateCartTotals();
                    $('.icon-shopping_cart').next().text('[' + response.cart_count + ']');
                    
                    if(response.cart_count === 0) {
                        $('#cart-items').html('<tr><td colspan="6" class="text-center">Your cart is empty</td></tr>');
                        $('.row.justify-content-end').hide();
                    }
                });
            }
        }
    });
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
</script>
@endsection