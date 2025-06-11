@extends('layouts.app')

@section('title', __('messages.bad_request'))

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 text-center">
        <div>
            <div class="mx-auto h-32 w-32 text-gray-400">
                <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h2 class="mt-6 text-6xl font-extrabold text-gray-900">400</h2>
            <h3 class="mt-2 text-2xl font-bold text-gray-900">{{ __('messages.bad_request') }}</h3>
            <p class="mt-2 text-sm text-gray-600">
                {{ __('messages.bad_request_description') }}
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
                <a href="{{ route('product.index') }}" class="text-blue-600 hover:text-blue-800">
                    {{ __('messages.products') }}
                </a>
                <a href="{{ route('admin.categories.index') }}" class="text-blue-600 hover:text-blue-800">
                    {{ __('messages.categories') }}
                </a>
                @auth
                    <a href="{{ route('profile.edit') }}" class="text-blue-600 hover:text-blue-800">
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