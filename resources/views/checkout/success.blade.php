@extends('layouts.app')

@section('title', __('messages.order_successful'))

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-8">
            <!-- Success Message -->
            <div class="text-center mb-8">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                    <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('messages.order_successful') }}</h1>
                <p class="text-gray-600">{{ __('messages.thank_you_for_order') }}</p>
            </div>

            <!-- Order Details -->
            <div class="border-t border-gray-200 pt-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('messages.order_details') }}</h2>
                
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">{{ __('messages.order_number') }}</p>
                            <p class="font-medium">{{ $order->order_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">{{ __('messages.order_date') }}</p>
                            <p class="font-medium">{{ $order->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">{{ __('messages.order_status') }}</p>
                            <p class="font-medium">{{ __('messages.' . $order->status) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">{{ __('messages.total') }}</p>
                            <p class="font-medium">LKR {{ number_format($order->total_amount, 2) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('messages.order_items') }}</h3>
                <div class="space-y-4">
                    @foreach($order->orderItems as $item)
                    <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                        @if($item->product->featured_image)
                            <img src="{{ asset('storage/' . $item->product->featured_image) }}" 
                                 alt="{{ $item->product->name }}" 
                                 class="w-20 h-20 object-cover rounded">
                        @endif
                        <div class="flex-1">
                            <h4 class="font-medium">{{ $item->product->name }}</h4>
                            <p class="text-sm text-gray-600">{{ __('messages.quantity') }}: {{ $item->quantity }}</p>
                            <p class="text-sm text-gray-600">LKR {{ number_format($item->price, 2) }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-medium">LKR {{ number_format($item->subtotal, 2) }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('home') }}" 
                   class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    {{ __('messages.continue_shopping') }}
                </a>
                <a href="{{ route('profile.order-details', $order) }}" 
                   class="inline-flex justify-center items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    {{ __('messages.view_order') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 