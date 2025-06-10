<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role->hasPermission('category.read');
    }

    public function view(User $user, Category $category): bool
    {
        return $user->role->hasPermission('category.read');
    }

    public function create(User $user): bool
    {
        return $user->role->hasPermission('category.create');
    }

    public function update(User $user, Category $category): bool
    {
        return $user->role->hasPermission('category.update');
    }

    public function delete(User $user, Category $category): bool
    {
        return $user->role->hasPermission('category.delete') && $category->products()->count() === 0;
    }
}
