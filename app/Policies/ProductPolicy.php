<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermission('view_products');
    }

    public function view(User $user)
    {
        return $user->hasPermission('view_products');
    }

    public function create(User $user)
    {
        return $user->hasPermission('manage_products');
    }

    public function update(User $user)
    {
        return $user->hasPermission('manage_products');
    }

    public function delete(User $user)
    {
        return $user->hasPermission('manage_products');
    }
}