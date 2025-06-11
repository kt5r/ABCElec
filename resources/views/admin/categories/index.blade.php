@extends('layouts.app')

@section('title', __('messages.category'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">{{ __('messages.category') }}</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($categories as $category)
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
            <div class="p-6">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4 mx-auto">
                    <i class="fas fa-{{ $category->icon ?? 'box' }} text-2xl text-blue-600"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 text-center mb-2">
                     {{strtolower($category->name)}}
                </h3>
                <p class="text-gray-600 text-center mb-4">
                    {{ $category->products_count }} {{ __('common.products') }}
                </p>
                <a href="{{ route('admin.categories.show', ['category' => $category->id]) }}" 
                   class="block w-full bg-blue-600 text-white text-center py-2 rounded-md hover:bg-blue-700 transition-colors">
                    {{ __('common.view_products') }}
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection