<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class OtherProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $otherCategory = Category::where('name', 'Other')->first();
        
        if (!$otherCategory) {
            $otherCategory = Category::create([
                'name' => 'Other',
                'slug' => 'other',
                'description' => 'Other electronic devices and accessories',
                'status' => true,
                'sort_order' => 4
            ]);
        }

        $products = [
            [
                'name' => 'Wireless Security Camera System',
                'slug' => 'wireless-security-camera-system',
                'description' => '4-camera wireless security system with 2K resolution, night vision, motion detection, and cloud storage. Easy installation with battery power.',
                'short_description' => '4-camera wireless security system with 2K resolution',
                'price' => 699.99,
                'sale_price' => 599.99,
                'sku' => 'OTH-SEC-001',
                'stock_quantity' => 18,
                'image' => json_encode('products/other/security-camera.jpg'),
                'status' => true,
                'featured' => true,
                'weight' => 6.5,
                'dimensions' => '45x35x15 cm'
            ],
            [
                'name' => 'Electric Scooter 25km Range',
                'slug' => 'electric-scooter-25km-range',
                'description' => 'Foldable electric scooter with 25km range, 25km/h top speed, and mobile app connectivity. LED lights and dual braking system for safety.',
                'short_description' => 'Foldable electric scooter with 25km range',
                'price' => 799.99,
                'sale_price' => 749.99,
                'sku' => 'OTH-SCO-002',
                'stock_quantity' => 10,
                'image' => json_encode('products/other/electric-scooter.jpg'),
                'status' => true,
                'featured' => true,
                'weight' => 14.2,
                'dimensions' => '110x50x120 cm'
            ],
            [
                'name' => 'Portable Power Station 1000W',
                'slug' => 'portable-power-station-1000w',
                'description' => '1000W portable power station with multiple output ports, solar charging capability, and LCD display. Perfect for camping or emergency backup.',
                'short_description' => '1000W portable power station with solar charging',
                'price' => 899.99,
                'sale_price' => null,
                'sku' => 'OTH-POW-003',
                'stock_quantity' => 15,
                'image' => json_encode('products/other/power-station.jpg'),
                'status' => true,
                'featured' => false,
                'weight' => 10.8,
                'dimensions' => '33x23x28 cm'
            ],
            [
                'name' => 'Drone 4K Camera GPS',
                'slug' => 'drone-4k-camera-gps',
                'description' => 'Professional drone with 4K camera, 3-axis gimbal, GPS positioning, and 30-minute flight time. Obstacle avoidance and follow-me modes.',
                'short_description' => 'Professional drone with 4K camera and GPS',
                'price' => 1299.99,
                'sale_price' => 1199.99,
                'sku' => 'OTH-DRO-004',
                'stock_quantity' => 8,
                'image' => json_encode('products/other/drone.jpg'),
                'status' => true,
                'featured' => true,
                'weight' => 1.8,
                'dimensions' => '25x25x8 cm'
            ],
            [
                'name' => 'Wireless Charging Station 5-Device',
                'slug' => 'wireless-charging-station-5-device',
                'description' => 'Multi-device wireless charging station supporting up to 5 devices simultaneously. Fast charging with Qi compatibility and foreign object detection.',
                'short_description' => '5-device wireless charging station with fast charging',
                'price' => 199.99,
                'sale_price' => 179.99,
                'sku' => 'OTH-CHA-005',
                'stock_quantity' => 40,
                'image' => json_encode('products/other/charging-station.jpg'),
                'status' => true,
                'featured' => false,
                'weight' => 1.5,
                'dimensions' => '30x20x5 cm'
            ]
        ];

        foreach ($products as $productData) {
            $productData['category_id'] = $otherCategory->id;
            Product::create($productData);
        }
    }
}