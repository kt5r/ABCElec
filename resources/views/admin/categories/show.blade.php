@extends('layouts.app')

@section('title', __('categories.' . strtolower($category->name)))

@section('content')
<div class="container mx-auto px-4 py-8">
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li><a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800">{{ __('common.home') }}</a></li>
            <li><span class="mx-2 text-gray-500">/</span></li>
            <li><a href="{{ route('categories.index') }}" class="text-blue-600 hover:text-blue-800">{{ __('categories.categories') }}</a></li>
            <li><span class="mx-2 text-gray-500">/</span></li>
            <li class="text-gray-500">{{ __('categories.' . strtolower($category->name)) }}</li>
        </ol>
    </nav>

    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">{{ __('categories.' . strtolower($category->name)) }}</h1>
        <div class="flex items-center space-x-4">
            <select id="sortBy" class="border border-gray-300 rounded-md px-3 py-2">
                <option value="name">{{ __('common.sort_by_name') }}</option>
                <option value="price_low">{{ __('common.price_low_high') }}</option>
                <option value="price_high">{{ __('common.price_high_low') }}</option>
                <option value="newest">{{ __('common.newest_first') }}</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($products as $product)
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
            <div class="aspect-w-1 aspect-h-1">
                <img src="{{ $product->image_url ?? asset('images/placeholder.jpg') }}" 
                     alt="{{ $product->name }}" 
                     class="w-full h-48 object-cover rounded-t-lg">
            </div>
            <div class="p-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $product->name }}</h3>
                <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $product->description }}</p>
                <div class="flex justify-between items-center mb-3">
                    <span class="text-2xl font-bold text-blue-600">${{ number_format($product->price, 2) }}</span>
                    @if($product->stock_quantity > 0)
                        <span class="text-green-600 text-sm">{{ __('common.in_stock') }}</span>
                    @else
                        <span class="text-red-600 text-sm">{{ __('common.out_of_stock') }}</span>
                    @endif
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('products.show', $product->slug) }}" 
                       class="flex-1 bg-gray-100 text-gray-800 text-center py-2 rounded-md hover:bg-gray-200 transition-colors">
                        {{ __('common.view_details') }}
                    </a>
                    @if($product->stock_quantity > 0)
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" 
                                class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition-colors">
                            {{ __('cart.add_to_cart') }}
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <p class="text-gray-500 text-lg">{{ __('products.no_products_found') }}</p>
        </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $products->links() }}
    </div>
</div>
@endsection