<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Pagination\Paginator;
use App\Models\User;
use App\Policies\ProductPolicy;
use App\Policies\OrderPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\UserPolicy;
use App\Policies\DashboardPolicy;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use App\Services\CartService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CartService::class, function ($app) {
            return new CartService();
        });
        $this->app->alias(CartService::class, 'cart');
        $this->app->bind('App\Services\PaymentService');
        $this->app->bind('App\Services\NotificationService');
        $this->app->bind('App\Services\MailService');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Use Bootstrap for pagination
        Paginator::useBootstrap();
        
        // Register policies
        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(Order::class, OrderPolicy::class);
        Gate::policy(Category::class, CategoryPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        
        // Register custom gates
        Gate::define('access-dashboard', [DashboardPolicy::class, 'viewAny']);
        Gate::define('view-sales-reports', [DashboardPolicy::class, 'viewSalesReports']);
        Gate::define('manage-users', [UserPolicy::class, 'create']);
        Gate::define('manage-products', [ProductPolicy::class, 'create']);
        Gate::define('manage-categories', [CategoryPolicy::class, 'create']);
        Gate::define('manage-orders', [OrderPolicy::class, 'update']);
        
        // Define role-based gates
        Gate::define('admin-access', function (User $user) {
            return $user->hasRole('admin');
        });
        
        Gate::define('operation-manager-access', function (User $user) {
            return $user->hasRole('operation_manager');
        });
        
        Gate::define('sales-manager-access', function (User $user) {
            return $user->hasRole('sales_manager');
        });
        
        Gate::define('customer-access', function (User $user) {
            return $user->hasRole('customer');
        });
        
        // Composite gates
        Gate::define('admin-or-operation', function (User $user) {
            return $user->hasRole('admin') || $user->hasRole('operation_manager');
        });
        
        Gate::define('back-office-access', function (User $user) {
            return $user->hasRole('admin') || 
                   $user->hasRole('operation_manager') || 
                   $user->hasRole('sales_manager');
        });
    }
}