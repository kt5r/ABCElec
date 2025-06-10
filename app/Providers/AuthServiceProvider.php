<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\Product;
use App\Policies\OrderPolicy;
use App\Policies\ProductPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Product::class => ProductPolicy::class,
        Order::class => OrderPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define gates for specific permissions
        Gate::define('manage-products', function ($user) {
            return in_array($user->role, ['admin', 'operation_manager']);
        });

        Gate::define('view-sales-reports', function ($user) {
            return in_array($user->role, ['admin', 'operation_manager', 'sales_manager']);
        });

        Gate::define('manage-orders', function ($user) {
            return in_array($user->role, ['admin', 'operation_manager']);
        });

        Gate::define('manage-categories', function ($user) {
            return in_array($user->role, ['admin', 'operation_manager']);
        });

        Gate::define('access-admin-panel', function ($user) {
            return in_array($user->role, ['admin', 'operation_manager', 'sales_manager']);
        });

        Gate::define('manage-users', function ($user) {
            return $user->role === 'admin';
        });
    }
}