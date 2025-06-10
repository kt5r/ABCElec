<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class OperationManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $operationManagerRole = Role::where('name', 'operation_manager')->first();
        
        if (!$operationManagerRole) {
            $operationManagerRole = Role::create([
                'name' => 'operation_manager',
                'display_name' => 'Operation Manager',
                'description' => 'Same permissions as Admin - full CRUD access to product catalog'
            ]);
        }

        // Create sample operation managers
        $operationManagers = [
            [
                'name' => 'David Wilson',
                'email' => 'david.wilson@cabc.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'phone' => '+94771234570',
                'status' => 'active',
                'last_login_at' => now()->subHours(2),
                'login_count' => 45
            ],
            [
                'name' => 'Lisa Thompson',
                'email' => 'lisa.thompson@cabc.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'phone' => '+94771234571',
                'status' => 'active',
                'last_login_at' => now()->subDays(1),
                'login_count' => 33
            ]
        ];

        foreach ($operationManagers as $managerData) {
            $manager = User::create($managerData);
            $manager->role_id = $operationManagerRole->id;
            $manager->save();
        }

        $this->command->info('Operation Manager users created successfully!');
    }
}