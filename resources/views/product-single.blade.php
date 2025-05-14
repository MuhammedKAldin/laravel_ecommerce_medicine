@extends('layout.layout')

@section('content')
    <div class="hero-wrap hero-bread" style="background-image: url('{{ asset('images/bg_1.jpg') }}');">
      <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center">
          <div class="col-md-9 ftco-animate text-center">
          	<p class="breadcrumbs"><span class="mr-2"><a href="{{ route('home') }}">Home</a></span> <span class="mr-2"><a href="{{ route('products') }}">Product</a></span> <span>{{ $product->name }}</span></p>
            <h1 class="mb-0 bread">{{ $product->name }}</h1>
          </div>
        </div>
      </div>
    </div>

    <section class="ftco-section">
    	<div class="container">
    		<div class="row">
    			<div class="col-lg-6 mb-5 ftco-animate">
    				<a href="{{ asset('storage/' . $product->image) }}" class="image-popup product-img-main">
                        <img src="{{ $product->image }}" class="img-fluid" alt="{{ $product->name }}">
                    </a>
    			</div>
    			<div class="col-lg-6 product-details pl-md-5 ftco-animate">
    				<h3>{{ $product->name }}</h3>
    				<div class="rating d-flex">
							<p class="text-left mr-4">
								<a href="#" class="mr-2">5.0</a>
								<a href="#"><span class="ion-ios-star-outline"></span></a>
								<a href="#"><span class="ion-ios-star-outline"></span></a>
								<a href="#"><span class="ion-ios-star-outline"></span></a>
								<a href="#"><span class="ion-ios-star-outline"></span></a>
								<a href="#"><span class="ion-ios-star-outline"></span></a>
							</p>
							<p class="text-left mr-4">
								<a href="#" class="mr-2" style="color: #000;">100 <span style="color: #bbb;">Rating</span></a>
							</p>
							<p class="text-left">
								<a href="#" class="mr-2" style="color: #000;">500 <span style="color: #bbb;">Sold</span></a>
							</p>
						</div>
    				<p class="price"><span>${{ $product->price }}</span></p>
    				<p>{{ $product->description }}</p>
						<div class="row mt-4">
							<div class="col-md-6">
								<div class="form-group d-flex">
		              <div class="select-wrap">
	                  <div class="icon"><span class="ion-ios-arrow-down"></span></div>
	                </div>
		            </div>
							</div>
							<div class="w-100"></div>
							<div class="input-group col-md-6 d-flex mb-3">
	             	<span class="input-group-btn mr-2">
	                	<button type="button" class="quantity-left-minus btn" data-type="minus" data-field="">
	                   <i class="ion-ios-remove"></i>
	                	</button>
	            		</span>
	             	<input type="number" id="quantity" name="quantity" class="quantity form-control input-number" value="1" min="1" max="{{ $product->quantity }}">
	             	<span class="input-group-btn ml-2">
	                	<button type="button" class="quantity-right-plus btn" data-type="plus" data-field="">
	                     <i class="ion-ios-add"></i>
	                 </button>
	             	</span>
	          	</div>
	          	<div class="w-100"></div>
	          	<div class="col-md-12">
	          		<p style="color: #000;">{{ $product->quantity }} in stock available</p>
	          	</div>
          	</div>
          	<p>
			  <button type="button" 
			  onclick="addToCart({{ $product->id }}, event, '{{ asset($product->image) }}')" 
			  class="btn btn-black py-3 px-5 add-to-cart-btn" 
			  style="background-color: black!important; padding: 15px!important; padding-bottom: 10px!important; padding-top: 10px!important;">
				Add to Cart
			  </button>
			</p>
    		</div>
    	</div>
    </section>

    @if(isset($relatedProducts) && count($relatedProducts) > 0)
    <section class="ftco-section">
    	<div class="container">
				<div class="row justify-content-center mb-3 pb-3">
          <div class="col-md-12 heading-section text-center ftco-animate">
            <h2 class="mb-4">Related Products</h2>
          </div>
        </div>   		
    	</div>
    	<div class="container">
    		<div class="row">
                @foreach ($relatedProducts as $relatedProduct)
    			<div class="col-md-6 col-lg-3 ftco-animate">
    				<div class="product">
    					<a href="{{ route('product.show', $relatedProduct->id) }}" class="img-prod">
                            <img class="img-fluid" src="{{ asset($relatedProduct->image) }}" alt="{{ $relatedProduct->name }}">
    						<div class="overlay"></div>
    					</a>
    					<div class="text py-3 pb-4 px-3 text-center">
    						<h3><a href="{{ route('product.show', $relatedProduct->id) }}">{{ $relatedProduct->name }}</a></h3>
    						<div class="d-flex">
    							<div class="pricing">
		    						<p class="price"><span class="price-sale">${{ $relatedProduct->price }}</span></p>
		    					</div>
	    					</div>
	    					<div class="bottom-area d-flex px-3">
	    						<div class="m-auto d-flex">
	    							<a href="{{ route('product.show', $relatedProduct->id) }}" class="add-to-cart d-flex justify-content-center align-items-center text-center">
	    								<span><i class="ion-ios-menu"></i></span>
	    							</a>
	    							<a href="javascript:void(0);" onclick="addToCart({{ $relatedProduct->id }}, event, '{{ $relatedProduct->image }}', this)" class="buy-now d-flex justify-content-center align-items-center mx-1">
	    								<span><i class="ion-ios-cart"></i></span>
	    							</a>
    							</div>
    						</div>
    					</div>
    				</div>
    			</div>
                @endforeach
    		</div>
    	</div>
    </section>
    @endif
@endsection

@section('scripts')
<style>
.cart-item-fly {
    position: fixed;
    z-index: 1000;
    width: 75px;
    height: 75px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    pointer-events: none;
    transition: all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}
.cart-item-fly img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}
</style>

<script>
function addToCart(productId, event, productImage, productElement = null) {
    event.preventDefault();
    
    // Get the product image position
    let imgPosition;
    if (productElement) {
        // For related products
        const productImg = $(productElement).closest('.product').find('.img-prod img');
        imgPosition = productImg.offset();
    } else {
        // For main product
        const productImg = $('.product-img-main img');
        imgPosition = productImg.offset();
    }
    
    // Get cart icon position
    const cartIcon = $('.icon-shopping_cart').first();
    const cartIconPos = cartIcon.offset();
    
    // Create the flying element with product image
    const flyingItem = $('<div class="cart-item-fly"><img src="' + productImage + '" alt="Product"></div>');
    flyingItem.css({
        top: (imgPosition.top - $(window).scrollTop()) + 'px',
        left: imgPosition.left + 'px',
        transform: 'scale(1)',
        opacity: 1
    });
    $('body').append(flyingItem);

    // Trigger animation in the next frame
    setTimeout(() => {
        flyingItem.css({
            top: (cartIconPos.top - $(window).scrollTop()) + 'px',
            left: cartIconPos.left + 'px',
            transform: 'scale(0.3)',
            opacity: 0
        });

        // Remove the element after animation
        setTimeout(() => {
            flyingItem.remove();
        }, 800);
    }, 0);

    // Get quantity if it's the main product
    const quantity = productElement ? 1 : parseInt($('#quantity').val() || 1);

    // Make the AJAX call
    $.ajax({
        url: '{{ route("cart.add") }}',
        method: 'POST',
        data: {
            product_id: productId,
            quantity: quantity,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if(response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message,
                    showConfirmButton: false,
                    timer: 1500
                });
                
                // Update cart count in navbar
                $('.icon-shopping_cart').next().text('[' + response.cart_count + ']');
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong!',
            });
        }
    });
}
</script>
@endsection
