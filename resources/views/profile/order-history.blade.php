{{-- resources/views/profile/order-history.blade.php --}}
@extends('layouts.app')

@section('title', __('profile.order_history'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-800">{{ __('profile.order_history') }}</h1>
            <a href="{{ route('profile.show') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                {{ __('profile.back_to_profile') }}
            </a>
        </div>

        {{-- Filter and Search Section --}}
        <div class="bg-white shadow-sm rounded-lg p-4 mb-6">
            <form method="GET" action="{{ route('profile.order-history') }}" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-64">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">{{ __('profile.search_orders') }}</label>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="{{ __('profile.search_placeholder') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="min-w-40">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">{{ __('profile.filter_status') }}</label>
                    <select id="status" 
                            name="status" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">{{ __('profile.all_statuses') }}</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('profile.pending') }}</option>
                        <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>{{ __('profile.processing') }}</option>
                        <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>{{ __('profile.shipped') }}</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>{{ __('profile.completed') }}</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>{{ __('profile.cancelled') }}</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition duration-200">
                        {{ __('profile.filter') }}
                    </button>
                    <a href="{{ route('profile.order-history') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md transition duration-200">
                        {{ __('profile.clear') }}
                    </a>
                </div>
            </form>
        </div>

        @if($orders->count() > 0)
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            {{-- Orders Table --}}
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('profile.order_details') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('profile.date') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('profile.status') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('profile.total') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('profile.items') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('profile.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <div class="text-sm font-medium text-gray-900">#{{ $order->order_number }}</div>
                                    <div class="text-sm text-gray-500">
                                        @if($order->orderItems->count() > 0)
                                            {{ $order->orderItems->first()->product->name }}
                                            @if($order->orderItems->count() > 1)
                                                {{ __('profile.and_more', ['count' => $order->orderItems->count() - 1]) }}
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $order->created_at->format('M d, Y') }}</div>
                                <div class="text-sm text-gray-500">{{ $order->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($order->status === 'completed') bg-green-100 text-green-800
                                    @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                    @elseif($order->status === 'shipped') bg-indigo-100 text-indigo-800
                                    @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst(__('profile.' . $order->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">${{ number_format($order->total_amount, 2) }}</div>
                                @if($order->discount_amount > 0)
                                <div class="text-sm text-green-600">-${{ number_format($order->discount_amount, 2) }} {{ __('profile.discount') }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $order->orderItems->count() }} {{ Str::plural(__('profile.item'), $order->orderItems->count()) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex flex-col space-y-1">
                                    <a href="{{ route('profile.order-details', $order) }}" class="text-blue-600 hover:text-blue-900">
                                        {{ __('profile.view_details') }}
                                    </a>
                                    @if($order->status === 'completed')
                                    <a href="{{ route('profile.order-details', $order) }}?action=reorder" class="text-green-600 hover:text-green-900">
                                        {{ __('profile.reorder') }}
                                    </a>
                                    @endif
                                    @if(in_array($order->status, ['pending', 'processing']) && $order->created_at->diffInHours() < 24)
                                    <form method="POST" action="{{ route('profile.order-cancel', $order) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="button" onclick="cancelOrder(this.form)" class="text-red-600 hover:text-red-900 text-left">
                                            {{ __('profile.cancel_order') }}
                                        </button>
                                    </form>
                                    @endif
                                    @if($order->status === 'completed')
                                    <a href="{{ route('profile.order-invoice', $order) }}" class="text-gray-600 hover:text-gray-900" target="_blank">
                                        {{ __('profile.download_invoice') }}
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($orders->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $orders->appends(request()->query())->links() }}
            </div>
            @endif
        </div>

        {{-- Order Summary Statistics --}}
        <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-lg shadow-sm border">
                <div class="text-2xl font-bold text-blue-600">{{ $orders->count() }}</div>
                <div class="text-sm text-gray-600">{{ __('profile.total_orders') }}</div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border">
                <div class="text-2xl font-bold text-green-600">${{ number_format($orders->sum('total_amount'), 2) }}</div>
                <div class="text-sm text-gray-600">{{ __('profile.total_spent') }}</div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border">
                <div class="text-2xl font-bold text-yellow-600">{{ $orders->where('status', 'pending')->count() }}</div>
                <div class="text-sm text-gray-600">{{ __('profile.pending_orders') }}</div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border">
                <div class="text-2xl font-bold text-indigo-600">{{ $orders->where('status', 'completed')->count() }}</div>
                <div class="text-sm text-gray-600">{{ __('profile.completed_orders') }}</div>
            </div>
        </div>

        @else
        {{-- No Orders State --}}
        <div class="bg-white shadow-md rounded-lg p-12 text-center">
            <div class="text-gray-400 text-6xl mb-4">
                <svg class="mx-auto h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-700 mb-2">{{ __('profile.no_orders') }}</h3>
            <p class="text-gray-500 mb-6">{{ __('profile.no_orders_message') }}</p>
            <a href="{{ route('home') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md transition duration-300">
                {{ __('profile.start_shopping') }}
            </a>
        </div>
        @endif
    </div>
</div>

{{-- Cancel Order Modal --}}
<div id="cancelOrderModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">{{ __('profile.cancel_order_title') }}</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">{{ __('profile.cancel_order_message') }}</p>
            </div>
            <div class="items-center px-4 py-3">
                <button id="confirmCancel" class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300">
                    {{ __('profile.confirm_cancel') }}
                </button>
                <button id="closeModal" class="mt-3 px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    {{ __('profile.keep_order') }}
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let formToSubmit = null;

function cancelOrder(form) {
    formToSubmit = form;
    document.getElementById('cancelOrderModal').classList.remove('hidden');
}

document.getElementById('closeModal').addEventListener('click', function() {
    document.getElementById('cancelOrderModal').classList.add('hidden');
    formToSubmit = null;
});

document.getElementById('confirmCancel').addEventListener('click', function() {
    if (formToSubmit) {
        formToSubmit.submit();
    }
    document.getElementById('cancelOrderModal').classList.add('hidden');
});

// Close modal when clicking outside
document.getElementById('cancelOrderModal').addEventListener('click', function(e) {
    if (e.target === this) {
        this.classList.add('hidden');
        formToSubmit = null;
    }
});
</script>
@endpush