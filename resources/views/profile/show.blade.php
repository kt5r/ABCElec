@extends('layouts.app')

@section('title', __('profile.my_profile'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        {{-- Profile Header --}}
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h1 class="text-3xl font-bold text-gray-800">{{ __('profile.my_profile') }}</h1>
                <a href="{{ route('profile.edit') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition duration-300">
                    {{ __('profile.edit_profile') }}
                </a>
            </div>
            
            <div class="grid md:grid-cols-2 gap-6">
                {{-- Personal Information --}}
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-3">{{ __('profile.personal_information') }}</h3>
                    <div class="space-y-2">
                        <div class="flex">
                            <span class="font-medium text-gray-600 w-20">{{ __('profile.name') }}:</span>
                            <span class="text-gray-800">{{ $user->name }}</span>
                        </div>
                        <div class="flex">
                            <span class="font-medium text-gray-600 w-20">{{ __('profile.email') }}:</span>
                            <span class="text-gray-800">{{ $user->email }}</span>
                        </div>
                        @if($user->phone)
                        <div class="flex">
                            <span class="font-medium text-gray-600 w-20">{{ __('profile.phone') }}:</span>
                            <span class="text-gray-800">{{ $user->phone }}</span>
                        </div>
                        @endif
                        <div class="flex">
                            <span class="font-medium text-gray-600 w-20">{{ __('profile.member_since') }}:</span>
                            <span class="text-gray-800">{{ $user->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Address Information --}}
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-3">{{ __('profile.address_information') }}</h3>
                    @if($user->address)
                    <div class="space-y-2">
                        <div class="text-gray-800">{{ $user->address }}</div>
                        @if($user->city || $user->postal_code)
                        <div class="text-gray-800">
                            {{ $user->city }}{{ $user->city && $user->postal_code ? ', ' : '' }}{{ $user->postal_code }}
                        </div>
                        @endif
                        @if($user->country)
                        <div class="text-gray-800">{{ $user->country }}</div>
                        @endif
                    </div>
                    @else
                    <p class="text-gray-500 italic">{{ __('profile.no_address') }}</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Account Statistics --}}
        @auth
            @if(auth()->user()->hasRole('customer'))
                <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">{{ __('profile.account_statistics') }}</h2>
                    <div class="grid md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-blue-600">{{ $totalOrders }}</div>
                            <div class="text-gray-600">{{ __('profile.total_orders') }}</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-green-600">${{ number_format($totalSpent, 2) }}</div>
                            <div class="text-gray-600">{{ __('profile.total_spent') }}</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-purple-600">{{ $user->email_verified_at ? __('profile.verified') : __('profile.unverified') }}</div>
                            <div class="text-gray-600">{{ __('profile.account_status') }}</div>
                        </div>
                    </div>
                </div>

        {{-- Recent Orders --}}

                <div class="bg-white shadow-md rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-bold text-gray-800">{{ __('profile.recent_orders') }}</h2>
                    <a href="{{ route('profile.order-history') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                        {{ __('profile.view_all_orders') }}
                    </a>
                </div>

                @if($recentOrders->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('profile.order_id') }}</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('profile.order_date') }}</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('profile.status') }}</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('profile.total') }}</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('profile.items') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($recentOrders as $order)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <a href="{{ route('profile.order-details', $order) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                            #{{ $order->order_number }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $order->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($order->status === 'completed') bg-green-100 text-green-800
                                            @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        ${{ number_format($order->total_amount, 2) }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $order->orderItems->count() }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-8">
                        <div class="text-gray-400 text-6xl mb-4">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-700 mb-2">{{ __('profile.no_orders') }}</h3>
                        <p class="text-gray-500 mb-4">{{ __('profile.no_orders_message') }}</p>
                        <a href="{{ route('home') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition duration-300">
                            {{ __('profile.start_shopping') }}
                        </a>
                    </div>
                    @endif
                </div>
            @endif
        @endauth
        
    </div>
</div>
@endsection