<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Full access to all features',
            ],
            [
                'name' => 'customer',
                'display_name' => 'Customer',
                'description' => 'Can place orders and manage their account',
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}