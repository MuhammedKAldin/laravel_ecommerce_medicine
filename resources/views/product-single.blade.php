@extends('layout.layout')

@section('content')
    <div class="hero-wrap hero-bread" style="background-image: url('{{ asset('images/bg_1.jpg') }}');">
      <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center">
          <div class="col-md-9 ftco-animate text-center">
            <p class="breadcrumbs">
                <span class="mr-2"><a href="{{ route('home') }}">Home</a></span>
                <span class="mr-2"><a href="{{ route('products') }}">Products</a></span>
                <span>{{ $product->name }}</span>
            </p>
            <h1 class="mb-0 bread">{{ $product->name }}</h1>
          </div>
        </div>
      </div>
    </div>

    <section class="ftco-section">
    	<div class="container">
    		<div class="row">
    			<div class="col-lg-6 mb-5 ftco-animate">
                    @if($product->image)
                        <img src="{{ $product->image }}" class="img-fluid" alt="{{ $product->name }}">
                    @else
                        <img src="{{ asset('images/product-placeholder.jpg') }}" class="img-fluid" alt="{{ $product->name }}">
                    @endif
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
					</div>
    				<p class="price">
                        @if($product->sale_price)
                            <span class="mr-2 price-dc">${{ number_format($product->price, 2) }}</span>
                            <span class="price-sale">${{ number_format($product->sale_price, 2) }}</span>
                        @else
                            <span>${{ number_format($product->price, 2) }}</span>
                        @endif
                    </p>
    				<p>{{ $product->description }}</p>
					<div class="row mt-4">
						<div class="w-100"></div>
						<div class="input-group col-md-6 d-flex mb-3">
	             	<span class="input-group-btn mr-2">
	                	<button type="button" class="quantity-left-minus btn"  data-type="minus" data-field="">
	                   <i class="ion-ios-remove"></i>
	                	</button>
	            		</span>
	             	<input type="text" id="quantity" name="quantity" class="form-control input-number" value="1" min="1" max="100">
	             	<span class="input-group-btn ml-2">
	                	<button type="button" class="quantity-right-plus btn" data-type="plus" data-field="">
	                     <i class="ion-ios-add"></i>
	                 </button>
	             	</span>
	          	</div>
          	</div>
          	<p><a href="#" class="btn btn-black py-3 px-5">Add to Cart</a></p>
    			</div>
    		</div>
    	</div>
    </section>

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
                @foreach($product->category->products()->where('id', '!=', $product->id)->take(4)->get() as $relatedProduct)
    			<div class="col-md-6 col-lg-3 ftco-animate">
    				<div class="product">
    					<a href="{{ route('product.show', $relatedProduct->id) }}" class="img-prod">
                            @if($relatedProduct->image)
                                <img class="img-fluid" src="{{ $relatedProduct->image }}" alt="{{ $relatedProduct->name }}">
                            @else
                                <img class="img-fluid" src="{{ asset('images/product-placeholder.jpg') }}" alt="{{ $relatedProduct->name }}">
                            @endif
                            @if($relatedProduct->sale_price)
                                <span class="status">{{ round((($relatedProduct->price - $relatedProduct->sale_price) / $relatedProduct->price) * 100) }}%</span>
                            @endif
    						<div class="overlay"></div>
    					</a>
    					<div class="text py-3 pb-4 px-3 text-center">
    						<h3><a href="{{ route('product.show', $relatedProduct->id) }}">{{ $relatedProduct->name }}</a></h3>
    						<div class="d-flex">
    							<div class="pricing">
                                    @if($relatedProduct->sale_price)
                                        <p class="price">
                                            <span class="mr-2 price-dc">${{ number_format($relatedProduct->price, 2) }}</span>
                                            <span class="price-sale">${{ number_format($relatedProduct->sale_price, 2) }}</span>
                                        </p>
                                    @else
                                        <p class="price"><span>${{ number_format($relatedProduct->price, 2) }}</span></p>
                                    @endif
		    					</div>
	    					</div>
	    					<div class="bottom-area d-flex px-3">
	    						<div class="m-auto d-flex">
	    							<a href="{{ route('product.show', $relatedProduct->id) }}" class="add-to-cart d-flex justify-content-center align-items-center text-center">
	    								<span><i class="ion-ios-menu"></i></span>
	    							</a>
	    							<a href="#" class="buy-now d-flex justify-content-center align-items-center mx-1">
	    								<span><i class="ion-ios-cart"></i></span>
	    							</a>
	    							<a href="#" class="heart d-flex justify-content-center align-items-center ">
	    								<span><i class="ion-ios-heart"></i></span>
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
@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        var quantitiy=0;
        $('.quantity-right-plus').click(function(e){
            e.preventDefault();
            var quantity = parseInt($('#quantity').val());
            $('#quantity').val(quantity + 1);
        });

        $('.quantity-left-minus').click(function(e){
            e.preventDefault();
            var quantity = parseInt($('#quantity').val());
            if(quantity>0){
                $('#quantity').val(quantity - 1);
            }
        });
    });
</script>
@endsection 