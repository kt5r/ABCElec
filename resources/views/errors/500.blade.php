@extends('layouts.app')

@section('title', __('Server Error'))

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 text-center">
        <div>
            <h1 class="text-9xl font-bold text-red-600">500</h1>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                {{ __('Server Error') }}
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                {{ __('Something went wrong on our end. We\'re working to fix it.') }}
            </p>
        </div>

        <div class="mt-8 space-y-4">
            <a href="{{ route('home') }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                {{ __('Go Home') }}
            </a>
            
            <button onclick="window.location.reload()" 
                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                {{ __('Try Again') }}
            </button>
        </div>

        <div class="mt-8">
            <p class="text-sm text-gray-500">
                {{ __('If this problem persists, please') }}
                <a href="{{ route('contact') }}" class="text-indigo-600 hover:text-indigo-500">
                    {{ __('contact our support team') }}
                </a>
            </p>
        </div>
    </div>
</div>
@endsection