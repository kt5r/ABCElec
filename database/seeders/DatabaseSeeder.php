<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Core system seeders
            RoleSeeder::class,
            RolePermissionSeeder::class,
            
            // User seeders
            UserSeeder::class,
            
            // Category and Product seeders
            CategorySeeder::class,
            KitchenProductsSeeder::class,
            BathroomProductsSeeder::class,
            LivingProductsSeeder::class,
            OtherProductsSeeder::class,
            
            // Sample data seeders
            CartItemSeeder::class,
            OrderItemSeeder::class,
        ]);
        
        $this->command->info('Database seeding completed successfully!');
    }
}