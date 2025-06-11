@extends('layouts.app')

@section('title', __('messages.shopping_cart'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold text-gray-900">{{ __('messages.shopping_cart') }}</h1>
            <a href="{{ route('product.index') }}" 
               class="text-indigo-600 hover:text-indigo-900 font-medium">
                {{ __('messages.continue_shopping') }}
            </a>
        </div>

        @if($cartItems->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">{{ __('messages.cart_items') }}</h2>
                        </div>
                        
                        <div class="divide-y divide-gray-200">
                            @foreach($cartItems as $item)
                                <div class="p-6 flex items-center space-x-4">
                                    <!-- Product Image -->
                                    <div class="flex-shrink-0">
                                        <div class="w-20 h-20 bg-gray-200 rounded-md flex items-center justify-center">
                                            @if($item->product->image)
                                                <img src="{{ Storage::url($item->product->image) }}" 
                                                     alt="{{ $item->product->name }}" 
                                                     class="w-full h-full object-cover rounded-md">
                                            @else
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Product Details -->
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-lg font-medium text-gray-900">{{ $item->product->name }}</h3>
                                        <p class="text-sm text-gray-500">{{ __(ucfirst($item->product->category->name)) }}</p>
                                        <p class="text-lg font-semibold text-indigo-600">${{ number_format($item->product->price, 2) }}</p>
                                    </div>

                                    <!-- Quantity Controls -->
                                    <div class="flex items-center space-x-3">
                                        <form method="POST" action="{{ route('cart.update', $item) }}" class="flex items-center space-x-2">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" name="action" value="decrease" 
                                                    class="w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center text-gray-600 hover:text-gray-800 transition duration-200"
                                                    {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                </svg>
                                            </button>
                                            <span class="w-12 text-center font-medium text-gray-900">{{ $item->quantity }}</span>
                                            <button type="submit" name="action" value="increase" 
                                                    class="w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center text-gray-600 hover:text-gray-800 transition duration-200"
                                                    {{ $item->quantity >= $item->product->stock_quantity ? 'disabled' : '' }}>
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Subtotal -->
                                    <div class="text-right">
                                        <p class="text-lg font-semibold text-gray-900">
                                            ${{ number_format($item->product->price * $item->quantity, 2) }}
                                        </p>
                                    </div>

                                    <!-- Remove Button -->
                                    <div>
                                        <form method="POST" action="{{ route('cart.remove', $item) }}" 
                                              onsubmit="return confirm('{{ __('messages.remove_item') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-800 p-2 rounded-full hover:bg-red-50 transition duration-200">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-6 sticky top-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('messages.order_summary') }}</h2>
                        
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">{{ __('messages.subtotal') }}</span>
                                <span class="font-medium">${{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">{{ __('messages.tax') }}</span>
                                <span class="font-medium">${{ number_format($tax, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">{{ __('messages.shipping') }}</span>
                                <span class="font-medium">
                                    @if($subtotal >= 100)
                                        <span class="text-green-600">{{ __('messages.free') }}</span>
                                    @else
                                        ${{ number_format($shipping, 2) }}
                                    @endif
                                </span>
                            </div>
                            <div class="border-t border-gray-200 pt-3">
                                <div class="flex justify-between">
                                    <span class="text-lg font-semibold text-gray-900">{{ __('messages.total') }}</span>
                                    <span class="text-lg font-semibold text-gray-900">${{ number_format($total, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <a href="{{ route('checkout.index') }}" 
                               class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-center py-3 px-4 rounded-md font-medium transition duration-200 block">
                                {{ __('messages.proceed_to_checkout') }}
                            </a>
                            
                            <form method="POST" action="{{ route('cart.clear') }}" 
                                  onsubmit="return confirm('{{ __('messages.clear_all_items') }}')"
                                  class="w-full">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-4 rounded-md font-medium transition duration-200">
                                    {{ __('messages.clear_cart') }}
                                </button>
                            </form>
                        </div>

                        <!-- Continue Shopping -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <a href="{{ route('product.index') }}" 
                               class="text-indigo-600 hover:text-indigo-900 text-sm font-medium flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                {{ __('messages.continue_shopping') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty Cart -->
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m0 0h7.5M17 18a2 2 0 11-4 0 2 2 0 014 0zM9 18a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('messages.cart_empty') }}</h3>
                <p class="text-gray-500 mb-6">{{ __('messages.get_started') }}</p>
                <a href="{{ route('product.index') }}" 
                   class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition duration-200">
                    {{ __('messages.shop_now') }}
                </a>
            </div>
        @endif
    </div>
</div>
@endsection