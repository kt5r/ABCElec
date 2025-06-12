<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Policies\OrderPolicy;
use App\Policies\ProductPolicy;
use App\Policies\ReportPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Product::class => ProductPolicy::class,
        Order::class => OrderPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // Define Gates for permissions
        Gate::define('view-dashboard', function (User $user) {
            return $user->hasPermission('view_dashboard');
        });

        Gate::define('manage-products', function (User $user) {
            return $user->hasPermission('manage_products');
        });

        Gate::define('view-all-orders', function (User $user) {
            return $user->hasPermission('view_all_orders');
        });

        Gate::define('view-sales-reports', function (User $user) {
            return $user->hasPermission('view_sales_reports');
        });

        Gate::define('manage-users', function (User $user) {
            return $user->hasPermission('manage_users');
        });
    }
}