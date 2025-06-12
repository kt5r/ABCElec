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
    LanguageController,
    SalesReportController
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

// Authentication routes
require __DIR__.'/auth.php';

// Authenticated user routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Profile management - All authenticated users
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'show')->name('profile.show');
        Route::get('/profile/edit', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::patch('/profile/password', 'updatePassword')->name('profile.update-password');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
        
        // Order history - Customers can view their own, others can view all
        Route::get('/profile/orders', 'orderHistory')->name('profile.order-history');
        Route::get('/profile/orders/{order}', 'orderDetails')->name('profile.order-details');
        Route::patch('/profile/orders/{order}/cancel', 'cancelOrder')->name('profile.order-cancel');
        Route::get('/profile/orders/{order}/invoice', 'orderInvoice')->name('profile.order-invoice');
    });

    // Cart - Only for customers
    Route::middleware(['role:customer', 'role.redirect'])->controller(CartController::class)->group(function () {
        // Shopping Cart
        Route::get('/cart', 'index')->name('cart.index');
        Route::post('/cart/add/{product}', 'add')->name('cart.add');
        Route::put('/cart/update/{cartItem}', 'update')->name('cart.update');
        Route::delete('/cart/remove/{cartItem}', 'remove')->name('cart.remove');
        Route::delete('/cart/clear', 'clear')->name('cart.clear');
        Route::get('/cart/count', 'count')->name('cart.count');
    });
    
    // Checkout - Only for customers
    Route::middleware(['role:customer','role.redirect'])->controller(CheckoutController::class)->group(function () {
        Route::get('/checkout', 'index')->name('checkout.index');
        Route::post('/checkout/process', 'process')->name('checkout.process');
        Route::get('/checkout/success/{order}', 'success')->name('checkout.success');
        Route::get('/checkout/failed', 'failed')->name('checkout.failed');
    });
});

// API routes for AJAX calls
Route::middleware(['auth'])->prefix('api')->name('api.')->group(function () {
    Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
    Route::get('/categories/active', [CategoryController::class, 'active'])->name('categories.active');
    Route::post('/cart/quick-add', [CartController::class, 'quickAdd'])->name('cart.quick-add');
});

// Add these middleware groups to your existing routes

// Admin routes with role-based access
Route::middleware(['auth', 'role:admin,operation_manager,sales_manager', 'role.redirect'])
    ->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard (all admin roles)
    Route::middleware('role:admin,operation_manager')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::resource('categories', CategoryController::class);
    });
    
    // Products (admin, operation_manager only)
    Route::middleware('role:admin,operation_manager')->group(function () {
        Route::resource('products', ProductController::class)->except(['show']);
        Route::post('products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');
    });
    
    // Orders (admin, operation_manager only)
    Route::middleware('role:admin,operation_manager')->group(function () {
        Route::get('orders', [AdminController::class, 'orders'])->name('orders.index');
        Route::get('orders/{order}', [AdminController::class, 'showOrder'])->name('orders.show');
        Route::patch('orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.update-status');
    });
    
    // Users (admin only)
    Route::middleware('role:admin')->group(function () {
        Route::get('users', [AdminController::class, 'users'])->name('users.index');
        Route::get('users/create', [AdminController::class, 'createUser'])->name('users.create');
        Route::post('users', [AdminController::class, 'storeUser'])->name('users.store');
        Route::get('users/{user}', [AdminController::class, 'showUser'])->name('users.show');
    });
    
    // Sales Reports (all admin roles)
    Route::get('reports/sales', [SalesReportController::class, 'index'])->name('reports.sales');
});



// Authentication Routes
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// Fallback route
Route::fallback(function () {
    return view('errors.404');
});