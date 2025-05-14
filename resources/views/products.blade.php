@extends('layout.layout')

@section('content')
    <div class="hero-wrap hero-bread" style="background-image: url('images/bg_1.jpg');">
      <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center">
          <div class="col-md-9 ftco-animate text-center">
          	<p class="breadcrumbs"><span class="mr-2"><a href="index.html">Home</a></span> <span>Products</span></p>
            <h1 class="mb-0 bread">Products</h1>
          </div>
        </div>
      </div>
    </div>

    <section class="ftco-section">
    	<div class="container">
    		<div class="row justify-content-center">
    			<div class="col-md-12 mb-5 text-center">
    				<ul class="product-category">
    					<li><a href="{{ route('products') }}" class="{{ is_null($activeCategory) ? 'active' : '' }}">All</a></li>
						@foreach ($categories as $category)
							<li><a href="{{ route('products', ['category' => $category->id]) }}" 
                                class="{{ $activeCategory && $activeCategory->id == $category->id ? 'active' : '' }}">
                                {{ $category->name }}
                            </a></li>
						@endforeach
    				</ul>
    			</div>
    		</div>
    		<div class="row">
    			@foreach ($products as $product)
    				<div class="col-md-6 col-lg-3 ftco-animate">
						<div class="product">
							<a href="{{ route('product.show', $product->id) }}" class="img-prod">
								<img class="img-fluid" src="{{ $product->image }}" alt="{{ $product->name }}">
								<div class="overlay"></div>
							</a>
							<div class="text py-3 pb-4 px-3 text-center">
								<h3><a href="{{ route('product.show', $product->id) }}">{{ $product->name }}</a></h3>
								<div class="d-flex">
									<div class="pricing">
										<!-- <p class="price"><span class="mr-2 price-dc">${{ $product->price }}</span> -->
										<span class="price-sale">${{ $product->price }}</span>
										</p>
									</div>
								</div>
								<div class="bottom-area d-flex px-3">
									<div class="m-auto d-flex">
										<a href="{{ route('product.show', $product->id) }}" class="add-to-cart d-flex justify-content-center align-items-center text-center">
											<span><i class="ion-ios-menu"></i></span>
										</a>
										<a href="{{ route('cart') }}" class="buy-now d-flex justify-content-center align-items-center mx-1">
											<span><i class="ion-ios-cart"></i></span>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
    			@endforeach
    		</div>
    		<div class="row mt-5">
          <div class="col text-center">
            <div class="block-27">
              <ul>
                <li><a href="{{ $products->previousPageUrl() }}">&lt;</a></li>
                @for ($i = 1; $i <= $products->lastPage(); $i++)
					<li class="{{ $products->currentPage() == $i ? 'active' : '' }}"><a href="{{ $products->url($i) }}">{{ $i }}</a></li>
				@endfor
				@if ($products->currentPage() < $products->lastPage())
					<li><a href="{{ $products->nextPageUrl() }}">&gt;</a></li>
				@endif
              </ul>
            </div>
          </div>
        </div>
    	</div>
    </section>
@endsection