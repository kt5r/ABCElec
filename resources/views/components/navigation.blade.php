<nav class="bg-white shadow-lg border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-600">
                        ABC Electronics
                    </a>
                </div>

                <!-- Main Navigation -->
                <div class="hidden md:ml-10 md:flex md:space-x-8">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        {{ __('messages.home') }}
                    </a>
                    <div class="relative group">
                        <button class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium flex items-center transition-colors">
                            {{ __('messages.categories') }}
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                            <div class="py-1">
                                <a href="{{ route('category.show', 'kitchen') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">{{ __('messages.kitchen') }}</a>
                                <a href="{{ route('category.show', 'bathroom') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">{{ __('messages.bathroom') }}</a>
                                <a href="{{ route('category.show', 'living') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">{{ __('messages.living') }}</a>
                                <a href="{{ route('category.show', 'other') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">{{ __('messages.other') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="flex-1 max-w-lg mx-8 hidden md:block">
                <form action="{{ route('products.search') }}" method="GET" class="relative">
                    <input type="text" name="q" placeholder="{{ __('messages.search') }}" 
                           value="{{ request('q') }}"
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </form>
            </div>

            <!-- Right Navigation -->
            <div class="flex items-center space-x-4">
               <!-- Language Selector -->
                <div class="relative group">
                    <button class="flex items-center space-x-1 text-gray-700 hover:text-blue-600 transition-colors">
                        <span>{{ app()->getLocale() === 'si' ? '🇱🇰' : '🇺🇸' }}</span>
                        <span class="text-sm">{{ strtoupper(app()->getLocale()) }}</span>
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="absolute right-0 mt-2 w-32 bg-white rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                        <div class="py-1">
                            <a href="{{ route('language.switch', 'en') }}" class="flex items-center space-x-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors {{ app()->getLocale() === 'en' ? 'bg-gray-50 font-medium' : '' }}">
                                <span>🇺🇸</span>
                                <span>English</span>
                            </a>
                            <a href="{{ route('language.switch', 'si') }}" class="flex items-center space-x-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors {{ app()->getLocale() === 'si' ? 'bg-gray-50 font-medium' : '' }}">
                                <span>🇱🇰</span>
                                <span>සිංහල</span>
                            </a>
                        </div>
                    </div>
                </div>

                @auth
                    <!-- Cart -->
                    <a href="{{ route('cart.index') }}" class="relative p-2 text-gray-700 hover:text-blue-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5L21 21H9l-2.5-5z"></path>
                        </svg>
                        @if(auth()->user()->cartItems()->count() > 0)
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                {{ auth()->user()->cartItems()->sum('quantity') }}
                            </span>
                        @endif
                    </a>

                    <!-- User Menu -->
                    <div class="relative group">
                        <button class="flex items-center text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            {{ auth()->user()->name }}
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                            <div class="py-1">
                                <a href="{{ route('profile.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">{{ __('messages.profile') }}</a>
                                <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">{{ __('messages.order_history') }}</a>
                                @if(in_array(auth()->user()->role, ['admin', 'operation_manager', 'sales_manager']))
                                    <div class="border-t border-gray-100"></div>
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">{{ __('messages.dashboard') }}</a>
                                @endif
                                <div class="border-t border-gray-100"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">{{ __('messages.logout') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">{{ __('messages.login') }}</a>
                    <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">{{ __('messages.register') }}</a>
                @endauth

                <!-- Mobile menu button -->
                <button class="md:hidden p-2 rounded-md text-gray-700 hover:text-blue-600 focus:outline-none" id="mobile-menu-button">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div class="md:hidden hidden" id="mobile-menu">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-gray-50">
            <a href="{{ route('home') }}" class="block px-3 py-2 text-gray-700 hover:text-blue-600">{{ __('messages.home') }}</a>
            <a href="{{ route('category.show', 'kitchen') }}" class="block px-3 py-2 text-gray-700 hover:text-blue-600">{{ __('messages.kitchen') }}</a>
            <a href="{{ route('category.show', 'bathroom') }}" class="block px-3 py-2 text-gray-700 hover:text-blue-600">{{ __('messages.bathroom') }}</a>
            <a href="{{ route('category.show', 'living') }}" class="block px-3 py-2 text-gray-700 hover:text-blue-600">{{ __('messages.living') }}</a>
            <a href="{{ route('category.show', 'other') }}" class="block px-3 py-2 text-gray-700 hover:text-blue-600">{{ __('messages.other') }}</a>
        </div>
    </div>
</nav>

<script>
document.getElementById('mobile-menu-button').addEventListener('click', function() {
    const menu = document.getElementById('mobile-menu');
    menu.classList.toggle('hidden');
});
</script>