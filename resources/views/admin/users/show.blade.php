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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Information -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('User Information') }}</h3>
                </div>
                <div class="px-6 py-4">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Full Name') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Email Address') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Role') }}</dt>
                            <dd class="mt-1">
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
                            <dd class="mt-1">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    @if($user->email_verified_at) bg-green-100 text-green-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    @if($user->email_verified_at)
                                        {{ __('Verified') }}
                                    @else
                                        {{ __('Unverified') }}
                                    @endif
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Phone') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->phone ?? __('Not provided') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Date of Birth') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->date_of_birth ? $user->date_of_birth->format('M d, Y') : __('Not provided') }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Address') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($user->address)
                                    {{ $user->address }}<br>
                                    @if($user->city || $user->state || $user->postal_code)
                                        {{ $user->city }}{{ $user->city && ($user->state || $user->postal_code) ? ', ' : '' }}
                                        {{ $user->state }}{{ $user->state && $user->postal_code ? ' ' : '' }}
                                        {{ $user->postal_code }}
                                    @endif
                                @else
                                    {{ __('Not provided') }}
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Account Activity -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Account Activity') }}</h3>
                </div>
                <div class="px-6 py-4">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Member Since') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('M d, Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Last Updated') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->updated_at->format('M d, Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Last Login') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->last_login_at ? $user->last_login_at->format('M d, Y H:i') : __('Never') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Email Verified') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->email_verified_at ? $user->email_verified_at->format('M d, Y H:i') : __('Not verified') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Quick Actions') }}</h3>
                </div>
                <div class="px-6 py-4 space-y-3">
                    <a href="{{ route('admin.users.edit', $user) }}" 
                       class="w-full flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        {{ __('Edit Profile') }}
                    </a>
                    
                    @if(!$user->email_verified_at)
                        <form method="POST" action="{{ route('admin.users.verify', $user) }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ __('Verify Email') }}
                            </button>
                        </form>
                    @endif
                    
                    @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" 
                              onsubmit="return confirm('{{ __('Are you sure you want to delete this user?') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full flex items-center px-3 py-2 border border-red-300 shadow-sm text-sm leading-4 font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                {{ __('Delete User') }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Statistics -->
            @if($user->role === 'customer')
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Customer Statistics') }}</h3>
                    </div>
                    <div class="px-6 py-4">
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">{{ __('Total Orders') }}</dt>
                                <dd class="mt-1 text-2xl font-bold text-gray-900">{{ $user->orders_count ?? 0 }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">{{ __('Total Spent') }}</dt>
                                <dd class="mt-1 text-2xl font-bold text-gray-900">${{ number_format($user->total_spent ?? 0, 2) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">{{ __('Average Order Value') }}</dt>
                                <dd class="mt-1 text-lg font-semibold text-gray-900">${{ number_format($user->average_order_value ?? 0, 2) }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection