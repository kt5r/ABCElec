@extends('layouts.app')

@section('title', __('Products'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('Our Products') }}</h1>
        
        @can('create', App\Models\Product::class)
            <a href="{{ route('products.create') }}" 
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md font-medium transition duration-200">
                {{ __('Add New Product') }}
            </a>
        @endcan
    </div>

    <!-- Category Filter -->
    <div class="mb-8">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">{{ __('Categories') }}</h2>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('products.index') }}" 
               class="px-4 py-2 rounded-full {{ !request('category') ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} transition duration-200">
                {{ __('All') }}
            </a>
            @foreach(['kitchen', 'bathroom', 'living', 'other'] as $category)
                <a href="{{ route('products.index', ['category' => $category]) }}" 
                   class="px-4 py-2 rounded-full {{ request('category') == $category ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} transition duration-200">
                    {{ __(ucfirst($category)) }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Products Grid -->
    @if($products->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($products as $product)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                    <!-- Product Image -->
                    <div class="h-48 bg-gray-200 flex items-center justify-center">
                        @if($product->image)
                            <img src="{{ Storage::url($product->image) }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        @endif
                    </div>

                    <!-- Product Details -->
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-semibold text-gray-900 line-clamp-2">{{ $product->name }}</h3>
                            @can('update', $product)
                                <div class="flex space-x-1">
                                    <a href="{{ route('products.edit', $product) }}" 
                                       class="text-indigo-600 hover:text-indigo-900 p-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    @can('delete', $product)
                                        <form method="POST" action="{{ route('products.destroy', $product) }}" class="inline" onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 p-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            @endcan
                        </div>
                        
                        <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $product->description }}</p>
                        
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-2xl font-bold text-indigo-600">${{ number_format($product->price, 2) }}</span>
                            <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">
                                {{ __(ucfirst($product->category)) }}
                            </span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">
                                {{ __('Stock') }}: {{ $product->stock_quantity }}
                            </span>
                            
                            @auth
                                @if($product->stock_quantity > 0)
                                    <form method="POST" action="{{ route('cart.add', $product) }}" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded-md text-sm font-medium transition duration-200">
                                            {{ __('Add to Cart') }}
                                        </button>
                                    </form>
                                @else
                                    <span class="text-red-500 text-sm font-medium">{{ __('Out of Stock') }}</span>
                                @endif
                            @else
                                <a href="{{ route('login') }}" 
                                   class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded-md text-sm font-medium transition duration-200">
                                    {{ __('Login to Buy') }}
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $products->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('No products found') }}</h3>
            <p class="text-gray-500">{{ __('Try adjusting your search or filter to find what you\'re looking for.') }}</p>
        </div>
    @endif
</div>
@endsection