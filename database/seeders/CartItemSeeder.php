<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CartItem;
use App\Models\User;
use App\Models\Product;

class CartItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some users and products for sample cart items
        $users = User::where('role_id', '4')->take(3)->get();
        $products = Product::take(10)->get();

        if ($users->count() > 0 && $products->count() > 0) {
            foreach ($users as $user) {
                // Add 2-4 random products to each user's cart
                $randomProducts = $products->random(rand(2, 4));
                
                foreach ($randomProducts as $product) {
                    CartItem::create([
                        'user_id' => $user->id,
                        'product_id' => $product->id,
                        'quantity' => rand(1, 3),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
