@extends('layouts.app')

@section('title', __('Edit Product'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold text-gray-900">{{ __('Edit Product') }}</h1>
            <a href="{{ route('products.index') }}" 
               class="text-indigo-600 hover:text-indigo-900 font-medium">
                ← {{ __('Back to Products') }}
            </a>
        </div>

        <!-- Form -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Product Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Product Name') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $product->name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-300 @enderror"
                           placeholder="{{ __('Enter product name') }}"
                           required>
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Description') }}
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('description') border-red-300 @enderror"
                              placeholder="{{ __('Enter product description') }}">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price and Stock -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('Price') }} ($) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="price" 
                               name="price" 
                               value="{{ old('price', $product->price) }}"
                               step="0.01"
                               min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('price') border-red-300 @enderror"
                               placeholder="0.00"
                               required>
                        @error('price')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="stock_quantity" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('Stock Quantity') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="stock_quantity" 
                               name="stock_quantity" 
                               value="{{ old('stock_quantity', $product->stock_quantity) }}"
                               min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('stock_quantity') border-red-300 @enderror"
                               placeholder="0"
                               required>
                        @error('stock_quantity')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Category -->
                <div class="mb-6">
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Category') }} <span class="text-red-500">*</span>
                    </label>
                    <select id="category" 
                            name="category"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('category') border-red-300 @enderror"
                            required>
                        <option value="">{{ __('Select a category') }}</option>
                        <option value="kitchen" {{ old('category', $product->category) == 'kitchen' ? 'selected' : '' }}>{{ __('Kitchen') }}</option>
                        <option value="bathroom" {{ old('category', $product->category) == 'bathroom' ? 'selected' : '' }}>{{ __('Bathroom') }}</option>
                        <option value="living" {{ old('category', $product->category) == 'living' ? 'selected' : '' }}>{{ __('Living') }}</option>
                        <option value="other" {{ old('category', $product->category) == 'other' ? 'selected' : '' }}>{{ __('Other') }}</option>
                    </select>
                    @error('category')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Current Image Display -->
                @if($product->image)
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('Current Image') }}
                        </label>
                        <div class="flex items-center space-x-4">
                            <img src="{{ Storage::url($product->image) }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-20 h-20 object-cover rounded-md border border-gray-300">
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="remove_image" 
                                       name="remove_image" 
                                       value="1"
                                       class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                <label for="remove_image" class="ml-2 block text-sm text-red-600">
                                    {{ __('Remove current image') }}
                                </label>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Product Image -->
                <div class="mb-6">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Product Image') }} @if(!$product->image) <span class="text-gray-500">({{ __('Optional') }})</span> @endif
                    </label>
                    <input type="file" 
                           id="image" 
                           name="image" 
                           accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('image') border-red-300 @enderror">
                    <p class="mt-1 text-sm text-gray-500">
                        {{ __('Accepted formats: JPG, PNG, GIF (Max: 2MB)') }}
                        @if($product->image) - {{ __('Leave empty to keep current image') }} @endif
                    </p>
                    @error('image')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div class="mb-6">
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="is_active" 
                               name="is_active" 
                               value="1"
                               {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-700">
                            {{ __('Product is active and visible to customers') }}
                        </label>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('products.index') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('Update Product') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection