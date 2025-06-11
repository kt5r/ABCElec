<?php

use App\Http\Controllers\{
    AdminController,
    CartController,
    CategoryController,
    CheckoutController,
    DashboardController,
    HomeController,
    ProductController,
    ProfileController,
    LanguageController
};
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Language switching
Route::get('/language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'si'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
        session()->flash('locale_changed', 'Language changed to ' . ($locale === 'si' ? 'Sinhala' : 'English'));
    }
    return redirect()->back();
})->name('language.switch');

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('category.show');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');
Route::get('/terms', function () {
    return view('terms');
})->name('terms');

// Authentication routes
require __DIR__.'/auth.php';

// Guest routes
Route::middleware('guest')->group(function () {
    // Additional guest routes if needed
});

// Authenticated user routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile management
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'show')->name('profile.show');
        Route::get('/profile/edit', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::patch('/profile/password', 'updatePassword')->name('profile.update-password');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
        // Order history and details
        Route::get('/profile/orders', 'orderHistory')->name('profile.order-history');
        Route::get('/profile/orders/{order}', 'orderDetails')->name('profile.order-details');
        Route::patch('/profile/orders/{order}/cancel', 'cancelOrder')->name('profile.order-cancel');
        Route::get('/profile/orders/{order}/invoice', 'orderInvoice')->name('profile.order-invoice');
    });

    // Shopping Cart
    Route::controller(CartController::class)->group(function () {
        Route::get('/cart', 'index')->name('cart.index');
        Route::post('/cart/add/{product}', 'add')->name('cart.add');
        Route::put('/cart/update/{cartItem}', 'update')->name('cart.update');
        Route::delete('/cart/remove/{cartItem}', 'remove')->name('cart.remove');
        Route::delete('/cart/clear', 'clear')->name('cart.clear');
        Route::get('/cart/count', 'count')->name('cart.count');
    });
    
    // Checkout
    Route::controller(CheckoutController::class)->group(function () {
        Route::get('/checkout', 'index')->name('checkout.index');
        Route::post('/checkout/process', 'process')->name('checkout.process');
        Route::get('/checkout/success/{order}', 'success')->name('checkout.success');
        Route::get('/checkout/failed', 'failed')->name('checkout.failed');
    });
});

// Admin routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    // Product management
    Route::resource('products', ProductController::class)->except(['show']);
    Route::post('products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');
    
    // Category management
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::post('categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
    
    // Order management
    Route::get('orders', [AdminController::class, 'orders'])->name('orders.index');
    Route::get('orders/{order}', [AdminController::class, 'showOrder'])->name('orders.show');
    Route::patch('orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.update-status');
    
    // User management
    Route::get('users', [AdminController::class, 'users'])->name('users.index');
    Route::get('users/{user}', [AdminController::class, 'showUser'])->name('users.show');
    Route::patch('users/{user}/status', [AdminController::class, 'updateUserStatus'])->name('users.update-status');
    Route::get('users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('users', [AdminController::class, 'storeUser'])->name('users.store');
    
    // System reports (Admin and Operation Manager only)
    Route::get('reports', [AdminController::class, 'reports'])->name('reports.index');
    Route::get('reports/sales', [AdminController::class, 'salesReport'])->name('reports.sales');
    Route::get('reports/products', [AdminController::class, 'productsReport'])->name('reports.products');
    Route::get('reports/users', [AdminController::class, 'usersReport'])->name('reports.users');
});

// Sales Manager routes
Route::middleware(['auth', 'role:sales_manager'])->prefix('sales')->name('sales.')->group(function () {
    Route::get('/', [AdminController::class, 'salesDashboard'])->name('dashboard');
    Route::get('/reports/daily', [AdminController::class, 'dailySalesReport'])->name('reports.daily');
    Route::get('/reports/export', [AdminController::class, 'exportDailySales'])->name('reports.export');
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports.index');
});

// API routes for AJAX calls
Route::middleware(['auth'])->prefix('api')->name('api.')->group(function () {
    Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
    Route::get('/categories/active', [CategoryController::class, 'active'])->name('categories.active');
    Route::post('/cart/quick-add', [CartController::class, 'quickAdd'])->name('cart.quick-add');
});

// Fallback route
Route::fallback(function () {
    return view('errors.404');
});
