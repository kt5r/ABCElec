<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customerRole = Role::where('name', 'customer')->first();
        
        if (!$customerRole) {
            $customerRole = Role::create([
                'name' => 'customer',
                'display_name' => 'Customer',
                'description' => 'Regular customer with shopping and order history access'
            ]);
        }

        // Create sample customers
        $customers = [
            [
                'name' => 'Sunil Perera',
                'email' => 'sunil.perera@gmail.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'phone' => '+94771234572',
                'address' => '123 Kandy Road, Kandy',
                'city' => 'Kandy',
                'postal_code' => '20000',
                'status' => 'active',
                'last_login_at' => now()->subHours(1),
                'login_count' => 12
            ],
            [
                'name' => 'Kamala Silva',
                'email' => 'kamala.silva@yahoo.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'phone' => '+94771234573',
                'address' => '456 Peradeniya Road, Kandy',
                'city' => 'Kandy',
                'postal_code' => '20000',
                'status' => 'active',
                'last_login_at' => now()->subDays(1),
                'login_count' => 8
            ],
            [
                'name' => 'Ranjan Fernando',
                'email' => 'ranjan.fernando@hotmail.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'phone' => '+94771234574',
                'address' => '789 Colombo Street, Kandy',
                'city' => 'Kandy',
                'postal_code' => '20000',
                'status' => 'active',
                'last_login_at' => now()->subHours(6),
                'login_count' => 25
            ],
            [
                'name' => 'Manjula Wickramasinghe',
                'email' => 'manjula.w@gmail.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'phone' => '+94771234575',
                'address' => '321 Temple Road, Kandy',
                'city' => 'Kandy',
                'postal_code' => '20000',
                'status' => 'active',
                'last_login_at' => now()->subDays(3),
                'login_count' => 18
            ],
            [
                'name' => 'Nimal Rajapaksa',
                'email' => 'nimal.rajapaksa@outlook.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'phone' => '+94771234576',
                'address' => '654 Lake Road, Kandy',
                'city' => 'Kandy',
                'postal_code' => '20000',
                'status' => 'active',
                'last_login_at' => now()->subDays(2),
                'login_count' => 6
            ]
        ];

        foreach ($customers as $customerData) {
            $customer = User::create($customerData);
            $customer->role_id = $customerRole->id;
            $customer->save();
        }

        $this->command->info('Customer users created successfully!');
    }
}