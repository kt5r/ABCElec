@extends('layouts.app')

@section('title', 'Access Denied')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 text-center">
        <div>
            <h1 class="text-9xl font-bold text-gray-300">403</h1>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                Access Denied
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                You don't have permission to access this resource.
            </p>
        </div>
        <div class="mt-8 space-y-4">
            <a href="{{ route('home') }}" 
               class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Go to Homepage
            </a>
            @auth
                @if(auth()->user()->hasRole(['admin', 'operation_manager', 'sales_manager']))
                    <a href="{{ route('admin.dashboard') }}" 
                       class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Go to Dashboard
                    </a>
                @endif
            @endauth
        </div>
    </div>
</div>
@endsection