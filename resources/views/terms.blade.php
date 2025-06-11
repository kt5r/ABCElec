@extends('layouts.app')

@section('title', __('messages.terms_and_conditions'))

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">{{ __('messages.terms_and_conditions') }}</h1>

            <div class="prose prose-blue max-w-none">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('messages.terms_1_title') }}</h2>
                <p class="text-gray-600 mb-6">{{ __('messages.terms_1_content') }}</p>

                <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('messages.terms_2_title') }}</h2>
                <p class="text-gray-600 mb-6">{{ __('messages.terms_2_content') }}</p>

                <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('messages.terms_3_title') }}</h2>
                <p class="text-gray-600 mb-6">{{ __('messages.terms_3_content') }}</p>

                <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('messages.terms_4_title') }}</h2>
                <p class="text-gray-600 mb-6">{{ __('messages.terms_4_content') }}</p>

                <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('messages.terms_5_title') }}</h2>
                <p class="text-gray-600 mb-6">{{ __('messages.terms_5_content') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection 