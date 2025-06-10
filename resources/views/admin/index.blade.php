@extends('layouts.app')

@section('title', __('Admin Panel') . ' - ABC Private LTD')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-cyan-50">
    <!-- Hero Section -->
    <div class="relative bg-white shadow-sm">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                    <span class="block">{{ __('Admin Panel') }}</span>
                    <span class="block text-indigo-600 text-3xl sm:text-4xl">ABC Private LTD</span>
                </h1>
                <p class="mt-3 max-w-md mx-auto text-base text-gray-500 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                    {{ __('Manage your e-commerce platform with powerful administrative tools') }}
                </p>
                <div class="mt-5 max-w-md mx-auto sm:flex sm:justify-center md:mt-8">
                    <div class="rounded-md shadow">
                        <a href="{{ route('admin.dashboard') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10">
                            {{ __('Go to Dashboard') }}
                        </a>
                    </div>
                    <div class="mt-3 rounded-md shadow sm:mt-0 sm:ml-3">
                        <a href="{{ route('home') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-gray-50 md:py-4 md:text-lg md:px-10">
                            {{ __('View Store') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <!-- System Status -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold text-gray-900">{{ __('System Status') }}</h2>
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-green-400 rounded-full mr-2"></div>
                    <span class="text-sm text-gray-600">{{ __('All systems operational') }}</span>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-indigo-600">{{ $systemStats['uptime'] ?? '99.9%' }}</div>
                    <div class="text-sm text-gray-500">{{ __('Uptime') }}</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600">{{ $systemStats['response_time'] ?? '< 200ms' }}</div>
                    <div class="text-sm text-gray-500">{{ __('Response Time') }}</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-yellow-600">{{ $systemStats['active_users'] ?? '0' }}</div>
                    <div class="text-sm text-gray-500">{{ __('Active Users') }}</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-purple-600">{{ $systemStats['storage_used'] ?? '45%' }}</div>
                    <div class="text-sm text-gray-500">{{ __('Storage Used') }}</div>
                </div>
            </div>
        </div>

        <!-- Admin Modules -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ __('Administrative Modules') }}</h2>
            
            <!-- Primary Actions -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 mb-8">
                @can('manage-products')
                <div class="bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow duration-300">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-indigo-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">{{ __('Product Management') }}</h3>
                                <p class="text-sm text-gray-500">{{ __('Manage products, categories, and inventory') }}</p>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-between items-center">
                            <div class="text-sm text-gray-500">
                                {{ $totalProducts ?? 0 }} {{ __('products') }} • {{ $totalCategories ?? 4 }} {{ __('categories') }}
                            </div>
                            <a href="{{ route('admin.products.index') }}" class="text-indigo-600 hover:text-indigo-500 font-medium">
                                {{ __('Manage') }} →
                            </a>
                        </div>
                    </div>
                </div>
                @endcan

                @can('manage-orders')
                <div class="bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow duration-300">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">{{ __('Order Management') }}</h3>
                                <p class="text-sm text-gray-500">{{ __('Process orders and manage fulfillment') }}</p>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-between items-center">
                            <div class="text-sm text-gray-500">
                                {{ $totalOrders ?? 0 }} {{ __('orders') }} • {{ $pendingOrders ?? 0 }} {{ __('pending') }}
                            </div>
                            <a href="{{ route('admin.orders.index') }}" class="text-green-600 hover:text-green-500 font-medium">
                                {{ __('Manage') }} →
                            </a>
                        </div>
                    </div>
                </div>
                @endcan

                @can('manage-users')
                <div class="bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow duration-300">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">{{ __('User Management') }}</h3>
                                <p class="text-sm text-gray-500">{{ __('Manage customers and staff accounts') }}</p>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-between items-center">
                            <div class="text-sm text-gray-500">
                                {{ $totalUsers ?? 0 }} {{ __('users') }} • {{ $totalAdmins ?? 0 }} {{ __('admins') }}
                            </div>
                            <a href="{{ route('admin.users.index') }}" class="text-yellow-600 hover:text-yellow-500 font-medium">
                                {{ __('Manage') }} →
                            </a>
                        </div>
                    </div>
                </div>
                @endcan

                @can('view-sales-reports')
                <div class="bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow duration-300">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">{{ __('Analytics & Reports') }}</h3>
                                <p class="text-sm text-gray-500">{{ __('View sales data and analytics') }}</p>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-between items-center">
                            <div class="text-sm text-gray-500">
                                ${{ number_format($totalRevenue ?? 0, 2) }} {{ __('revenue') }}
                            </div>
                            <a href="{{ route('admin.reports.dashboard') }}" class="text-purple-600 hover:text-purple-500 font-medium">
                                {{ __('View') }} →
                            </a>
                        </div>
                    </div>
                </div>
                @endcan

                <div class="bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow duration-300">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-cyan-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">{{ __('System Settings') }}</h3>
                                <p class="text-sm text-gray-500">{{ __('Configure system preferences') }}</p>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-between items-center">
                            <div class="text-sm text-gray-500">
                                {{ __('Site configuration') }}
                            </div>
                            <a href="#" class="text-cyan-600 hover:text-cyan-500 font-medium">
                                {{ __('Configure') }} →
                            </a>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow duration-300">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-red-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">{{ __('Security & Logs') }}</h3>
                                <p class="text-sm text-gray-500">{{ __('Monitor security and system logs') }}</p>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-between items-center">
                            <div class="text-sm text-gray-500">
                                {{ __('Security monitoring') }}
                            </div>
                            <a href="#" class="text-red-600 hover:text-red-500 font-medium">
                                {{ __('Monitor') }} →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Role-based Access Information -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('Your Access Level') }}</h2>
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">{{ auth()->user()->role ?? 'User' }}</h3>
                    <p class="text-sm text-gray-500">
                        @if(auth()->user()->hasRole('admin'))
                            {{ __('Full administrative access to all system functions') }}
                        @elseif(auth()->user()->hasRole('operation_manager'))
                            {{ __('Full administrative access to all system functions') }}
                        @elseif(auth()->user()->hasRole('sales_manager'))
                            {{ __('Access to sales reports and analytics only') }}
                        @else
                            {{ __('Limited access - contact administrator for permissions') }}
                        @endif
                    </p>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium
                        @if(auth()->user()->hasRole(['admin', 'operation_manager']))
                            bg-green-100 text-green-800
                        @elseif(auth()->user()->hasRole('sales_manager'))
                            bg-yellow-100 text-yellow-800
                        @else
                            bg-gray-100 text-gray-800
                        @endif
                    ">
                        @if(auth()->user()->hasRole(['admin', 'operation_manager']))
                            {{ __('Full Access') }}
                        @elseif(auth()->user()->hasRole('sales_manager'))
                            {{ __('Limited Access') }}
                        @else
                            {{ __('No Access') }}
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('Recent Activity') }}</h2>
            @if(isset($recentActivity) && count($recentActivity) > 0)
                <div class="flow-root">
                    <ul class="-mb-8">
                        @foreach($recentActivity as $activity)
                        <li>
                            <div class="relative pb-8">
                                @if(!$loop->last)
                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                @endif
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full 
                                            @switch($activity['type'])
                                                @case('order')
                                                    bg-green-500
                                                    @break
                                                @case('product')
                                                    bg-blue-500
                                                    @break
                                                @case('user')
                                                    bg-yellow-500
                                                    @break
                                                @default
                                                    bg-gray-500
                                            @endswitch
                                            flex items-center justify-center ring-8 ring-white">
                                            <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-gray-500">{{ $activity['description'] }}</p>
                                        </div>
                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                            <time datetime="{{ $activity['created_at'] }}">{{ $activity['time_ago'] }}</time>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('No recent activity') }}</h3>
                    <p class="mt-1 text-sm text-gray-500">{{ __('Activity will appear here as actions are performed.') }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Simple welcome animation
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.shadow-lg');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.transition = 'all 0.5s ease-out';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });
</script>
@endpush