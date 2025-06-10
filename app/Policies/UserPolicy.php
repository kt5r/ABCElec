<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role->hasPermission('user.read');
    }

    public function view(User $user, User $model): bool
    {
        return $user->role->hasPermission('user.read') || $user->id === $model->id;
    }

    public function create(User $user): bool
    {
        return $user->role->hasPermission('user.create');
    }

    public function update(User $user, User $model): bool
    {
        return $user->role->hasPermission('user.update') || $user->id === $model->id;
    }

    public function delete(User $user, User $model): bool
    {
        return $user->role->hasPermission('user.delete') && $user->id !== $model->id;
    }

    public function updateRole(User $user, User $model): bool
    {
        return $user->role->hasPermission('user.update') && $user->id !== $model->id;
    }
}
