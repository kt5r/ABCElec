<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OrderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'operation_manager', 'sales_manager']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Order $order): bool
    {
        // Users can view their own orders
        if ($user->role === 'customer' && $order->user_id === $user->id) {
            return true;
        }
        
        // Admin and managers can view all orders
        return in_array($user->role, ['admin', 'operation_manager', 'sales_manager']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // All authenticated users can create orders
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Order $order): bool
    {
        // Only admin and operation managers can update orders
        return in_array($user->role, ['admin', 'operation_manager']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Order $order): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can cancel the order.
     */
    public function cancel(User $user, Order $order): bool
    {
        // Customers can cancel their own orders if they're cancellable
        if ($user->role === 'customer' && $order->user_id === $user->id && $order->canBeCancelled()) {
            return true;
        }
        
        // Admin and operation managers can cancel any order
        return in_array($user->role, ['admin', 'operation_manager']);
    }

    /**
     * Determine whether the user can view sales reports.
     */
    public function viewSalesReports(User $user): bool
    {
        return in_array($user->role, ['admin', 'operation_manager', 'sales_manager']);
    }
}