@extends('layouts.app')

@section('title', __('Checkout'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">{{ __('Checkout') }}</h1>
            <div class="mt-4 flex items-center space-x-4 text-sm text-gray-600">
                <span class="flex items-center">
                    <span class="w-6 h-6 bg-indigo-600 text-white rounded-full flex items-center justify-center text-xs font-medium mr-2">1</span>
                    {{ __('Shipping') }}
                </span>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="flex items-center">
                    <span class="w-6 h-6 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-xs font-medium mr-2">2</span>
                    {{ __('Payment') }}
                </span>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="flex items-center">
                    <span class="w-6 h-6 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-xs font-medium mr-2">3</span>
                    {{ __('Review') }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Checkout Form -->
            <div class="lg:col-span-2 space-y-6">
                <form method="POST" action="{{ route('checkout.process') }}" id="checkout-form">
                    @csrf

                    <!-- Shipping Information -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Shipping Information') }}</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ __('First Name') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="first_name" 
                                       name="first_name" 
                                       value="{{ old('first_name', auth()->user()->first_name ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('first_name') border-red-300 @enderror"
                                       required>
                                @error('first_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ __('Last Name') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="last_name" 
                                       name="last_name" 
                                       value="{{ old('last_name', auth()->user()->last_name ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('last_name') border-red-300 @enderror"
                                       required>
                                @error('last_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Email Address') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', auth()->user()->email ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-300 @enderror"
                                   required>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Phone Number') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('phone') border-red-300 @enderror"
                                   required>
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Street Address') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="address" 
                                   name="address" 
                                   value="{{ old('address') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('address') border-red-300 @enderror"
                                   placeholder="{{ __('Street address, P.O. box, company name, c/o') }}"
                                   required>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ __('City') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="city" 
                                       name="city" 
                                       value="{{ old('city') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('city') border-red-300 @enderror"
                                       required>
                                @error('city')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="state" class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ __('State/Province') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="state" 
                                       name="state" 
                                       value="{{ old('state') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('state') border-red-300 @enderror"
                                       required>
                                @error('state')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ __('Postal Code') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="postal_code" 
                                       name="postal_code" 
                                       value="{{ old('postal_code') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('postal_code') border-red-300 @enderror"
                                       required>
                                @error('postal_code')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Country') }} <span class="text-red-500">*</span>
                            </label>
                            <select id="country" 
                                    name="country"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('country') border-red-300 @enderror"
                                    required>
                                <option value="">{{ __('Select Country') }}</option>
                                <option value="US" {{ old('country') == 'US' ? 'selected' : '' }}>{{ __('United States') }}</option>
                                <option value="CA" {{ old('country') == 'CA' ? 'selected' : '' }}>{{ __('Canada') }}</option>
                                <option value="LK" {{ old('country') == 'LK' ? 'selected' : '' }}>{{ __('Sri Lanka') }}</option>
                                <option value="IN" {{ old('country') == 'IN' ? 'selected' : '' }}>{{ __('India') }}</option>
                                <option value="GB" {{ old('country') == 'GB' ? 'selected' : '' }}>{{ __('United Kingdom') }}</option>
                                <option value="AU" {{ old('country') == 'AU' ? 'selected' : '' }}>{{ __('Australia') }}</option>
                            </select>
                            @error('country')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Payment Information') }}</h2>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('Payment Method') }} <span class="text-red-500">*</span>
                            </label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="payment_method" value="credit_card" 
                                           {{ old('payment_method', 'credit_card') == 'credit_card' ? 'checked' : '' }}
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                    <span class="ml-2 text-sm text-gray-700">{{ __('Credit/Debit Card') }}</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="payment_method" value="paypal" 
                                           {{ old('payment_method') == 'paypal' ? 'checked' : '' }}
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                    <span class="ml-2 text-sm text-gray-700">{{ __('PayPal') }}</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="payment_method" value="bank_transfer" 
                                           {{ old('payment_method') == 'bank_transfer' ? 'checked' : '' }}
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                    <span class="ml-2 text-sm text-gray-700">{{ __('Bank Transfer') }}</span>
                                </label>
                            </div>
                        </div>

                        <!-- Credit Card Fields (shown by default) -->
                        <div id="credit-card-fields" class="space-y-4">
                            <div>
                                <label for="card_number" class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ __('Card Number') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="card_number" 
                                       name="card_number" 
                                       value="{{ old('card_number') }}"
                                       placeholder="1234 5678 9012 3456"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('card_number') border-red-300 @enderror">
                                @error('card_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="md:col-span-2">
                                    <label for="card_expiry" class="block text-sm font-medium text-gray-700 mb-1">
                                        {{ __('Expiry Date') }} <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           id="card_expiry" 
                                           name="card_expiry" 
                                           value="{{ old('card_expiry') }}"
                                           placeholder="MM/YY"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('card_expiry') border-red-300 @enderror">
                                    @error('card_expiry')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="card_cvc" class="block text-sm font-medium text-gray-700 mb-1">
                                        {{ __('CVC') }} <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           id="card_cvc" 
                                           name="card_cvc" 
                                           value="{{ old('card_cvc') }}"
                                           placeholder="123"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('card_cvc') border-red-300 @enderror">
                                    @error('card_cvc')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="card_name" class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ __('Name on Card') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="card_name" 
                                       name="card_name" 
                                       value="{{ old('card_name') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('card_name') border-red-300 @enderror">
                                @error('card_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Payment method specific instructions -->
                        <div id="paypal-info" class="hidden mt-4 p-4 bg-blue-50 border border-blue-200 rounded-md">
                            <p class="text-sm text-blue-800">
                                <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                {{ __('You will be redirected to PayPal to complete your payment after reviewing your order.') }}
                            </p>
                        </div>

                        <div id="bank-transfer-info" class="hidden mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-md">
                            <p class="text-sm text-yellow-800">
                                <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                {{ __('Bank transfer instructions will be provided after order confirmation. Please allow 3-5 business days for processing.') }}
                            </p>
                        </div>
                    </div>

                    <!-- Order Notes -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Order Notes') }}</h2>
                        <div>
                            <label for="order_notes" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('Special Instructions') }} <span class="text-gray-500">({{ __('Optional') }})</span>
                            </label>
                            <textarea id="order_notes" 
                                      name="order_notes" 
                                      rows="3"
                                      placeholder="{{ __('Any special delivery instructions or notes...') }}"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('order_notes') border-red-300 @enderror">{{ old('order_notes') }}</textarea>
                            @error('order_notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-start">
                            <input type="checkbox" 
                                   id="terms_accepted" 
                                   name="terms_accepted" 
                                   value="1"
                                   {{ old('terms_accepted') ? 'checked' : '' }}
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded mt-0.5">
                            <label for="terms_accepted" class="ml-2 text-sm text-gray-700">
                                {{ __('I have read and agree to the') }}
                                <a href="{{ route('terms') }}" target="_blank" class="text-indigo-600 hover:text-indigo-500 underline">{{ __('Terms and Conditions') }}</a>
                                {{ __('and') }}
                                <a href="{{ route('privacy') }}" target="_blank" class="text-indigo-600 hover:text-indigo-500 underline">{{ __('Privacy Policy') }}</a>
                                <span class="text-red-500">*</span>
                            </label>
                        </div>
                        @error('terms_accepted')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </form>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Order Summary') }}</h2>
                    
                    <!-- Cart Items -->
                    <div class="space-y-4 mb-6">
                        @forelse($cartItems ?? [] as $item)
                            <div class="flex items-center space-x-3">
                                @if($item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" 
                                         alt="{{ $item->product->name }}" 
                                         class="w-12 h-12 object-cover rounded-md">
                                @else
                                    <div class="w-12 h-12 bg-gray-200 rounded-md flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $item->product->name }}</p>
                                    <p class="text-sm text-gray-500">{{ __('Qty: :quantity', ['quantity' => $item->quantity]) }}</p>
                                </div>
                                <p class="text-sm font-medium text-gray-900">${{ number_format($item->product->price * $item->quantity, 2) }}</p>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <p class="text-sm text-gray-500">{{ __('Your cart is empty') }}</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Order Totals -->
                    <div class="border-t pt-4 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ __('Subtotal') }}</span>
                            <span class="text-gray-900">${{ number_format($subtotal ?? 0, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ __('Shipping') }}</span>
                            <span class="text-gray-900">
                                @if(($shipping ?? 0) > 0)
                                    ${{ number_format($shipping, 2) }}
                                @else
                                    {{ __('Free') }}
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ __('Tax') }}</span>
                            <span class="text-gray-900">${{ number_format($tax ?? 0, 2) }}</span>
                        </div>
                        <div class="border-t pt-2 flex justify-between text-base font-semibold">
                            <span class="text-gray-900">{{ __('Total') }}</span>
                            <span class="text-gray-900">${{ number_format($total ?? 0, 2) }}</span>
                        </div>
                    </div>

                    <!-- Checkout Button -->
                    <div class="mt-6">
                        <button type="submit" 
                                form="checkout-form"
                                class="w-full bg-indigo-600 text-white py-3 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 font-medium transition duration-200">
                            {{ __('Complete Order') }}
                        </button>
                    </div>

                    <!-- Security Badge -->
                    <div class="mt-4 flex items-center justify-center text-xs text-gray-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        {{ __('Secure checkout powered by SSL encryption') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const creditCardFields = document.getElementById('credit-card-fields');
    const paypalInfo = document.getElementById('paypal-info');
    const bankTransferInfo = document.getElementById('bank-transfer-info');

    function togglePaymentFields() {
        const selectedMethod = document.querySelector('input[name="payment_method"]:checked').value;
        
        // Hide all payment-specific elements
        creditCardFields.style.display = 'none';
        paypalInfo.classList.add('hidden');
        bankTransferInfo.classList.add('hidden');

        // Show relevant elements based on selection
        switch(selectedMethod) {
            case 'credit_card':
                creditCardFields.style.display = 'block';
                break;
            case 'paypal':
                paypalInfo.classList.remove('hidden');
                break;
            case 'bank_transfer':
                bankTransferInfo.classList.remove('hidden');
                break;
        }
    }

    // Initialize on page load
    togglePaymentFields();

    // Add event listeners to payment method radio buttons
    paymentMethods.forEach(function(radio) {
        radio.addEventListener('change', togglePaymentFields);
    });

    // Format card number input
    const cardNumberInput = document.getElementById('card_number');
    if (cardNumberInput) {
        cardNumberInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s/g, '').replace(/[^0-9]/gi, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            e.target.value = formattedValue;
        });
    }

    // Format expiry date input
    const cardExpiryInput = document.getElementById('card_expiry');
    if (cardExpiryInput) {
        cardExpiryInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value;
        });
    }
});
</script>
@endpush

@endsection