<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::firstOrCreate(
            ['email' => 'admin@abcelec.com'],
            [
                'name' => 'ABC Admin',
                'password' => Hash::make('pass1234'),
                'email_verified_at' => Carbon::now(),
                'status' => 'active',
                'phone' => '+94123456789',
                'address' => 'ABC Head Office, Colombo',
                'role_id' => 1,
            ]
        );
        
        // Create Operation Manager
        User::firstOrCreate(
            ['email' => 'operations@abcelec.com'],
            [
                'name' => 'Operation Manager',
                'password' => Hash::make('pass1234'),
                'email_verified_at' => Carbon::now(),
                'status' => 'active',
                'phone' => '+94123456790',
                'address' => 'ABC Operations, Colombo',
                'role_id' => 3,
            ]
        );

        // Create Sales Manager
        User::firstOrCreate(
            ['email' => 'sales@abcelec.com'],
            [
                'name' => 'Sales Manager',
                'password' => Hash::make('pass1234'),
                'email_verified_at' => Carbon::now(),
                'status' => 'active',
                'phone' => '+94123456791',
                'address' => 'ABC Sales Department, Colombo',
                'role_id' => 4,
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
                    'password' => Hash::make('pass1234'),
                    'email_verified_at' => Carbon::now(),
                    'status' => 'active',
                    'phone' => $customerData['phone'],
                    'address' => $customerData['address'],
                    'role_id' => 2,
                ]
            );
        }
    }
}