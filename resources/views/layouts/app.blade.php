<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ config('language.available.' . app()->getLocale() . '.direction', 'ltr') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'ABCElec')</title>
    <meta name="description" content="@yield('description', 'ABC Private LTD - Electronics Retailer offering Kitchen, Bathroom, Living, and Other electronic products.')">
    <meta name="keywords" content="@yield('keywords', 'electronics, kitchen appliances, bathroom accessories, living room electronics, ABC')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Additional CSS -->
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Skip to main content for accessibility -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-blue-600 text-white px-4 py-2 rounded-md z-50">
        {{ __('messages.skip_to_content') }}
    </a>

    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="flex items-center space-x-2">
                            <img src="{{ asset('logo.png') }}" alt="ABCElec" class="h-8 w-auto" onerror="this.style.display='none'">
                            <span class="font-bold text-xl text-gray-900">ABCElec</span>
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                            {{ __('messages.home') }}
                        </a>
                        
                        @if(auth()->check() && auth()->user()->hasRole(['admin', 'operation_manager', 'sales_manager']))
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                                {{ __('messages.dashboard') }}
                            </a>
                        @endif
                        
                        <!-- Categories Dropdown -->
                        <div class="relative group">
                            <button class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center">
                                {{ __('messages.categories') }}
                                <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                <div class="py-1">
                                    <a href="{{ route('category.show', 'all') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                        {{ __('messages.all') }}
                                    </a>
                                    @foreach(['kitchen', 'bathroom', 'living', 'other'] as $category)
                                        <a href="{{ route('category.show', $category) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                            {{ __('messages.' . $category) }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('admin.products.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                            {{ __('messages.products') }}
                        </a>
                        <a href="{{ route('admin.reports.sales') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                            {{__('messages.view_sales_report')}}
                        </a>
                    </div>

                    <!-- Right Side -->
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

                        <!-- Cart -->
                        @auth
                            <a href="{{ route('cart.index') }}" class="relative text-gray-700 hover:text-blue-600 transition-colors">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l1.5-6m0 0h12.5M17 21a2 2 0 100-4 2 2 0 000 4zM9 21a2 2 0 100-4 2 2 0 000 4z"></path>
                                </svg>
                                @if(auth()->check() && app('cart')->getCartItemCount(auth()->user()) > 0)
                                    <span class="absolute -top-2 -right-2 bg-blue-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                        {{ app('cart')->getCartItemCount(auth()->user()) }}
                                    </span>
                                @endif
                            </a>
                        @endauth

                        <!-- User Menu -->
                @auth
                    <div class="relative group">
                        <button class="flex items-center space-x-2 text-gray-700 hover:text-blue-600 transition-colors">
                            <div class="h-8 w-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-medium">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <span class="hidden sm:block text-sm font-medium">{{ auth()->user()->name }}</span>
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <div class="py-1">
                                <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                    {{ __('messages.profile') }}
                                </a>
                                <a href="{{ route('profile.order-history') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                    {{ __('messages.order_history') }}
                                </a>
                                @if(auth()->user()->hasRole(['admin', 'operation_manager', 'sales_manager']))
                                    <a href="{{ route('admin.orders.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                        {{ __('dashboard.view_all_orders') }}
                                    </a>
                                    <div class="border-t my-1"></div>
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                        {{ __('messages.dashboard') }}
                                    </a>
                                @endif
                                <div class="border-t my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                        {{ __('messages.logout') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                            {{ __('messages.login') }}
                        </a>
                        <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                            {{ __('messages.register') }}
                        </a>
                    </div>
                @endauth

                        <!-- Mobile menu button -->
                        <button type="button" class="md:hidden text-gray-700 hover:text-blue-600 transition-colors" id="mobile-menu-button">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu -->
            <div class="md:hidden hidden" id="mobile-menu">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 border-t">
                    <a href="{{ route('home') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-md transition-colors">
                        {{ __('messages.home') }}
                    </a>
                    @if(auth()->check() && auth()->user()->hasRole(['admin', 'operation_manager', 'sales_manager']))
                        <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-md transition-colors">
                            {{ __('messages.dashboard') }}
                        </a>
                    @endif
                    <a href="{{ route('admin.products.index') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-md transition-colors">
                        {{ __('messages.products') }}
                    </a>
                    @foreach(['kitchen', 'bathroom', 'living', 'other'] as $category)
                        <a href="{{ route('category.show', $category) }}" class="block px-6 py-2 text-sm text-gray-600 hover:text-blue-600 hover:bg-gray-50 rounded-md transition-colors">
                            {{ __('messages.category_' . $category) }}
                        </a>
                    @endforeach
                </div>
            </div>
        </nav>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-md mx-4 mt-4" role="alert">
                <div class="flex items-center">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-md mx-4 mt-4" role="alert">
                <div class="flex items-center">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @if(session('warning'))
            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-md mx-4 mt-4" role="alert">
                <div class="flex items-center">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    {{ session('warning') }}
                </div>
            </div>
        @endif

        <!-- Main Content -->
        <main class="flex-1" id="main-content">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <!-- Company Info -->
                    <div class="col-span-1 md:col-span-2">
                        <div class="flex items-center space-x-2 mb-4">
                            <img src="{{ asset('logo.png') }}" alt="{{ config('app.name') }}" class="h-8 w-auto" onerror="this.style.display='none'">
                            <span class="font-bold text-xl">{{ config('app.name') }}</span>
                        </div>
                        <p class="text-gray-300 mb-4">
                            {{ __('messages.company_description') }}
                        </p>
                        <div class="flex items-center space-x-4">
                            <a href="#" class="text-gray-400 hover:text-white transition-colors" aria-label="Facebook">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition-colors" aria-label="Twitter">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition-colors" aria-label="Instagram">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.62 5.367 11.987 11.988 11.987s11.987-5.367 11.987-11.987C24.014 5.367 18.647.001 12.017.001zM8.449 16.988c-1.297 0-2.448-.49-3.148-1.19C4.6 15.097 4.6 13.943 4.6 12.645c0-1.297 0-2.452.701-3.152.7-.7 1.851-1.19 3.148-1.19s2.447.49 3.148 1.19c.7.7.7 1.855.7 3.152 0 1.298 0 2.452-.7 3.153-.701.7-1.851 1.19-3.148 1.19z"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h3 class="font-semibold text-lg mb-4">{{ __('messages.quick_links') }}</h3>
                        <ul class="space-y-2">
                            <li><a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition-colors">{{ __('messages.home') }}</a></li>
                            <li><a href="{{ route('admin.products.index') }}" class="text-gray-300 hover:text-white transition-colors">{{ __('messages.products') }}</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white transition-colors">{{ __('messages.about_us') }}</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white transition-colors">{{ __('messages.contact') }}</a></li>
                        </ul>
                    </div>

                    <!-- Categories -->
                    <div>
                        <h3 class="font-semibold text-lg mb-4">{{ __('messages.categories') }}</h3>
                        <ul class="space-y-2">
                            @foreach(['kitchen', 'bathroom', 'living', 'other'] as $category)
                                <li>
                                    <a href="{{ route('category.show', $category) }}" class="text-gray-300 hover:text-white transition-colors">
                                        {{ __('messages.' . $category) }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                
                <div class="border-t border-gray-700 mt-8 pt-8 text-center">
                    <p class="text-gray-400">
                        &copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('messages.all_rights_reserved') }}
                    </p>
                </div>
            </div>
        </footer>
    </div>

    <!-- Additional Scripts -->
    @stack('scripts')
    
    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });

        // Auto-hide flash messages
        setTimeout(function() {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s ease-out';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            });
        }, 5000);
    </script>
</body>
</html>