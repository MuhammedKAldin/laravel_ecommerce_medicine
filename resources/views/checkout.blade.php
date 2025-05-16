@extends('layout.layout')

@section('content')

    <div class="hero-wrap hero-bread" style="background-image: url('images/bg_1.jpg');">
      <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center">
          <div class="col-md-9 ftco-animate text-center">
          	<p class="breadcrumbs"><span class="mr-2"><a href="{{ route('home') }}">Home</a></span> <span>Checkout</span></p>
            <h1 class="mb-0 bread">Checkout</h1>
          </div>
        </div>
      </div>
    </div>

    <section class="ftco-section">
      <div class="container">
        @if(session('error'))
            <div class="mb-3">
                <span class="badge bg-danger">{{ session('error') }}</span>
            </div>
        @endif
        
        <div class="row justify-content-center">
          <div class="col-xl-7 ftco-animate" style="color: black!important;">
			<form action="{{ route('place.order') }}" method="POST" class="billing-form" style="color: black!important;">
			@csrf
				<h3 class="mb-4 billing-heading">Billing Details</h3>
	          	<div class="row align-items-end">
	          		<div class="col-md-6">
	                <div class="form-group">
	                	<label for="firstname">First Name</label>
	                  <input type="text" name="firstname" class="form-control @error('firstname') is-invalid @enderror" 
	                    @if(isset($user))
	                        value="{{ old('firstname', $user->first_name ?: explode(' ', $user->name)[0]) }}" 
	                    @else
	                        value="{{ old('firstname') }}"
	                    @endif
	                    placeholder="First Name" style="color: black!important;">
                      @error('firstname')
                          <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
	                </div>
	              </div>
	              <div class="col-md-6">
	                <div class="form-group">
	                	<label for="lastname">Last Name</label>
	                  <input type="text" name="lastname" class="form-control @error('lastname') is-invalid @enderror" 
	                    @if(isset($user))
	                        value="{{ old('lastname', $user->last_name ?: explode(' ', $user->name)[1]) }}" 
	                    @else
	                        value="{{ old('lastname') }}"
	                    @endif
	                    placeholder="Last Name" style="color: black!important;">
                      @error('lastname')
                          <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
	                </div>
                </div>
                <div class="w-100"></div>
		            <div class="col-md-12">
		            	<div class="form-group">
		            		<label for="country">State / Country</label>
		            		<div class="select-wrap">
		                  <div class="icon"></div>
		                  <select name="country" id="country" class="form-control @error('country') is-invalid @enderror">
		                  	@php
		                  		$countries = ['Egypt', 'France', 'Italy', 'Philippines', 'South Korea', 'Hongkong', 'Japan'];
		                  	@endphp
		                  	@foreach($countries as $country)
								@if(isset($user))
									<option value="{{ $country }}" {{ old('country', $user->country) == $country ? 'selected' : '' }}>
										{{ $country }}
									</option>
								@else
									<option value="{{ $country }}" {{ old('country') == $country ? 'selected' : '' }}>
										{{ $country }}
									</option>
								@endif
		                  	@endforeach
		                  </select>
                          @error('country')
                              <div class="invalid-feedback">{{ $message }}</div>
                          @enderror
		                </div>
		            	</div>
		            </div>
		            <div class="w-100"></div>
		            <div class="col-md-6">
		            	<div class="form-group">
	                	<label for="street_address">Street Address</label>
	                  <input type="text" name="street_address" class="form-control @error('street_address') is-invalid @enderror" 
	                    @if(isset($user))
	                        value="{{ old('street_address', $user->street_address) }}" 
	                    @else
	                        value="{{ old('street_address') }}"
	                    @endif
	                    placeholder="House number and street name" style="color: black!important;">
                      @error('street_address')
                          <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
	                </div>
		            </div>
		            <div class="col-md-6">
		            	<div class="form-group">
	                  <input type="text" name="apartment" class="form-control @error('apartment') is-invalid @enderror" 
	                    @if(isset($user))
	                        value="{{ old('apartment', $user->apartment) }}" 
	                    @else
	                        value="{{ old('apartment') }}"
	                    @endif
	                    placeholder="Apartment, suite, unit etc: (optional)" style="color: black!important;">
                      @error('apartment')
                          <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
	                </div>
		            </div>
		            <div class="w-100"></div>
		            <div class="col-md-6">
		            	<div class="form-group">
	                	<label for="city">Town / City</label>
	                  <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" 
	                    @if(isset($user))
	                        value="{{ old('city', $user->city) }}" 
	                    @else
	                        value="{{ old('city') }}"
	                    @endif
	                    style="color: black!important;">
                      @error('city')
                          <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
	                </div>
		            </div>
		            <div class="col-md-6">
		            	<div class="form-group">
		            		<label for="postcode">Postcode / ZIP *</label>
	                  <input type="text" name="postcode" class="form-control @error('postcode') is-invalid @enderror" 
	                    @if(isset($user))
	                        value="{{ old('postcode', $user->postcode) }}" 
	                    @else
	                        value="{{ old('postcode') }}"
	                    @endif
	                    style="color: black!important;">
                      @error('postcode')
                          <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
	                </div>
		            </div>
		            <div class="w-100"></div>
		            <div class="col-md-6">
	                <div class="form-group">
	                	<label for="phone">Phone</label>
	                  <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
	                    @if(isset($user))
	                        value="{{ old('phone', $user->phone) }}" 
	                    @else
	                        value="{{ old('phone') }}"
	                    @endif
	                    placeholder="Enter your phone number" style="color: black!important;">
                      @error('phone')
                          <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
	                </div>
	              </div>
	              <div class="col-md-6">
	                <div class="form-group">
	                	<label for="email">Email Address</label>
	                  <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
	                    @if(isset($user))
	                        value="{{ old('email', $user->email) }}" 
	                    @else
	                        value="{{ old('email') }}"
	                    @endif
	                    placeholder="Enter your email" style="color: black!important;">
                      @error('email')
                          <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
	                </div>
                </div>
                <div class="w-100"></div>
                <!-- <div class="col-md-12">
                	<div class="form-group mt-4">
						<div class="radio">
						  <label class="mr-3"><input type="radio" name="optradio"> Create an Account? </label>
						  <label><input type="radio" name="optradio"> Ship to different address</label>
						</div>
					</div>
                </div> -->
	            </div>
	          
	          	<div class="row mt-5 pt-3">
	          		<div class="col-md-12">
	          			<div class="cart-detail p-3 p-md-4">
	          				<h3 class="billing-heading mb-4">Payment Method</h3>
							<div class="form-group">
								<div class="col-md-12 @error('payment_method') is-invalid @enderror">
									@foreach($paymentMethods as $method)
									<div class="radio">
										@if(isset($user))
											<label>
												<input type="radio" name="payment_method" value="{{ $method->value }}" 
													{{ old('payment_method', $user->payment_method) == $method->value ? 'checked' : '' }}> 
												{{ ucwords(str_replace('_', ' ', $method->value)) }}
											</label>
										@else
											<label>
												<input type="radio" name="payment_method" value="{{ $method->value }}" 
													{{ old('payment_method') == $method->value ? 'checked' : '' }}> 
												{{ ucwords(str_replace('_', ' ', $method->value)) }}
											</label>
										@endif
									</div>
									@endforeach
								</div>
							</div>
                            @error('payment_method')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
							<div class="form-group mt-4">
								<div class="col-md-12">
									<div class="checkbox">
										@if(isset($user))
											<label><input type="checkbox" name="terms" value="1" {{ old('terms', $user->terms) ? 'checked' : '' }} class="@error('terms') is-invalid @enderror"> I have read and accept the terms and conditions</label>
										@else
											<label><input type="checkbox" name="terms" value="1" {{ old('terms') ? 'checked' : '' }} class="@error('terms') is-invalid @enderror"> I have read and accept the terms and conditions</label>
										@endif
									</div>
                                    @error('terms')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
								</div>
							</div>
							<p><button type="submit" class="btn btn-primary py-3 px-4">Place an order</button></p>
						</div>
	          		</div>
	          	</div>
			</form>
          </div> <!-- .col-md-8 -->
          <div class="col-xl-5">
	          <div class="row mt-5 pt-3">
	          	<div class="col-md-12 d-flex mb-5">
	          		<div class="cart-detail cart-total p-3 p-md-4">
	          			<h3 class="billing-heading mb-4">Cart Total</h3>
	          			<p class="d-flex">
		    				<span>Subtotal</span>
		    				<span>${{ number_format($subtotal, 2) }}</span>
		    			</p>
		    			<p class="d-flex">
		    				<span>Delivery</span>
		    				<span>${{ number_format($delivery, 2) }}</span>
		    			</p>
		    			<p class="d-flex">
		    				<span>Discount</span>
		    				<span>${{ number_format($discount, 2) }}</span>
		    			</p>
		    			<hr>
		    			<p class="d-flex total-price">
		    				<span>Total</span>
		    				<span>${{ number_format($subtotal - $discount + $delivery, 2) }}</span>
		    			</p>
					</div>
	          	</div>
	          </div>
          </div>
        </div>
      </div>
    </section>

    @if(config('app.debug') && isset($user))
    <div class="container" style="margin-bottom: 20px;">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">Debug Information</div>
                    <div class="card-body">
                        <h5>User Data:</h5>
                        <pre>{{ print_r($user->toArray(), true) }}</pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection