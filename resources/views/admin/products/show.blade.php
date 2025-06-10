@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li><a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800">{{ __('common.home') }}</a></li>
            <li><span class="mx-2 text-gray-500">/</span></li>
            <li><a href="{{ route('categories.show', $product->category->slug) }}" class="text-blue-600 hover:text-blue-800">{{ __('categories.' . strtolower($product->category->name)) }}</a></li>
            <li><span class="mx-2 text-gray-500">/</span></li>
            <li class="text-gray-500">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
        <div>
            <img src="{{ $product->image_url ?? asset('images/placeholder.jpg') }}" 
                 alt="{{ $product->name }}" 
                 class="w-full h-96 object-cover rounded-lg shadow-md">
        </div>
        
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $product->name }}</h1>
            <p class="text-gray-600 mb-6">{{ $product->description }}</p>
            
            <div class="mb-6">
                <span class="text-3xl font-bold text-blue-600">${{ number_format($product->price, 2) }}</span>
            </div>
            
            <div class="mb-6">
                @if($product->stock_quantity > 0)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                        {{ __('common.in_stock') }} ({{ $product->stock_quantity }} {{ __('common.available') }})
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                        <span class="w-2 h-2 bg-red-400 rounded-full mr-2"></span>
                        {{ __('common.out_of_stock') }}
                    </span>
                @endif
            </div>

            @if($product->stock_quantity > 0)
            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mb-6">
                @csrf
                <div class="flex items-center space-x-4 mb-4">
                    <label for="quantity" class="text-gray-700 font-medium">{{ __('common.quantity') }}:</label>
                    <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock_quantity }}" 
                           class="w-20 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="submit" 
                        class="w-full bg-blue-600 text-white py-3 px-6 rounded-md hover:bg-blue-700 transition-colors font-medium">
                    {{ __('cart.add_to_cart') }}
                </button>
            </form>
            @endif

            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">{{ __('products.specifications') }}</h3>
                <dl class="grid grid-cols-1 gap-x-4 gap-y-2 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500">{{ __('common.category') }}:</dt>
                        <dd class="text-gray-900">{{ __('categories.' . strtolower($product->category->name)) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">SKU:</dt>
                        <dd class="text-gray-900">{{ $product->sku }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div>
        <h2 class="text-2xl font-bold text-gray-800 mb-6">{{ __('products.related_products') }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($relatedProducts as $relatedProduct)
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                <div class="aspect-w-1 aspect-h-1">
                    <img src="{{ $relatedProduct->image_url ?? asset('images/placeholder.jpg') }}" 
                         alt="{{ $relatedProduct->name }}" 
                         class="w-full h-48 object-cover rounded-t-lg">
                </div>
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $relatedProduct->name }}</h3>
                    <p class="text-2xl font-bold text-blue-600 mb-3">${{ number_format($relatedProduct->price, 2) }}</p>
                    <a href="{{ route('products.show', $relatedProduct->slug) }}" 
                       class="block w-full bg-gray-100 text-gray-800 text-center py-2 rounded-md hover:bg-gray-200 transition-colors">
                        {{ __('common.view_details') }}
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection