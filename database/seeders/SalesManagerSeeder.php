<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class SalesManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $salesManagerRole = Role::where('name', 'sales_manager')->first();
        
        if (!$salesManagerRole) {
            $salesManagerRole = Role::create([
                'name' => 'sales_manager',
                'display_name' => 'Sales Manager',
                'description' => 'Can view daily sales reports only'
            ]);
        }

        // Create sample sales managers
        $salesManagers = [
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@cabc.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'phone' => '+94771234567',
                'status' => 'active',
                'last_login_at' => now()->subDays(1),
                'login_count' => 15
            ],
            [
                'name' => 'Michael Chen',
                'email' => 'michael.chen@cabc.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'phone' => '+94771234568',
                'status' => 'active',
                'last_login_at' => now()->subHours(3),
                'login_count' => 8
            ],
            [
                'name' => 'Emily Rodriguez',
                'email' => 'emily.rodriguez@cabc.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'phone' => '+94771234569',
                'status' => 'active',
                'last_login_at' => now()->subDays(2),
                'login_count' => 22
            ]
        ];

        foreach ($salesManagers as $managerData) {
            $manager = User::create($managerData);
            $manager->role_id = $salesManagerRole->id;
            $manager->save();
        }

        $this->command->info('Sales Manager users created successfully!');
    }
}