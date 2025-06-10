@extends('layouts.app')

@section('title', __('orders.my_orders'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">{{ __('orders.my_orders') }}</h1>

    @if($orders->count() > 0)
    <div class="space-y-4">
        @foreach($orders as $order)
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">
                        {{ __('orders.order') }} #{{ $order->order_number }}
                    </h3>
                    <p class="text-gray-600">{{ $order->created_at->format('M d, Y H:i') }}</p>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                 @if($order->status === 'completed') bg-green-100 text-green-800
                                 @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                 @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                                 @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                 @else bg-gray-100 text-gray-800 @endif">
                        {{ __('orders.status.' . $order->status) }}
                    </span>
                    <p class="text-xl font-bold text-gray-800 mt-1">${{ number_format($order->total_amount, 2) }}</p>
                </div>
            </div>
            
            <div class="border-t pt-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <h4 class="font-medium text-gray-800 mb-2">{{ __('orders.shipping_address') }}</h4>
                        <div class="text-gray-600 text-sm">
                            <p>{{ $order->shipping_name }}</p>
                            <p>{{ $order->shipping_address }}</p>
                            <p>{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}</p>
                            <p>{{ $order->shipping_country }}</p>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-800 mb-2">{{ __('orders.order_summary') }}</h4>
                        <div class="text-gray-600 text-sm">
                            <p>{{ $order->items->count() }} {{ __('common.items') }}</p>
                            <p>{{ __('orders.payment_method') }}: {{ ucfirst($order->payment_method) }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('orders.show', $order->id) }}" 
                       class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                        {{ __('orders.view_details') }}
                    </a>
                    @if($order->status === 'completed')
                    <a href="{{ route('orders.invoice', $order->id) }}" 
                       class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition-colors">
                        {{ __('orders.download_invoice') }}
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $orders->links() }}
    </div>
    @else
    <div class="text-center py-12">
        <div class="max-w-md mx-auto">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-shopping-bag text-3xl text-gray-400"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-4">{{ __('orders.no_orders') }}</h3>
            <p class="text-gray-600 mb-6">{{ __('orders.no_orders_message') }}</p>
            <a href="{{ route('categories.index') }}" 
               class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors">
                {{ __('common.start_shopping') }}
            </a>
        </div>
    </div>
    @endif
</div>
@endsection