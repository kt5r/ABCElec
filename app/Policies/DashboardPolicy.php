<?php

namespace App\Policies;

use App\Models\User;

class DashboardPolicy
{
    public function access(User $user): bool
    {
        return $user->role->hasPermission('dashboard.access');
    }

    public function viewSalesReports(User $user): bool
    {
        return $user->role->hasPermission('sales.view');
    }

    public function manageProducts(User $user): bool
    {
        return $user->role->hasPermission('product.create') && 
               $user->role->hasPermission('product.update') && 
               $user->role->hasPermission('product.delete');
    }

    public function manageUsers(User $user): bool
    {
        return $user->role->hasPermission('user.create') && 
               $user->role->hasPermission('user.update') && 
               $user->role->hasPermission('user.delete');
    }
}
