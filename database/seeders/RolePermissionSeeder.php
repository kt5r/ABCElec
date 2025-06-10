<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define permissions with display names
        $permissions = [
            // Products
            ['name' => 'products.view', 'display_name' => 'View Products'],
            ['name' => 'products.create', 'display_name' => 'Create Products'],
            ['name' => 'products.edit', 'display_name' => 'Edit Products'],
            ['name' => 'products.delete', 'display_name' => 'Delete Products'],

            // Categories
            ['name' => 'categories.view', 'display_name' => 'View Categories'],
            ['name' => 'categories.create', 'display_name' => 'Create Categories'],
            ['name' => 'categories.edit', 'display_name' => 'Edit Categories'],
            ['name' => 'categories.delete', 'display_name' => 'Delete Categories'],

            // Orders
            ['name' => 'orders.view', 'display_name' => 'View Orders'],
            ['name' => 'orders.create', 'display_name' => 'Create Orders'],
            ['name' => 'orders.edit', 'display_name' => 'Edit Orders'],
            ['name' => 'orders.delete', 'display_name' => 'Delete Orders'],

            // Users
            ['name' => 'users.view', 'display_name' => 'View Users'],
            ['name' => 'users.create', 'display_name' => 'Create Users'],
            ['name' => 'users.edit', 'display_name' => 'Edit Users'],
            ['name' => 'users.delete', 'display_name' => 'Delete Users'],

            // Dashboard
            ['name' => 'dashboard.view', 'display_name' => 'View Dashboard'],
            ['name' => 'dashboard.admin', 'display_name' => 'Admin Dashboard Access'],

            // Sales
            ['name' => 'sales.view', 'display_name' => 'View Sales'],
            ['name' => 'sales.reports', 'display_name' => 'View Sales Reports'],

            // Cart
            ['name' => 'cart.manage', 'display_name' => 'Manage Cart'],

            // Profile
            ['name' => 'profile.view', 'display_name' => 'View Profile'],
            ['name' => 'profile.edit', 'display_name' => 'Edit Profile'],
        ];

        // Seed permissions
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(
                ['name' => $perm['name']],
                ['display_name' => $perm['display_name']]
            );
        }

        // Seed roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $operations = Role::firstOrCreate(['name' => 'operation_manager']);
        $sales = Role::firstOrCreate(['name' => 'sales_manager']);
        $customer = Role::firstOrCreate(['name' => 'customer']);

        // Assign all permissions to admin and operations
        $allPermissions = Permission::all()->pluck('id');
        $admin->permissions()->sync($allPermissions);
        $operations->permissions()->sync($allPermissions);

        // Sales manager - limited permissions
        $salesPerms = Permission::whereIn('name', [
            'sales.view',
            'sales.reports',
            'dashboard.view',
            'orders.view',
            'profile.view',
            'profile.edit',
        ])->pluck('id');
        $sales->permissions()->sync($salesPerms);

        // Customer - basic permissions
        $customerPerms = Permission::whereIn('name', [
            'products.view',
            'categories.view',
            'cart.manage',
            'orders.create',
            'orders.view',
            'profile.view',
            'profile.edit',
        ])->pluck('id');
        $customer->permissions()->sync($customerPerms);
    }
}
