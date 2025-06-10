@extends('layouts.app')

@section('title', __('orders.order_details'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">{{ __('orders.order') }} #{{ $order->order_number }}</h1>
            <p class="text-gray-600">{{ __('orders.placed_on') }} {{ $order->created_at->format('M d, Y H:i') }}</p>
        </div>
        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium 
                     @if($order->status === 'completed') bg-green-100 text-green-800
                     @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                     @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                     @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                     @else bg-gray-100 text-gray-800 @endif">
            {{ __('orders.status.' . $order->status) }}
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">{{ __('orders.order_items') }}</h2>
                <div class="space-y-4">
                    @foreach($order->items as $item)
                    <div class="flex items-center space-x-4 border-b pb-4 last:border-b-0">
                        <img src="{{ $item->product->image_url ?? asset('images/placeholder.jpg') }}" 
                             alt="{{ $item->product->name }}" 
                             class="w-16 h-16 object-cover rounded-md">
                        <div class="flex-1">
                            <h3 class="font-medium text-gray-800">{{ $item->product->name }}</h3>
                            <p class="text-gray-600 text-sm">{{ __('common.quantity') }}: {{ $item->quantity }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-medium text-gray-800">${{ number_format($item->price, 2) }}</p>
                            <p class="text-gray-600 text-sm">${{ number_format($item->price * $item->quantity, 2) }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            @if($order->status === 'shipped' && $order->tracking_number)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-blue-800 mb-2">{{ __('orders.tracking_information') }}</h3>
                <p class="text-blue-700">{{ __('orders.tracking_number') }}: <span class="font-mono">{{ $order->tracking_number }}</span></p>
            </div>
            @endif
        </div>

        <div>
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">{{ __('orders.order_summary') }}</h2>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ __('orders.subtotal') }}:</span>
                        <span class="text-gray-800">${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ __('orders.tax') }}:</span>
                        <span class="text-gray-800">${{ number_format($order->tax_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ __('orders.shipping') }}:</span>
                        <span class="text-gray-800">${{ number_format($order->shipping_amount, 2) }}</span>
                    </div>
                    <div class="border-t pt-2 flex justify-between font-semibold">
                        <span class="text-gray-800">{{ __('orders.total') }}:</span>
                        <span class="text-gray-800">${{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">{{ __('orders.shipping_address') }}</h2>
                <div class="text-gray-700 text-sm space-y-1">
                    <p class="font-medium">{{ $order->shipping_name }}</p>
                    <p>{{ $order->shipping_address }}</p>
                    <p>{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}</p>
                    <p>{{ $order->shipping_country }}</p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">{{ __('orders.payment_information') }}</h2>
                <div class="text-gray-700 text-sm space-y-1">
                    <p>{{ __('orders.payment_method') }}: {{ ucfirst($order->payment_method) }}</p>
                    <p>{{ __('orders.payment_status') }}: 
                        <span class="@if($order->payment_status === 'paid') text-green-600 @else text-red-600 @endif">
                            {{ __('orders.payment_status.' . $order->payment_status) }}
                        </span>
                    </p>
                </div>
            </div>

            @if($order->status === 'completed')
            <div class="mt-6">
                <a href="{{ route('orders.invoice', $order->id) }}" 
                   class="block w-full bg-gray-600 text-white text-center py-2 rounded-md hover:bg-gray-700 transition-colors">
                    {{ __('orders.download_invoice') }}
                </a>
            </div>
            @endif
        </div>
    </div>

    <div class="mt-8">
        <a href="{{ route('orders.index') }}" 
           class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors">
            ← {{ __('orders.back_to_orders') }}
        </a>
    </div>
</div>
@endsection