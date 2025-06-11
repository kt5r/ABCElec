@extends('layouts.app')

@section('title', __('Edit Product'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('Edit Product') }}</h1>
        <a href="{{ route('admin.products.index') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md font-medium transition duration-200">
            {{ __('Back to Products') }}
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Product Name -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                    {{ __('Product Name') }} <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name', $product->name) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror"
                       required>
                @error('name')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- SKU -->
            <div class="mb-6">
                <label for="sku" class="block text-sm font-medium text-gray-700 mb-1">
                    {{ __('SKU') }} <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="sku" 
                       name="sku" 
                       value="{{ old('sku', $product->sku) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('sku') border-red-500 @enderror"
                       required>
                @error('sku')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category -->
            <div class="mb-6">
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">
                    {{ __('Category') }} <span class="text-red-500">*</span>
                </label>
                <select id="category_id" 
                        name="category_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('category_id') border-red-500 @enderror"
                        required>
                    <option value="">{{ __('Select a category') }}</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                    {{ __('Description') }} <span class="text-red-500">*</span>
                </label>
                <textarea id="description" 
                          name="description" 
                          rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('description') border-red-500 @enderror"
                          required>{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Short Description -->
            <div class="mb-6">
                <label for="short_description" class="block text-sm font-medium text-gray-700 mb-1">
                    {{ __('Short Description') }}
                </label>
                <textarea id="short_description" 
                          name="short_description" 
                          rows="2"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('short_description') border-red-500 @enderror">{{ old('short_description', $product->short_description) }}</textarea>
                @error('short_description')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Price -->
            <div class="mb-6">
                <label for="price" class="block text-sm font-medium text-gray-700 mb-1">
                    {{ __('Price') }} <span class="text-red-500">*</span>
                </label>
                <input type="number" 
                       id="price" 
                       name="price" 
                       value="{{ old('price', $product->price) }}"
                       step="0.01"
                       min="0"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('price') border-red-500 @enderror"
                       required>
                @error('price')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Sale Price -->
            <div class="mb-6">
                <label for="sale_price" class="block text-sm font-medium text-gray-700 mb-1">
                    {{ __('Sale Price') }}
                </label>
                <input type="number" 
                       id="sale_price" 
                       name="sale_price" 
                       value="{{ old('sale_price', $product->sale_price) }}"
                       step="0.01"
                       min="0"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('sale_price') border-red-500 @enderror">
                @error('sale_price')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Stock Quantity -->
            <div class="mb-6">
                <label for="stock_quantity" class="block text-sm font-medium text-gray-700 mb-1">
                    {{ __('Stock Quantity') }}
                </label>
                <input type="number" 
                       id="stock_quantity" 
                       name="stock_quantity" 
                       value="{{ old('stock_quantity', $product->stock_quantity) }}"
                       min="0"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('stock_quantity') border-red-500 @enderror">
                @error('stock_quantity')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Product Image -->
            <div class="mb-6">
                <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-1">
                    {{ __('Product Image') }}
                </label>
                @if($product->featured_image)
                    <div class="mb-2">
                        <img src="{{ Storage::url($product->featured_image) }}" 
                             alt="{{ $product->name }}" 
                             class="w-32 h-32 object-cover rounded-lg">
                    </div>
                @endif
                <input type="file" 
                       id="featured_image" 
                       name="featured_image"
                       accept="image/*"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('featured_image') border-red-500 @enderror">
                @error('featured_image')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Checkboxes -->
            <div class="space-y-4 mb-6">
                <div class="flex items-center">
                    <input type="checkbox" 
                           id="manage_stock" 
                           name="manage_stock" 
                           value="1"
                           {{ old('manage_stock', $product->manage_stock) ? 'checked' : '' }}
                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="manage_stock" class="ml-2 block text-sm text-gray-700">
                        {{ __('Manage Stock') }}
                    </label>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" 
                           id="in_stock" 
                           name="in_stock" 
                           value="1"
                           {{ old('in_stock', $product->in_stock) ? 'checked' : '' }}
                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="in_stock" class="ml-2 block text-sm text-gray-700">
                        {{ __('In Stock') }}
                    </label>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" 
                           id="featured" 
                           name="featured" 
                           value="1"
                           {{ old('featured', $product->featured) ? 'checked' : '' }}
                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="featured" class="ml-2 block text-sm text-gray-700">
                        {{ __('Featured Product') }}
                    </label>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" 
                           id="status" 
                           name="status" 
                           value="1"
                           {{ old('status', $product->status) ? 'checked' : '' }}
                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="status" class="ml-2 block text-sm text-gray-700">
                        {{ __('Active') }}
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md font-medium transition duration-200">
                    {{ __('Update Product') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection