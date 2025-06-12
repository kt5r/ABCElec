<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();
        $products = Product::all();

        // Create sample orders for the past 30 days
        for ($i = 0; $i < 25; $i++) {
            $customer = $customers->random();
            $orderDate = now()->subDays(rand(1, 30));
            
            $order = Order::create([
                'user_id' => $customer->id,
                'order_number' => Order::generateOrderNumber(),
                'status' => $this->getRandomStatus(),
                'total_amount' => 0, 
                'shipping_address' => $customer->address ?? [
                    'street' => 'Sample Street ' . rand(1, 100),
                    'city' => 'Colombo',
                    'postal_code' => '00100',
                    'country' => 'Sri Lanka'
                ],
                'billing_address' => $customer->address ?? [
                    'street' => 'Sample Street ' . rand(1, 100),
                    'city' => 'Colombo',
                    'postal_code' => '00100',
                    'country' => 'Sri Lanka'
                ],
                'payment_method' => $this->getRandomPaymentMethod(),
                'payment_status' => $this->getRandomPaymentStatus(),
                'notes' => 'Sample order for testing',
                'created_at' => $orderDate,
                'updated_at' => $orderDate
            ]);

            // Add random order items
            $numItems = rand(1, 4);
            $totalAmount = 0;

            for ($j = 0; $j < $numItems; $j++) {
                $product = $products->random();
                $quantity = rand(1, 3);
                $price = $product->price;
                $total = $quantity * $price;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total' => $total
                ]);

                $totalAmount += $total;
            }

            // Update order total
            $order->update(['total_amount' => $totalAmount]);

            // Set shipped and delivered dates for completed orders
            if ($order->status === 'delivered') {
                $order->update([
                    'shipped_at' => $orderDate->addDays(1),
                    'delivered_at' => $orderDate->addDays(rand(3, 7)),
                    'payment_status' => 'completed'
                ]);
            } elseif ($order->status === 'shipped') {
                $order->update([
                    'shipped_at' => $orderDate->addDays(1),
                    'payment_status' => 'completed'
                ]);
            } elseif (in_array($order->status, ['processing', 'shipped', 'delivered'])) {
                $order->update(['payment_status' => 'completed']);
            }
        }
    }

    private function getRandomStatus(): string
    {
        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        $weights = [20, 25, 20, 30, 5]; 
        
        return $this->weightedRandom($statuses, $weights);
    }

    private function getRandomPaymentMethod(): string
    {
        $methods = ['cash_on_delivery', 'bank_transfer', 'credit_card'];
        return $methods[array_rand($methods)];
    }

    private function getRandomPaymentStatus(): string
    {
        $statuses = ['pending', 'completed', 'failed'];
        $weights = [30, 65, 5];
        
        return $this->weightedRandom($statuses, $weights);
    }

    private function weightedRandom(array $values, array $weights): string
    {
        $totalWeight = array_sum($weights);
        $random = rand(1, $totalWeight);
        
        $currentWeight = 0;
        foreach ($values as $index => $value) {
            $currentWeight += $weights[$index];
            if ($random <= $currentWeight) {
                return $value;
            }
        }
        
        return $values[0];
    }
}