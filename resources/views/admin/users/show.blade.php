@extends('layouts.app')

@section('title', __('User Details'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ __('User Details') }}</h1>
            <p class="mt-2 text-gray-600">{{ __('View and manage user information') }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.users.edit', $user) }}" 
               class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                {{ __('Edit User') }}
            </a>
            <a href="{{ route('admin.users.index') }}" 
               class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                {{ __('Back to Users') }}
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- User Information -->
        <div class="md:col-span-2">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="h-16 w-16 rounded-full bg-indigo-100 flex items-center justify-center">
                            <span class="text-2xl font-medium text-indigo-800">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </span>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                            <p class="text-gray-600">{{ $user->email }}</p>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-4">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">{{ __('Role') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        @if($user->role === 'admin') bg-red-100 text-red-800
                                        @elseif($user->role === 'operation_manager') bg-purple-100 text-purple-800
                                        @elseif($user->role === 'sales_manager') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">{{ __('Status') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $user->is_active ? __('Active') : __('Inactive') }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">{{ __('Joined') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('M d, Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">{{ __('Last Login') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $user->last_login_at ? $user->last_login_at->format('M d, Y H:i') : __('Never') }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="space-y-6">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Order Statistics') }}</h3>
                    <dl class="grid grid-cols-1 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Total Orders') }}</dt>
                            <dd class="mt-1 text-2xl font-semibold text-gray-900">{{ $totalOrders }}</dd>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Total Spent') }}</dt>
                            <dd class="mt-1 text-2xl font-semibold text-gray-900">${{ number_format($totalSpent, 2) }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="mt-6">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Recent Orders') }}</h3>
                @if($recentOrders->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Order ID') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Date') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Status') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Total') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($recentOrders as $order)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            #{{ $order->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $order->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                @if($order->status === 'completed') bg-green-100 text-green-800
                                                @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            ${{ number_format($order->total_amount, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">{{ __('No recent orders found.') }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection