@extends('layouts.app')

@section('title', __('messages.orders'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('messages.orders') }}</h1>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">{{ __('Search') }}</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="{{ __('Search by order number or customer') }}">
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">{{ __('Status') }}</label>
                <select name="status" id="status"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">{{ __('All Statuses') }}</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                    <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>{{ __('Processing') }}</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                </select>
            </div>
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700">{{ __('Start Date') }}</label>
                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700">{{ __('End Date') }}</label>
                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div class="md:col-span-4 flex justify-end">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                    {{ __('Apply Filters') }}
                </button>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ __('Order Number') }}
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ __('Customer') }}
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ __('Total') }}
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ __('Status') }}
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ __('Date') }}
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($orders as $order)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $order->order_number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $order->user->name }}<br>
                            <span class="text-xs">{{ $order->user->email }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ number_format($order->total, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($order->status === 'completed') bg-green-100 text-green-800
                                @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $order->created_at->format('M d, Y H:i') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            {{ __('No orders found') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $orders->links() }}
    </div>
</div>
@endsection