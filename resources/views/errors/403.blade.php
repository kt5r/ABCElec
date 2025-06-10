@extends('layouts.app')

@section('title', __('messages.access_denied'))

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 text-center">
        <div>
            <div class="mx-auto h-32 w-32 text-red-400">
                <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H10m-7 0a9 9 0 1118 0M3 12a9 9 0 1118 0m-9-3V9m0 0V7m0 2H9m6 0h2"/>
                </svg>
            </div>
            <h2 class="mt-6 text-6xl font-extrabold text-gray-900">403</h2>
            <h3 class="mt-2 text-2xl font-bold text-gray-900">{{ __('messages.access_denied') }}</h3>
            <p class="mt-2 text-sm text-gray-600">
                {{ __('messages.access_denied_description') }}
            </p>
        </div>
        
        <div class="mt-8 space-y-4">
            @auth
                @if(canAccessBackOffice(auth()->user()))
                    <a href="{{ route('dashboard') }}" 
                       class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        {{ __('messages.go_to_dashboard') }}
                    </a>
                @else
                    <a href="{{ route('home') }}" 
                       class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        {{ __('messages.back_to_home') }}
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}" 
                   class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    {{ __('messages.login_to_continue') }}
                </a>
            @endauth
            
            <button onclick="history.back()" 
                    class="group relative w-full flex justify-center py-2 px-4 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                {{ __('messages.go_back') }}
            </button>
        </div>
        
        <div class="mt-8">
            <p class="text-xs text-gray-500">
                {{ __('messages.contact_admin_if_error') }}
            </p>
        </div>
    </div>
</div>
@endsection