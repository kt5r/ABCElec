@extends('layouts.app')

@section('title', __('messages.page_not_found'))

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 text-center">
        <div>
            <div class="mx-auto h-32 w-32 text-gray-400">
                <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.467-.785-6.168-2.107A6.973 6.973 0 013 12c0-2.343.793-4.495 2.121-6.089A7.962 7.962 0 0112 3c2.34 0 4.467.785 6.168 2.107A6.973 6.973 0 0121 12c0 2.343-.793 4.495-2.121 6.089z"/>
                </svg>
            </div>
            <h2 class="mt-6 text-6xl font-extrabold text-gray-900">404</h2>
            <h3 class="mt-2 text-2xl font-bold text-gray-900">{{ __('messages.page_not_found') }}</h3>
            <p class="mt-2 text-sm text-gray-600">
                {{ __('messages.page_not_found_description') }}
            </p>
        </div>
        
        <div class="mt-8 space-y-4">
            <a href="{{ route('home') }}" 
               class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                {{ __('messages.back_to_home') }}
            </a>
            
            <button onclick="history.back()" 
                    class="group relative w-full flex justify-center py-2 px-4 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                {{ __('messages.go_back') }}
            </button>
        </div>
        
        <div class="mt-8">
            <h4 class="text-sm font-medium text-gray-900 mb-4">{{ __('messages.quick_links') }}</h4>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <a href="{{ route('admin.products.index') }}" class="text-blue-600 hover:text-blue-800">
                    {{ __('messages.products') }}
                </a>
                <a href="{{ route('admin.categories.index') }}" class="text-blue-600 hover:text-blue-800">
                    {{ __('messages.categories') }}
                </a>
                @auth
                    <a href="{{ route('profile.orders') }}" class="text-blue-600 hover:text-blue-800">
                        {{ __('messages.profile') }}
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:text-blue-800">
                        {{ __('messages.orders') }}
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800">
                        {{ __('messages.login') }}
                    </a>
                    <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800">
                        {{ __('messages.register') }}
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection