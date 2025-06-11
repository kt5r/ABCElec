@extends('layouts.app')

@section('title', __('Category: ') . $category->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">{{ __('messages.category') }} : {{$category->name}}</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($products as $product)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="aspect-w-16 aspect-h-12 bg-gray-200">
                    @if($product->image && is_string($product->image) && file_exists(storage_path('app/public/' . json_decode($product->image)[0])))
                        <img src="{{ asset('storage/' . json_decode($product->image)[0]) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-300 flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                </div>
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2 truncate">{{ $product->name }}</h3>
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $product->description }}</p>
                    <div class="flex justify-between items-center">
                        <span class="text-2xl font-bold text-blue-600">${{ number_format($product->price, 2) }}</span>
                        <a href="{{ route('product.show', $product->slug) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                            {{ __('messages.view_details') }}
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-gray-500">{{ __('No products found in this category.') }}</p>
            </div>
        @endforelse
    </div>
</div>
@endsection 