@extends('layouts.app')

@section('title', __('Category Details'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ $category->name }}</h1>
        <div class="flex space-x-4">
            <a href="{{ route('admin.categories.edit', $category) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                {{ __('Edit Category') }}
            </a>
            <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                {{ __('Back to Categories') }}
            </a>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Category Information') }}</h2>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('Name') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $category->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('Slug') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $category->slug }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('Status') }}</dt>
                        <dd class="mt-1">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $category->is_active ? __('Active') : __('Inactive') }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('Sort Order') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $category->sort_order }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('Description') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $category->description ?: __('No description available') }}</dd>
                    </div>
                </dl>
            </div>

            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Category Statistics') }}</h2>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('Total Products') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $category->products_count }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('Active Products') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $category->products->where('status', true)->count() }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="mt-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Recent Products') }}</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Name') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Price') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Stock') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($category->products->take(5) as $product)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $product->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $product->formatted_price }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $product->status ? __('Active') : __('Inactive') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $product->stock_quantity }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ __('No products found in this category') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection