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

        // Define roles with display names
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Full system access'
            ],
            [
                'name' => 'operation_manager',
                'display_name' => 'Operation Manager',
                'description' => 'Same permissions as Admin'
            ],
            [
                'name' => 'sales_manager',
                'display_name' => 'Sales Manager',
                'description' => 'View daily sales reports only'
            ],
            [
                'name' => 'customer',
                'display_name' => 'Customer',
                'description' => 'Regular customer account'
            ]
        ];

        // Seed roles
        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']],
                [
                    'display_name' => $role['display_name'],
                    'description' => $role['description']
                ]
            );
        }

        // Get role instances
        $admin = Role::where('name', 'admin')->first();
        $operations = Role::where('name', 'operation_manager')->first();
        $sales = Role::where('name', 'sales_manager')->first();
        $customer = Role::where('name', 'customer')->first();

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
