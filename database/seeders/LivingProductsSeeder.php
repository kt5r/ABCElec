<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class LivingProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $livingCategory = Category::where('name', 'Living')->first();
        
        if (!$livingCategory) {
            $livingCategory = Category::create([
                'name' => 'Living',
                'slug' => 'living',
                'description' => 'Living room electronics and home entertainment',
                'status' => true,
                'sort_order' => 3
            ]);
        }

        $products = [
            [
                'name' => '75" 4K OLED Smart TV',
                'slug' => '75-inch-4k-oled-smart-tv',
                'description' => 'Premium 75-inch 4K OLED Smart TV with HDR10+ support, Dolby Vision, and built-in streaming apps. Ultra-thin design with perfect blacks and infinite contrast.',
                'short_description' => '75" 4K OLED Smart TV with HDR10+ and Dolby Vision',
                'price' => 2999.99,
                'sale_price' => 2699.99,
                'sku' => 'LIV-TV-001',
                'stock_quantity' => 12,
                'image' => json_encode('products/living/oled-tv.jpg'),
                'status' => true,
                'featured' => true,
                'weight' => 32.8,
                'dimensions' => '167x96x27 cm'
            ],
            [
                'name' => 'Soundbar 7.1 Surround',
                'slug' => 'soundbar-7-1-surround',
                'description' => '7.1 channel soundbar with wireless subwoofer and rear speakers. Dolby Atmos support, Bluetooth connectivity, and multiple HDMI inputs.',
                'short_description' => '7.1 surround soundbar with wireless subwoofer',
                'price' => 799.99,
                'sale_price' => 699.99,
                'sku' => 'LIV-SOU-002',
                'stock_quantity' => 22,
                'image' => json_encode('products/living/soundbar.jpg'),
                'status' => true,
                'featured' => true,
                'weight' => 8.5,
                'dimensions' => '120x8x12 cm'
            ],
            [
                'name' => 'Air Purifier HEPA H13',
                'slug' => 'air-purifier-hepa-h13',
                'description' => 'Advanced air purifier with HEPA H13 filter, activated carbon layer, and UV-C sterilization. Smart sensors monitor air quality in real-time.',
                'short_description' => 'HEPA H13 air purifier with UV-C sterilization',
                'price' => 549.99,
                'sale_price' => null,
                'sku' => 'LIV-AIR-003',
                'stock_quantity' => 28,
                'image' => json_encode('products/living/air-purifier.jpg'),
                'status' => true,
                'featured' => false,
                'weight' => 12.0,
                'dimensions' => '35x35x65 cm'
            ],
            [
                'name' => 'Robot Vacuum with Mapping',
                'slug' => 'robot-vacuum-with-mapping',
                'description' => 'Intelligent robot vacuum with laser mapping, app control, and automatic emptying station. Multi-surface cleaning with 3000Pa suction power.',
                'short_description' => 'Robot vacuum with laser mapping and auto-empty',
                'price' => 899.99,
                'sale_price' => 799.99,
                'sku' => 'LIV-VAC-004',
                'stock_quantity' => 15,
                'image' => json_encode('products/living/robot-vacuum.jpg'),
                'status' => true,
                'featured' => true,
                'weight' => 4.8,
                'dimensions' => '35x35x9 cm'
            ],
            [
                'name' => 'Smart Home Hub with Voice',
                'slug' => 'smart-home-hub-with-voice',
                'description' => 'Central smart home hub with voice assistant, 10-inch display, and compatibility with all major smart home protocols. Controls lights, security, and more.',
                'short_description' => 'Smart home hub with voice assistant and display',
                'price' => 399.99,
                'sale_price' => 349.99,
                'sku' => 'LIV-HUB-005',
                'stock_quantity' => 35,
                'image' => json_encode('products/living/smart-hub.jpg'),
                'status' => true,
                'featured' => false,
                'weight' => 2.1,
                'dimensions' => '25x17x10 cm'
            ]
        ];

        foreach ($products as $productData) {
            $productData['category_id'] = $livingCategory->id;
            Product::create($productData);
        }
    }
}