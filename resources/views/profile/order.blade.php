@extends('layouts.app')

@section('title', __('Order History'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('Order History') }}</h1>
            <p class="text-gray-600">{{ __('View and manage your past orders') }}</p>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <form method="GET" action="{{ route('profile.orders') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Order Number') }}</label>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ $request->search }}"
                           placeholder="{{ __('Search by order number') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Status') }}</label>
                    <select id="status" 
                            name="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">{{ __('All Statuses') }}</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ $request->status == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Date From -->
                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">{{ __('From Date') }}</label>
                    <input type="date" 
                           id="date_from" 
                           name="date_from" 
                           value="{{ $request->date_from }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Date To -->
                <div>
                    <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">{{ __('To Date') }}</label>
                    <input type="date" 
                           id="date_to" 
                           name="date_to" 
                           value="{{ $request->date_to }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Filter Buttons -->
                <div class="lg:col-span-4 flex gap-3">
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        {{ __('Apply Filters') }}
                    </button>
                    <a href="{{ route('profile.orders') }}" 
                       class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        {{ __('Clear Filters') }}
                    </a>
                </div>
            </form>
        </div>

        <!-- Orders List -->
        @if($orders->count() > 0)
            <div class="space-y-4">
                @foreach($orders as $order)
                    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
                        <!-- Order Header -->
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-4 mb-2">
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            {{ __('Order') }} #{{ $order->order_number }}
                                        </h3>
                                        <span class="px-3 py-1 text-xs font-medium rounded-full
                                            @if($order->status == 'completed') bg-green-100 text-green-800
                                            @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                            @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                            @elseif($order->status == 'shipped') bg-purple-100 text-purple-800
                                            @else bg-yellow-100 text-yellow-800
                                            @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </div>
                                    <div class="text-sm text-gray-600 space-x-4">
                                        <span>{{ __('Ordered on') }} {{ $order->created_at->format('M d, Y') }}</span>
                                        <span>{{ __('Total') }}: ${{ number_format($order->total_amount, 2) }}</span>
                                        <span>{{ __('Items') }}: {{ $order->orderItems->count() }}</span>
                                    </div>
                                </div>
                                <div class="flex gap-2 mt-4 sm:mt-0">
                                    <a href="{{ route('profile.order.show', $order) }}" 
                                       class="px-4 py-2 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        {{ __('View Details') }}
                                    </a>
                                    
                                    @if(in_array($order->status, ['pending', 'confirmed']))
                                        <form method="POST" action="{{ route('profile.order.cancel', $order) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    onclick="return confirm('{{ __('Are you sure you want to cancel this order?') }}')"
                                                    class="px-4 py-2 text-sm bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                                                {{ __('Cancel Order') }}
                                            </button>
                                        </form>
                                    @endif

                                    @if($order->status == 'completed')
                                        <form method="POST" action="{{ route('profile.order.reorder', $order) }}" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="px-4 py-2 text-sm bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                                                {{ __('Reorder') }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Order Items Preview -->
                        <div class="p-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                                @foreach($order->orderItems->take(4) as $item)
                                    <div class="flex items-center gap-3">
                                        @if($item->product->images->first())
                                            <img src="{{ Storage::url($item->product->images->first()->image_path) }}" 
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
                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                {{ $item->product->name }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ __('Qty') }}: {{ $item->quantity }} × ${{ number_format($item->price, 2) }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                                
                                @if($order->orderItems->count() > 4)
                                    <div class="flex items-center justify-center text-sm text-gray-500">
                                        +{{ $order->orderItems->count() - 4 }} {{ __('more items') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="w-24 h-24 mx-auto mb-4 text-gray-300">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-medium text-gray-900 mb-2">{{ __('No orders found') }}</h3>
                <p class="text-gray-600 mb-6">
                    @if(request()->has('search') || request()->has('status') || request()->has('date_from') || request()->has('date_to'))
                        {{ __('No orders match your current filters. Try adjusting your search criteria.') }}
                    @else
                        {{ __('You haven\'t placed any orders yet. Start shopping to see your orders here.') }}
                    @endif
                </p>
                <a href="{{ route('shop.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    {{ __('Start Shopping') }}
                </a>
            </div>
        @endif
    </div>
</div>
@endsection