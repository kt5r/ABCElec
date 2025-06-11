@extends('layouts.app')

@section('title', __('messages.welcome_message'))

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-blue-600 to-purple-700 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">
                {{ __('messages.welcome_message') }}
            </h1>
            <p class="text-xl md:text-2xl mb-8 text-blue-100">
                {{ __('messages.company_description') }}
            </p>
            <a href="#categories" class="bg-white text-blue-600 hover:bg-gray-100 px-8 py-3 rounded-lg font-semibold text-lg transition-colors inline-block">
                {{ __('messages.shop_now') }}
            </a>
        </div>
    </div>
</div>

<!-- Featured Products -->
@if($featuredProducts->count() > 0)
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ __('messages.featured_products') }}</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">{{ __('messages.discover_products') }}</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($featuredProducts as $product)
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow overflow-hidden">
                <div class="aspect-w-16 aspect-h-12 bg-gray-200">
                    @if($product->images && count(json_decode($product->images)) > 0)
                        <img src="{{ json_decode($product->images)[0] }}" alt="{{ $product->name }}" 
                             class="w-full h-48 object-cover">
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
                        <a href="{{ route('product.show', $product->slug) }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                            {{ __('messages.view_details') }}
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Categories Section -->
<section id="categories" class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ __('messages.shop_by_category') }}</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">{{ __('messages.browse_categories') }}</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($categories as $category)
                <a href="{{ route('category.show', $category->slug) }}" class="group">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="aspect-w-16 aspect-h-12 bg-gray-200">
                            @php
                                $decodedImage = $category->image ? json_decode($category->image) : null;
                                $imagePath = $decodedImage && is_array($decodedImage) && !empty($decodedImage) ? $decodedImage[0] : null;
                            @endphp
                            @if($imagePath && file_exists(storage_path('app/public/' . $imagePath)))
                                <img src="{{ asset('storage/' . $imagePath) }}" alt="{{ $category->name }}" class="w-full h-48 object-cover group-hover:opacity-75 transition-opacity">
                            @else
                                <div class="w-full h-48 bg-gray-300 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">{{ $category->name }}</h3>
                            <p class="text-gray-600 text-sm mt-2 line-clamp-2">{{ $category->description }}</p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="bg-blue-600 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold mb-4">{{ __('messages.stay_updated') }}</h2>
        <p class="text-blue-100 mb-8 max-w-2xl mx-auto">
            {{ __('messages.newsletter_description') }}
        </p>
        <form class="max-w-md mx-auto flex">
            <input type="email" placeholder="{{ __('messages.enter_email') }}" 
                   class="flex-1 px-4 py-3 rounded-l-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-300">
            <button type="submit" 
                    class="bg-white text-blue-600 hover:bg-gray-100 px-6 py-3 rounded-r-lg font-semibold transition-colors">
                {{ __('messages.subscribe') }}
            </button>
        </form>
    </div>
</section>

<!-- Features Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('messages.best_prices') }}</h3>
                <p class="text-gray-600">{{ __('messages.best_prices_description') }}</p>
            </div>
            
            <div class="text-center">
                <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h1.586a1 1 0 01.707.293l1.414 1.414a1 1 0 00.707.293H15a2 2 0 012 2v0a2 2 0 01-2 2M5 8a2 2 0 000 4h4.586a1 1 0 00.707-.293l4.414-4.414a1 1 0 00.293-.707V6a1 1 0 00-1-1z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('messages.fast_delivery') }}</h3>
                <p class="text-gray-600">{{ __('messages.fast_delivery_description') }}</p>
            </div>
            
            <div class="text-center">
                <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('messages.warranty_protection') }}</h3>
                <p class="text-gray-600">{{ __('messages.warranty_protection_description') }}</p>
            </div>
        </div>
    </div>
</section>
@endsection