@extends('layouts.app')

@section('title', __('messages.add_new_product'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold text-gray-900">{{ __('messages.add_new_product') }}</h1>
            <a href="{{ route('products.index') }}" 
               class="text-indigo-600 hover:text-indigo-900 font-medium">
                ← {{ __('messages.back_to_products') }}
            </a>
        </div>

        <!-- Form -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- Product Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('messages.product_name') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-300 @enderror"
                           required>
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- SKU -->
                <div class="mb-6">
                    <label for="sku" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('SKU') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="sku" 
                           name="sku" 
                           value="{{ old('sku') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('sku') border-red-300 @enderror"
                           required>
                    @error('sku')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div class="mb-6">
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('messages.category') }} <span class="text-red-500">*</span>
                    </label>
                    <select id="category_id" 
                            name="category_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('category_id') border-red-300 @enderror"
                            required>
                        <option value="">{{ __('Select a category') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('messages.description') }} <span class="text-red-500">*</span>
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('description') border-red-300 @enderror"
                              required>{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Short Description -->
                <div class="mb-6">
                    <label for="short_description" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('messages.short_description') }}
                    </label>
                    <textarea id="short_description" 
                              name="short_description" 
                              rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('short_description') border-red-300 @enderror">{{ old('short_description') }}</textarea>
                    @error('short_description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price -->
                <div class="mb-6">
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('messages.price') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           id="price" 
                           name="price" 
                           step="0.01"
                           value="{{ old('price') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('price') border-red-300 @enderror"
                           required>
                    @error('price')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sale Price -->
                <div class="mb-6">
                    <label for="sale_price" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('messages.sale_price') }}
                    </label>
                    <input type="number" 
                           id="sale_price" 
                           name="sale_price" 
                           step="0.01"
                           value="{{ old('sale_price') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('sale_price') border-red-300 @enderror">
                    @error('sale_price')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Stock Quantity -->
                <div class="mb-6">
                    <label for="stock_quantity" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('messages.stock_quantity') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           id="stock_quantity" 
                           name="stock_quantity" 
                           value="{{ old('stock_quantity') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('stock_quantity') border-red-300 @enderror"
                           required>
                    @error('stock_quantity')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Image -->
                <div class="mb-6">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('messages.product_image') }}
                    </label>
                    <input type="file" 
                           id="image" 
                           name="image"
                           accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('image') border-red-300 @enderror">
                    @error('image')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Checkboxes -->
                <div class="mb-6 space-y-4">
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="manage_stock" 
                               name="manage_stock" 
                               value="1"
                               {{ old('manage_stock') ? 'checked' : '' }}
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="manage_stock" class="ml-2 block text-sm text-gray-700">
                            {{ __('messages.manage_stock') }}
                        </label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="in_stock" 
                               name="in_stock" 
                               value="1"
                               {{ old('in_stock') ? 'checked' : '' }}
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="in_stock" class="ml-2 block text-sm text-gray-700">
                            {{ __('messages.in_stock') }}
                        </label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="featured" 
                               name="featured" 
                               value="1"
                               {{ old('featured') ? 'checked' : '' }}
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="featured" class="ml-2 block text-sm text-gray-700">
                            {{ __('messages.featured_product') }}
                        </label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="status" 
                               name="status" 
                               value="1"
                               {{ old('status') ? 'checked' : '' }}
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="status" class="ml-2 block text-sm text-gray-700">
                            {{ __('messages.active') }}
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" 
                            class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        {{ __('messages.reate_product') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection