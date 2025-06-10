<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Product;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = Order::all();
        $products = Product::all();

        if ($orders->count() > 0 && $products->count() > 0) {
            foreach ($orders as $order) {
                // Add 1-4 random products to each order
                $randomProducts = $products->random(rand(1, 4));
                $totalAmount = 0;

                foreach ($randomProducts as $product) {
                    $quantity = rand(1, 3);
                    $price = $product->price;
                    $subtotal = $price * $quantity;
                    $totalAmount += $subtotal;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price' => $price,
                        'total' => $totalAmount,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // Update order total
                $order->update(['total_amount' => $totalAmount]);
            }
        }
    }
}