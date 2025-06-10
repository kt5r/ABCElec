<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'System Administrator',
            'email' => 'admin@cabc.lk',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
            'role_id' => 1, // admin
            'phone' => '+94711234567',
            'address' => json_encode([
                'street' => '123 Main Street',
                'city' => 'Colombo',
                'postal_code' => '00100',
                'country' => 'Sri Lanka'
            ])
        ]);

        // Create Operation Manager
        User::create([
            'name' => 'John Operations',
            'email' => 'operations@cabc.lk',
            'email_verified_at' => now(),
            'password' => Hash::make('operations123'),
            'role_id' => 2, // operation_manager
            'phone' => '+94717654321',
            'address' => json_encode([
                'street' => '456 Business Ave',
                'city' => 'Kandy',
                'postal_code' => '20000',
                'country' => 'Sri Lanka'
            ])
        ]);

        // Create Sales Manager
        User::create([
            'name' => 'Sarah Sales',
            'email' => 'sales@cabc.lk',
            'email_verified_at' => now(),
            'password' => Hash::make('sales123'),
            'role_id' => 3, // sales_manager
            'phone' => '+94719876543',
            'address' => json_encode([
                'street' => '789 Commerce Road',
                'city' => 'Galle',
                'postal_code' => '80000',
                'country' => 'Sri Lanka'
            ])
        ]);

        // Create Sample Customers
        User::factory()->create([
            'name' => 'Nimal Perera',
            'email' => 'nimal@example.com',
            'password' => Hash::make('customer123'),
            'role_id' => 4, // customer
            'phone' => '+94771234567',
            'address' => json_encode([
                'street' => '45 Temple Road',
                'city' => 'Colombo',
                'postal_code' => '00300',
                'country' => 'Sri Lanka'
            ])
        ]);

        User::factory()->create([
            'name' => 'Kamala Silva',
            'email' => 'kamala@example.com',
            'password' => Hash::make('customer123'),
            'role_id' => 4, // customer
            'phone' => '+94772345678',
            'address' => json_encode([
                'street' => '12 Hill Street',
                'city' => 'Kandy',
                'postal_code' => '20000',
                'country' => 'Sri Lanka'
            ])
        ]);

        // Create additional sample customers
        User::factory(8)->create([
            'role_id' => 4 // customer
        ]);
    }
}
