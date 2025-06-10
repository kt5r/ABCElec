@extends('layouts.app')

@section('title', __('Order Details'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('profile.index') }}" class="text-indigo-600 hover:text-indigo-500 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                {{ __('Back to Profile') }}
            </a>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <!-- Order Header -->
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ __('Order') }} #{{ $order->id }}</h1>
                        <p class="text-gray-600 mt-1">{{ __('Placed on') }} {{ $order->created_at->format('M d, Y \a\t h:i A') }}</p>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                            @elseif($order->status === 'shipped') bg-indigo-100 text-indigo-800
                            @elseif($order->status === 'delivered') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Order Items -->
                    <div class="lg:col-span-2">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Order Items') }}</h2>
                        <div class="space-y-4">
                            @foreach($order->orderItems as $item)
                                <div class="flex items-center space-x-4 p-4 border rounded-lg">
                                    @if($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" 
                                             alt="{{ $item->product->name }}" 
                                             class="w-16 h-16 object-cover rounded-lg">
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    
                                    <div class="flex-1">
                                        <h3 class="font-medium text-gray-900">{{ $item->product->name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $item->product->category->name ?? __('Uncategorized') }}</p>
                                        <div class="flex justify-between items-center mt-2">
                                            <span class="text-sm text-gray-600">{{ __('Quantity: :qty', ['qty' => $item->quantity]) }}</span>
                                            <span class="font-medium text-gray-900">${{ number_format($item->price, 2) }} {{ __('each') }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="text-right">
                                        <span class="font-semibold text-gray-900">${{ number_format($item->price * $item->quantity, 2) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Order Summary & Details -->
                    <div class="space-y-6">
                        <!-- Order Summary -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="font-semibold text-gray-900 mb-3">{{ __('Order Summary') }}</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ __('Subtotal') }}</span>
                                    <span class="text-gray-900">${{ number_format($order->orderItems->sum(function($item) { return $item->price * $item->quantity; }), 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ __('Tax') }}</span>
                                    <span class="text-gray-900">${{ number_format($order->tax_amount ?? 0, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ __('Shipping') }}</span>
                                    <span class="text-gray-900">${{ number_format($order->shipping_amount ?? 0, 2) }}</span>
                                </div>
                                <div class="border-t pt-2 mt-2">
                                    <div class="flex justify-between">
                                        <span class="font-semibold text-gray-900">{{ __('Total') }}</span>
                                        <span class="font-semibold text-gray-900">${{ number_format($order->total_amount, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Information -->
                        @if($order->shipping_address)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="font-semibold text-gray-900 mb-3">{{ __('Shipping Address') }}</h3>
                            <div class="text-sm text-gray-600 space-y-1">
                                <p class="font-medium text-gray-900">{{ $order->shipping_name }}</p>
                                <p>{{ $order->shipping_address }}</p>
                                <p>{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}</p>
                                <p>{{ $order->shipping_country }}</p>
                                @if($order->shipping_phone)
                                    <p class="mt-2">{{ __('Phone: :phone', ['phone' => $order->shipping_phone]) }}</p>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Payment Information -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="font-semibold text-gray-900 mb-3">{{ __('Payment Information') }}</h3>
                            <div class="text-sm text-gray-600 space-y-1">
                                <p>{{ __('Payment Method: :method', ['method' => ucfirst($order->payment_method ?? 'Card')]) }}</p>
                                <p>{{ __('Payment Status: :status', ['status' => ucfirst($order->payment_status ?? 'Pending')]) }}</p>
                                @if($order->transaction_id)
                                    <p>{{ __('Transaction ID: :id', ['id' => $order->transaction_id]) }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Order Timeline -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="font-semibold text-gray-900 mb-3">{{ __('Order Timeline') }}</h3>
                            <div class="space-y-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                    <div class="text-sm">
                                        <p class="font-medium text-gray-900">{{ __('Order Placed') }}</p>
                                        <p class="text-gray-600">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                                    </div>
                                </div>
                                
                                @if($order->status !== 'pending')
                                <div class="flex items-center space-x-3">
                                    <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                    <div class="text-sm">
                                        <p class="font-medium text-gray-900">{{ __('Order Confirmed') }}</p>
                                        <p class="text-gray-600">{{ $order->updated_at->format('M d, Y h:i A') }}</p>
                                    </div>
                                </div>
                                @endif
                                
                                @if(in_array($order->status, ['shipped', 'delivered']))
                                <div class="flex items-center space-x-3">
                                    <div class="w-3 h-3 bg-indigo-500 rounded-full"></div>
                                    <div class="text-sm">
                                        <p class="font-medium text-gray-900">{{ __('Order Shipped') }}</p>
                                        <p class="text-gray-600">{{ __('Tracking information will be provided') }}</p>
                                    </div>
                                </div>
                                @endif
                                
                                @if($order->status === 'delivered')
                                <div class="flex items-center space-x-3">
                                    <div class="w-3 h-3 bg-green-600 rounded-full"></div>
                                    <div class="text-sm">
                                        <p class="font-medium text-gray-900">{{ __('Order Delivered') }}</p>
                                        <p class="text-gray-600">{{ __('Thank you for your purchase!') }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="space-y-2">
                            @if($order->status === 'pending')
                                <button onclick="if(confirm('{{ __('Are you sure you want to cancel this order?') }}')) { document.getElementById('cancel-form').submit(); }" 
                                        class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-md transition duration-200">
                                    {{ __('Cancel Order') }}
                                </button>
                                <form id="cancel-form" action="{{ route('orders.cancel', $order) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('PATCH')
                                </form>
                            @endif
                            
                            <a href="{{ route('orders.invoice', $order) }}" 
                               class="w-full bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-md transition duration-200 inline-block text-center">
                                {{ __('Download Invoice') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection