@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="md:flex">
            <div class="md:w-1/2">
                <div class="aspect-w-16 aspect-h-12 bg-gray-200">
                    @if($product->images && count(json_decode($product->images)) > 0)
                        <img src="{{ Storage::url(json_decode($product->images)[0]) }}" alt="{{ $product->name }}" class="w-full h-96 object-cover">
                    @else
                        <div class="w-full h-96 bg-gray-300 flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                </div>
            </div>
            <div class="md:w-1/2 p-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>
                <p class="text-gray-600 mb-6">{{ $product->description }}</p>
                
                <div class="flex justify-between items-center mb-6">
                    <span class="text-3xl font-bold text-blue-600">${{ number_format($product->price, 2) }}</span>
                    <span class="text-sm text-gray-500">{{ __('messages.category') }} : {{ $product->category->name }}</span>
                </div>

                <!-- Stock Information -->
                @if($product->in_stock)
                    <div class="mb-4">
                        <span class="text-green-600 text-sm font-medium">
                            ✓ {{ __('messages.in_stock') }}
                            @if($product->stock_quantity <= 10)
                                ({{ $product->stock_quantity }} left)
                            @endif
                        </span>
                    </div>
                @else
                    <div class="mb-4">
                        <span class="text-red-600 text-sm font-medium">x {{ __('messages.out_of_stock') }}</span>
                    </div>
                @endif

                <!-- Add to Cart Form -->
                @if($product->status && $product->in_stock)
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" id="addToCartForm">
                        @csrf
                        
                        <!-- Quantity Selector -->
                        <div class="mb-6">
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('messages.quantity') }}
                            </label>
                            <div class="flex items-center space-x-3">
                                <button type="button" id="decreaseBtn" class="w-10 h-10 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                    </svg>
                                </button>
                                
                                <input type="number" 
                                       id="quantity" 
                                       name="quantity" 
                                       value="1" 
                                       min="1" 
                                       max="{{ $product->stock_quantity }}"
                                       class="w-20 px-3 py-2 border border-gray-300 rounded-md text-center focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                
                                <button type="button" id="increaseBtn" class="w-10 h-10 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">{{ __('messages.maximum') }} : {{ $product->stock_quantity }}</p>
                        </div>

                        <!-- Total Price Display -->
                        <div class="mb-6">
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-md">
                                <span class="text-sm font-medium text-gray-700">{{ __('messages.total') }} : </span>
                                <span id="totalPrice" class="text-xl font-bold text-blue-600">
                                    ${{ number_format($product->price, 2) }}
                                </span>
                            </div>
                        </div>

                        <button type="submit" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md text-lg font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            {{ __('messages.add_to_cart') }}
                        </button>
                    </form>
                @else
                    <button disabled class="w-full bg-gray-400 text-white px-6 py-3 rounded-md text-lg font-medium cursor-not-allowed">
                        {{ __('messages.unavailable') }}
                    </button>
                @endif

                <!-- Success/Error Messages -->
                <div id="messageContainer" class="mt-4"></div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const quantityInput = document.getElementById('quantity');
    const decreaseBtn = document.getElementById('decreaseBtn');
    const increaseBtn = document.getElementById('increaseBtn');
    const totalPriceElement = document.getElementById('totalPrice');
    const addToCartForm = document.getElementById('addToCartForm');
    const messageContainer = document.getElementById('messageContainer');
    
    const productPrice = {{ $product->price }};
    const maxStock = {{ $product->stock_quantity }};
    
    function updateTotalPrice() {
        const quantity = parseInt(quantityInput.value) || 1;
        const total = productPrice * quantity;
        totalPriceElement.textContent = '$' + total.toFixed(2);
    }
    
    function updateButtons() {
        const quantity = parseInt(quantityInput.value) || 1;
        decreaseBtn.disabled = quantity <= 1;
        increaseBtn.disabled = quantity >= maxStock;
        
        // Update button styles
        decreaseBtn.classList.toggle('opacity-50', quantity <= 1);
        increaseBtn.classList.toggle('opacity-50', quantity >= maxStock);
    }
    
    // Decrease quantity
    decreaseBtn.addEventListener('click', function() {
        const currentValue = parseInt(quantityInput.value) || 1;
        if (currentValue > 1) {
            quantityInput.value = currentValue - 1;
            updateTotalPrice();
            updateButtons();
        }
    });
    
    // Increase quantity
    increaseBtn.addEventListener('click', function() {
        const currentValue = parseInt(quantityInput.value) || 1;
        if (currentValue < maxStock) {
            quantityInput.value = currentValue + 1;
            updateTotalPrice();
            updateButtons();
        }
    });
    
    // Handle direct input
    quantityInput.addEventListener('input', function() {
        let value = parseInt(this.value) || 1;
        
        // Ensure value is within bounds
        if (value < 1) value = 1;
        if (value > maxStock) value = maxStock;
        
        this.value = value;
        updateTotalPrice();
        updateButtons();
    });
    
    // Handle form submission with AJAX
    addToCartForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitButton = this.querySelector('button[type="submit"]');
        
        // Disable button and show loading state
        submitButton.disabled = true;
        submitButton.textContent = '{{ __('messages.adding') }}...';
        
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            showMessage(data.message, data.success ? 'success' : 'error');
            
            if (data.success) {
                // Update cart count if you have a cart counter in your layout
                const cartCountElement = document.querySelector('.cart-count');
                if (cartCountElement && data.cart_count) {
                    cartCountElement.textContent = data.cart_count;
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('An error occurred. Please try again.', 'error');
        })
        .finally(() => {
            // Re-enable button
            submitButton.disabled = false;
            submitButton.textContent = '{{ __("messages.add_to_cart") }}';
        });
    });
    
    function showMessage(message, type) {
        const alertClass = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
        
        messageContainer.innerHTML = `
            <div class="border-l-4 p-4 ${alertClass}" role="alert">
                <div class="flex">
                    <div class="ml-3">
                        <p class="text-sm">${message}</p>
                    </div>
                    <div class="ml-auto pl-3">
                        <button class="text-gray-400 hover:text-gray-600" onclick="this.parentElement.parentElement.parentElement.remove()">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        // Auto-hide success messages after 5 seconds
        if (type === 'success') {
            setTimeout(() => {
                const alert = messageContainer.querySelector('.border-green-400');
                if (alert) {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }
            }, 5000);
        }
    }
    
    // Initialize
    updateTotalPrice();
    updateButtons();
});
</script>
@endsection