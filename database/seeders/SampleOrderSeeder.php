<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Str;

class SampleOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = User::where('role_id', '4')->whereNotNull('address')->get();

        $products = Product::all();

        if ($customers->isEmpty() || $products->isEmpty()) {
            return;
        }

        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        $paymentStatuses = ['pending', 'completed', 'failed', 'refunded'];
        $paymentMethods = ['card', 'bank_transfer', 'cash_on_delivery'];

        // Create sample orders for the last 30 days
        for ($i = 0; $i < 15; $i++) {
            $customer = $customers->random();
            $orderDate = Carbon::now()->subDays(rand(0, 30));
            
            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'user_id' => $customer->id,
                'status' => $statuses[array_rand($statuses)],
                'total_amount' => 0, // Will be calculated after adding items
                'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                'payment_status' => $paymentStatuses[array_rand($paymentStatuses)],
                'shipping_address' => $customer->address,
                'billing_address' => $customer->address,
                'notes' => 'Sample order for testing purposes',
                'created_at' => $orderDate,
                'updated_at' => $orderDate,
            ]);

            // Add 1-4 random products to each order
            $numItems = rand(1, 4);
            $totalAmount = 0;
            
            for ($j = 0; $j < $numItems; $j++) {
                $product = $products->random();
                $quantity = rand(1, 3);
                $price = $product->price;
                $subtotal = $price * $quantity;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total' => $subtotal,
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate,
                ]);
                
                $totalAmount += $subtotal;
            }
            
            // Update order total
            $order->update(['total_amount' => $totalAmount]);
        }

        // Create some orders for today (for sales reporting)
        for ($i = 0; $i < 5; $i++) {
            $customer = $customers->random();
            
            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'user_id' => $customer->id,
                'status' => ['processing', 'pending'][array_rand(['processing', 'pending'])],
                'total_amount' => 0,
                'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                'payment_status' => ['completed', 'pending'][array_rand(['completed', 'pending'])],
                'shipping_address' => $customer->address,
                'billing_address' => $customer->address,
                'notes' => 'Today\'s sample order',
            ]);

            $numItems = rand(1, 3);
            $totalAmount = 0;
            
            for ($j = 0; $j < $numItems; $j++) {
                $product = $products->random();
                $quantity = rand(1, 2);
                $price = $product->price;
                $subtotal = $price * $quantity;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total' => $subtotal,
                ]);
                
                $totalAmount += $subtotal;
            }
            
            $order->update(['total_amount' => $totalAmount]);
        }
    }
}