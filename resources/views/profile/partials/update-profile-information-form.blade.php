<section>
    <!-- Tabs Navigation -->
    <!-- <div class="tabs">
        <button id="profile-info-tab" class="tab-button active" type="button">Profile Information</button>
        <button id="address-info-tab" class="tab-button" type="button">Address Information</button>
        <button id="contact-info-tab" class="tab-button" type="button">Contact Information</button>
    </div> -->

    <!-- Profile Information Tab -->
    <div id="profile-info" class="tab-content">
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Profile Information') }}
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                {{ __("Update your account's profile information.") }}
            </p>
        </header>

        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
            @csrf
        </form>

        <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
            @csrf
            @method('patch')

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" 
                            :value="old('name', $user->name)" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" 
                            :value="old('email', $user->email)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />

                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <div>
                                <p class="text-sm mt-2 text-gray-800">
                                    {{ __('Your email address is unverified.') }}

                                    <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        {{ __('Click here to re-send the verification email.') }}
                                    </button>
                                </p>

                                @if (session('status') === 'verification-link-sent')
                                    <p class="mt-2 font-medium text-sm text-green-600">
                                        {{ __('A new verification link has been sent to your email address.') }}
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <x-input-label for="phone" :value="__('Phone')" />
                        <x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-full" 
                            :value="old('phone', $user->phone)" required 
                            placeholder="Enter your phone number"
                            pattern="[0-9\s\-\+\(\)]*"
                            minlength="6"
                            maxlength="20" />
                        <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <x-primary-button>{{ __('Save Profile') }}</x-primary-button>
                @if (session('status') === 'profile-updated')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-gray-600">{{ __('Saved.') }}</p>
                @endif
            </div>
        </form>
    </div>

    <!-- Address Information Tab -->
    <div id="address-info" class="tab-content">
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Address Information') }}
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                {{ __("Update your address details.") }}
            </p>
        </header>

        <form method="post" action="{{ route('profile.update.address') }}" class="mt-6 space-y-6">
            @csrf
            @method('patch')

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <x-input-label for="country" :value="__('State / Country')" />
                        <select name="country" id="country" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            @php
                                $countries = ['Egypt', 'France', 'Italy', 'Philippines', 'South Korea', 'Hongkong', 'Japan'];
                                $selectedCountry = old('country', $user->country);
                            @endphp
                            @foreach($countries as $country)
                                <option value="{{ $country }}" {{ $selectedCountry == $country ? 'selected' : '' }}>
                                    {{ $country }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('country')" />
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <x-input-label for="street_address" :value="__('Street Address')" />
                        <x-text-input id="street_address" name="street_address" type="text" class="mt-1 block w-full" 
                            :value="old('street_address', $user->street_address)" required placeholder="House number and street name" />
                        <x-input-error class="mt-2" :messages="$errors->get('street_address')" />
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <x-input-label for="apartment" :value="__('Apartment (optional)')" />
                        <x-text-input id="apartment" name="apartment" type="text" class="mt-1 block w-full" 
                            :value="old('apartment', $user->apartment)" placeholder="Apartment, suite, unit etc" />
                        <x-input-error class="mt-2" :messages="$errors->get('apartment')" />
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <x-input-label for="city" :value="__('Town / City')" />
                        <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" 
                            :value="old('city', $user->city)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('city')" />
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <x-input-label for="postcode" :value="__('Postcode / ZIP')" />
                        <x-text-input id="postcode" name="postcode" type="text" class="mt-1 block w-full" 
                            :value="old('postcode', $user->postcode)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('postcode')" />
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <x-primary-button>{{ __('Save Address') }}</x-primary-button>
                @if (session('status') === 'address-updated')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-gray-600">{{ __('Address Saved.') }}</p>
                @endif
            </div>
        </form>
    </div>

    <style>
        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .tab-button {
            padding: 10px 20px;
            border: none;
            background: #ccc;
            cursor: pointer;
            border-radius: 5px;
        }
        .tab-button.active {
            background: #007bff;
            color: white;
        }
        .tab-content {
            padding: 20px;
            border-radius: 5px;
            background: #fff;
        }
        .row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .col-md-12 {
            grid-column: span 2;
        }
        .form-group {
            margin-bottom: 1rem;
        }
    </style>
</section>
