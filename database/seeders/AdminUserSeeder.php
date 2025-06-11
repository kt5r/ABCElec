<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();

        // Create Admin User
        User::firstOrCreate(
            ['email' => 'admin@cabc.com'],
            [
                'name' => 'ABC Admin',
                'password' => Hash::make('admin123'),
                'email_verified_at' => Carbon::now(),
                'status' => 'active',
                'phone' => '+94123456789',
                'address' => 'ABC Head Office, Colombo',
                'role_id' => $adminRole->id,
            ]
        );
        
        // Create Operation Manager
        User::firstOrCreate(
            ['email' => 'operations@cabc.com'],
            [
                'name' => 'Operation Manager',
                'password' => Hash::make('operations123'),
                'email_verified_at' => Carbon::now(),
                'status' => 'active',
                'phone' => '+94123456790',
                'address' => 'ABC Operations, Colombo',
                'role_id' => $adminRole->id,
            ]
        );

        // Create Sales Manager
        User::firstOrCreate(
            ['email' => 'sales@cabc.com'],
            [
                'name' => 'Sales Manager',
                'password' => Hash::make('sales123'),
                'email_verified_at' => Carbon::now(),
                'status' => 'active',
                'phone' => '+94123456791',
                'address' => 'ABC Sales Department, Colombo',
                'role_id' => $adminRole->id,
            ]
        );

        // Create Sample Customer Users
        $customers = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'phone' => '+94712345678',
                'address' => '123 Main Street, Kandy',
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'phone' => '+94712345679',
                'address' => '456 Oak Avenue, Colombo',
            ],
            [
                'name' => 'Mike Wilson',
                'email' => 'mike@example.com',
                'phone' => '+94712345680',
                'address' => '789 Pine Road, Galle',
            ],
        ];
        
        foreach ($customers as $customerData) {
            User::firstOrCreate(
                ['email' => $customerData['email']],
                [
                    'name' => $customerData['name'],
                    'password' => Hash::make('customer123'),
                    'email_verified_at' => Carbon::now(),
                    'status' => 'active',
                    'phone' => $customerData['phone'],
                    'address' => $customerData['address'],
                    'role_id' => $adminRole->id,
                ]
            );
        }
    }
}