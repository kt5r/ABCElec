<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermission('view_all_orders');
    }

    public function view(User $user, Order $order)
    {
        // Users can view their own orders, or if they have permission to view all orders
        return $user->id === $order->user_id || $user->hasPermission('view_all_orders');
    }

    public function update(User $user)
    {
        return $user->hasPermission('manage_orders');
    }
}