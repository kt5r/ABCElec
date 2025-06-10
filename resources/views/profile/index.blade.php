@extends('layouts.app')

@section('title', __('My Profile'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">{{ __('My Profile') }}</h1>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Profile Information -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-center mb-6">
                        <div class="w-24 h-24 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-12 h-12 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900">{{ auth()->user()->name }}</h2>
                        <p class="text-gray-600">{{ auth()->user()->email }}</p>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('Phone') }}</label>
                            <p class="text-gray-900">{{ auth()->user()->phone ?? __('Not provided') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('Member Since') }}</label>
                            <p class="text-gray-900">{{ auth()->user()->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <a href="{{ route('profile.edit') }}" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 transition duration-200 inline-block text-center">
                            {{ __('Edit Profile') }}
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Order History -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">{{ __('Order History') }}</h3>
                    </div>
                    
                    <div class="p-6">
                        @if($orders->count() > 0)
                            <div class="space-y-4">
                                @foreach($orders as $order)
                                    <div class="border rounded-lg p-4 hover:bg-gray-50 transition duration-200">
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <h4 class="font-semibold text-gray-900">{{ __('Order') }} #{{ $order->id }}</h4>
                                                <p class="text-sm text-gray-600">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                                            </div>
                                            <div class="text-right">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
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
                                        
                                        <div class="mb-3">
                                            <p class="text-sm text-gray-600 mb-2">{{ __('Items:') }}</p>
                                            <div class="space-y-1">
                                                @foreach($order->orderItems as $item)
                                                    <div class="flex justify-between text-sm">
                                                        <span>{{ $item->product->name }} × {{ $item->quantity }}</span>
                                                        <span>${{ number_format($item->price * $item->quantity, 2) }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        
                                        <div class="flex justify-between items-center pt-2 border-t border-gray-200">
                                            <span class="font-semibold text-gray-900">{{ __('Total: $:amount', ['amount' => number_format($order->total_amount, 2)]) }}</span>
                                            <a href="{{ route('orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-500 text-sm font-medium">
                                                {{ __('View Details') }}
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="mt-6">
                                {{ $orders->links() }}
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('No orders yet') }}</h3>
                                <p class="text-gray-600 mb-4">{{ __('Start shopping to see your order history here.') }}</p>
                                <a href="{{ route('products.index') }}" class="bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 transition duration-200">
                                    {{ __('Browse Products') }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection