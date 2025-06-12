{{-- resources/views/profile/order-details.blade.php --}}
@extends('layouts.app')

@section('title', __('profile.order_details') . ' - #' . $order->order_number)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-800">{{ __('profile.order_details') }}</h1>
            <a href="{{ route('profile.order-history') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                ← {{ __('profile.back_to_orders') }}
            </a>
        </div>

        {{-- Order Info Card --}}
        <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gray-50 border-b">
                <div class="flex flex-wrap items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">{{ __('profile.order') }} #{{ $order->order_number }}</h2>
                        <p class="text-gray-600">{{ __('profile.placed_on') }} {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
                    </div>
                    <div class="text-right">
                        <span class="px-3 py-1 text-sm font-semibold rounded-full 
                            @if($order->status === 'completed') bg-green-100 text-green-800
                            @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                            @elseif($order->status === 'shipped') bg-indigo-100 text-indigo-800
                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst(__('profile.' . $order->status)) }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Order Items --}}
            <div class="px-6 py-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('profile.order_items') }}</h3>
                <div class="space-y-4">
                    @foreach($order->orderItems as $item)
                    <div class="flex items-center space-x-4 p-4 border rounded-lg">
                        @if($item->product->image)
                        <img src="{{ asset('storage/' . $item->product->image) }}" 
                             alt="{{ $item->product->name }}" 
                             class="w-16 h-16 object-cover rounded-md">
                        @else
                        <div class="w-16 h-16 bg-gray-200 rounded-md flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        @endif
                        
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-800">{{ $item->product->name }}</h4>
                            <p class="text-gray-600 text-sm">{{ __('profile.quantity') }}: {{ $item->quantity }}</p>
                            @if($item->product->description)
                            <p class="text-gray-500 text-sm">{{ Str::limit($item->product->description, 100) }}</p>
                            @endif
                        </div>
                        
                        <div class="text-right">
                            <div class="font-medium text-gray-800">${{ number_format($item->price, 2) }}</div>
                            @if($item->quantity > 1)
                            <div class="text-sm text-gray-500">{{ $item->quantity }} × ${{ number_format($item->price / $item->quantity, 2) }}</div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Order Summary --}}
            <div class="px-6 py-4 bg-gray-50 border-t">
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">{{ __('profile.subtotal') }}:</span>
                        <span class="text-gray-800">${{ number_format($order->subtotal ?? $order->total_amount, 2) }}</span>
                    </div>
                    
                    @if($order->tax_amount > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">{{ __('profile.tax') }}:</span>
                        <span class="text-gray-800">${{ number_format($order->tax_amount, 2) }}</span>
                    </div>
                    @endif
                    
                    @if($order->shipping_amount > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">{{ __('profile.shipping') }}:</span>
                        <span class="text-gray-800">${{ number_format($order->shipping_amount, 2) }}</span>
                    </div>
                    @endif
                    
                    @if($order->discount_amount > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">{{ __('profile.discount') }}:</span>
                        <span class="text-green-600">-${{ number_format($order->discount_amount, 2) }}</span>
                    </div>
                    @endif
                    
                    <hr class="my-2">
                    
                    <div class="flex justify-between text-lg font-semibold">
                        <span class="text-gray-800">{{ __('profile.total') }}:</span>
                        <span class="text-gray-900">${{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Shipping & Billing Info --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            {{-- Shipping Address --}}
            @if($order->shipping_address)
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('profile.shipping_address') }}</h3>
                <div class="text-gray-600">
                    @if($order->shipping_name)
                    <p class="font-medium">{{ $order->shipping_name }}</p>
                    @endif
                    <p>{{ $order->shipping_address }}</p>
                    @if($order->shipping_address_2)
                    <p>{{ $order->shipping_address_2 }}</p>
                    @endif
                    <p>{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}</p>
                    @if($order->shipping_country)
                    <p>{{ $order->shipping_country }}</p>
                    @endif
                    @if($order->shipping_phone)
                    <p class="mt-2">{{ __('profile.phone') }}: {{ $order->shipping_phone }}</p>
                    @endif
                </div>
            </div>
            @endif

            {{-- Billing Address --}}
            @if($order->billing_address)
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('profile.billing_address') }}</h3>
                <div class="text-gray-600">
                    @if($order->billing_name)
                    <p class="font-medium">{{ $order->billing_name }}</p>
                    @endif
                    <p>{{ $order->billing_address }}</p>
                    @if($order->billing_address_2)
                    <p>{{ $order->billing_address_2 }}</p>
                    @endif
                    <p>{{ $order->billing_city }}, {{ $order->billing_state }} {{ $order->billing_zip }}</p>
                    @if($order->billing_country)
                    <p>{{ $order->billing_country }}</p>
                    @endif
                    @if($order->billing_phone)
                    <p class="mt-2">{{ __('profile.phone') }}: {{ $order->billing_phone }}</p>
                    @endif
                </div>
            </div>
            @endif
        </div>

        {{-- Payment & Actions --}}
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex flex-wrap items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">{{ __('profile.payment_method') }}</h3>
                    <p class="text-gray-600">
                        @if($order->payment_method)
                            {{ \Illuminate\Support\Str::headline($order->payment_method) }}
                            @if($order->payment_status)
                                - {{ ucfirst($order->payment_status) }}
                            @endif
                        @else
                            {{ __('profile.not_specified') }}
                        @endif
                    </p>
                </div>
                
                <div class="flex space-x-3 mt-4 md:mt-0">
                    @if($order->status === 'completed')
                    <a href="{{ route('profile.order-details', $order) }}?action=reorder" 
                       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md transition duration-200">
                        {{ __('profile.reorder') }}
                    </a>
                    @endif
                    
                    @if(in_array($order->status, ['pending', 'processing']) && $order->created_at->diffInHours() < 24)
                    <form method="POST" action="{{ route('profile.order-cancel', $order) }}" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                onclick="return confirm('{{ __('profile.cancel_order_confirm') }}')"
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md transition duration-200">
                            {{ __('profile.cancel_order') }}
                        </button>
                    </form>
                    @endif
                    
                    @if($order->status === 'completed')
                    <a href="{{ route('profile.order-invoice', $order) }}" 
                       class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md transition duration-200" 
                       target="_blank">
                        {{ __('profile.download_invoice') }}
                    </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Order Timeline/Status Updates --}}
        @if($order->status_updates ?? false)
        <div class="bg-white shadow-md rounded-lg p-6 mt-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('profile.order_timeline') }}</h3>
            <div class="space-y-3">
                @foreach($order->status_updates as $update)
                <div class="flex items-center space-x-3">
                    <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                    <div>
                        <p class="font-medium text-gray-800">{{ $update['status'] }}</p>
                        <p class="text-sm text-gray-600">{{ $update['date'] }}</p>
                        @if($update['note'] ?? false)
                        <p class="text-sm text-gray-500">{{ $update['note'] }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection