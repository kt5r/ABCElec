<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class BathroomProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bathroomCategory = Category::where('name', 'Bathroom')->first();
        
        if (!$bathroomCategory) {
            $bathroomCategory = Category::create([
                'name' => 'Bathroom',
                'slug' => 'bathroom',
                'description' => 'Bathroom fixtures, accessories and appliances',
                'status' => true,
                'sort_order' => 2
            ]);
        }

        $products = [
            [
                'name' => 'Smart Toilet with Bidet',
                'slug' => 'smart-toilet-with-bidet',
                'description' => 'Luxury smart toilet with integrated bidet functionality. Features heated seat, automatic flush, night light, and remote control operation.',
                'short_description' => 'Smart toilet with bidet and heated seat',
                'price' => 2499.99,
                'sale_price' => 2199.99,
                'sku' => 'BAT-TOI-001',
                'stock_quantity' => 8,
                'image' => json_encode('products/bathroom/smart-toilet.jpg'),
                'status' => true,
                'featured' => true,
                'weight' => 65.0,
                'dimensions' => '70x40x80 cm'
            ],
            [
                'name' => 'Rainfall Shower System',
                'slug' => 'rainfall-shower-system',
                'description' => 'Premium rainfall shower system with thermostatic mixing valve. Includes 12-inch rainfall head, handheld shower, and body jets for spa-like experience.',
                'short_description' => 'Premium rainfall shower with thermostatic control',
                'price' => 899.99,
                'sale_price' => 799.99,
                'sku' => 'BAT-SHO-002',
                'stock_quantity' => 20,
                'image' => json_encode('products/bathroom/rainfall-shower.jpg'),
                'status' => true,
                'featured' => true,
                'weight' => 15.8,
                'dimensions' => '30x30x15 cm'
            ],
            [
                'name' => 'LED Vanity Mirror 60cm',
                'slug' => 'led-vanity-mirror-60cm',
                'description' => 'Illuminated vanity mirror with LED lighting and touch controls. Anti-fog heating pad and adjustable color temperature for perfect lighting.',
                'short_description' => '60cm LED vanity mirror with touch controls',
                'price' => 299.99,
                'sale_price' => null,
                'sku' => 'BAT-MIR-003',
                'stock_quantity' => 25,
                'image' => json_encode('products/bathroom/led-mirror.jpg'),
                'status' => true,
                'featured' => false,
                'weight' => 8.2,
                'dimensions' => '60x80x4 cm'
            ],
            [
                'name' => 'Towel Warmer Electric',
                'slug' => 'towel-warmer-electric',
                'description' => 'Wall-mounted electric towel warmer with timer function. Stainless steel construction with 6 heating bars and energy-efficient operation.',
                'short_description' => 'Electric towel warmer with timer function',
                'price' => 399.99,
                'sale_price' => 349.99,
                'sku' => 'BAT-TOW-004',
                'stock_quantity' => 18,
                'image' => json_encode('products/bathroom/towel-warmer.jpg'),
                'status' => true,
                'featured' => false,
                'weight' => 12.5,
                'dimensions' => '60x90x10 cm'
            ],
            [
                'name' => 'Exhaust Fan with Humidity Sensor',
                'slug' => 'exhaust-fan-humidity-sensor',
                'description' => 'Quiet exhaust fan with built-in humidity sensor and timer. Automatic operation based on humidity levels with ultra-quiet motor technology.',
                'short_description' => 'Quiet exhaust fan with humidity sensor',
                'price' => 189.99,
                'sale_price' => 169.99,
                'sku' => 'BAT-FAN-005',
                'stock_quantity' => 30,
                'image' => json_encode('products/bathroom/exhaust-fan.jpg'),
                'status' => true,
                'featured' => false,
                'weight' => 3.2,
                'dimensions' => '25x25x15 cm'
            ]
        ];

        foreach ($products as $productData) {
            $productData['category_id'] = $bathroomCategory->id;
            Product::create($productData);
        }
    }
}