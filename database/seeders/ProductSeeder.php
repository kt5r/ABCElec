<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();

        foreach ($categories as $category) {
            $this->createProductsForCategory($category);
        }
    }

    
    private function createProductsForCategory(Category $category): void
    {
        $products = $this->getProductsData()[$category->slug] ?? [];

        foreach ($products as $product) {
            Product::create([
                'category_id' => $category->id,
                'sku' => $product['sku'],
                'name' => $product['name'],
                'description' => $product['description'],
                'price' => $product['price'],
                'stock_quantity' => $product['stock_quantity'],
                'image' => json_encode(is_array($product['image']) ? $product['image'] : [$product['image']]),
                'featured' => $product['featured'] ?? false,
                'status' => true,
                'meta_title' => $product['name'] . ' - CABC Electronics',
                'meta_description' => substr($product['description'], 0, 160)
            ]);
        }
    }

    private function getProductsData(): array
    {
        return [
            'kitchen' => [
                [
                    'sku' => 'KIT-001',
                    'name' => 'Smart Refrigerator 450L',
                    'description' => 'Energy-efficient smart refrigerator with digital display and temperature control. Features include ice maker, water dispenser, and WiFi connectivity.',
                    'price' => 125000.00,
                    'stock_quantity' => 15,
                    'image' => 'products/smart-refrigerator.jpg',
                    'featured' => true
                ],
                [
                    'sku' => 'KIT-002',
                    'name' => 'Induction Cooktop 2000W',
                    'description' => 'High-performance induction cooktop with touch controls and 9 power levels. Energy-efficient and safe cooking solution.',
                    'price' => 35000.00,
                    'stock_quantity' => 25,
                    'image' => 'products/induction-cooktop.jpg'
                ],
                [
                    'sku' => 'KIT-003',
                    'name' => 'Microwave Oven 25L',
                    'description' => 'Convection microwave oven with grill function. 25L capacity with digital display and preset cooking modes.',
                    'price' => 28000.00,
                    'stock_quantity' => 20,
                    'image' => 'products/microwave-oven.jpg'
                ],
                [
                    'sku' => 'KIT-004',
                    'name' => 'Electric Kettle 1.8L',
                    'description' => 'Stainless steel electric kettle with auto shut-off and boil-dry protection. 1.8L capacity with cord storage.',
                    'price' => 4500.00,
                    'stock_quantity' => 50,
                    'image' => 'products/electric-kettle.jpg'
                ],
                [
                    'sku' => 'KIT-005',
                    'name' => 'Food Processor 800W',
                    'description' => 'Multi-function food processor with multiple attachments. Perfect for chopping, slicing, and mixing.',
                    'price' => 15000.00,
                    'stock_quantity' => 30,
                    'image' => 'products/food-processor.jpg',
                    'featured' => true
                ]
            ],
            'bathroom' => [
                [
                    'sku' => 'BAT-001',
                    'name' => 'Instant Water Heater 15L',
                    'description' => 'Electric instant water heater with digital temperature display. 15L capacity with safety features.',
                    'price' => 18000.00,
                    'stock_quantity' => 20,
                    'image' => 'products/water-heater.jpg'
                ],
                [
                    'sku' => 'BAT-002',
                    'name' => 'Exhaust Fan 6 Inch',
                    'description' => 'High-speed exhaust fan for bathroom ventilation. Silent operation with durable motor.',
                    'price' => 3500.00,
                    'stock_quantity' => 40,
                    'image' => 'products/exhaust-fan.jpg'
                ],
                [
                    'sku' => 'BAT-003',
                    'name' => 'LED Mirror Light 60cm',
                    'description' => 'Energy-efficient LED mirror light with warm white illumination. Easy installation and long-lasting.',
                    'price' => 8500.00,
                    'stock_quantity' => 25,
                    'image' => 'products/mirror-light.jpg'
                ],
                [
                    'sku' => 'BAT-004',
                    'name' => 'Hair Dryer 2000W',
                    'description' => 'Professional hair dryer with ionic technology. Multiple heat and speed settings with cool shot button.',
                    'price' => 6500.00,
                    'stock_quantity' => 35,
                    'image' => 'products/hair-dryer.jpg'
                ],
                [
                    'sku' => 'BAT-005',
                    'name' => 'Electric Toothbrush',
                    'description' => 'Rechargeable electric toothbrush with multiple brushing modes. Includes charging station and travel case.',
                    'price' => 12000.00,
                    'stock_quantity' => 15,
                    'image' => 'products/electric-toothbrush.jpg',
                    'featured' => true
                ]
            ],
            'living' => [
                [
                    'sku' => 'LIV-001',
                    'name' => '55" Smart LED TV',
                    'description' => '4K Ultra HD Smart LED TV with built-in WiFi and streaming apps. HDR support and voice control.',
                    'price' => 175000.00,
                    'stock_quantity' => 10,
                    'image' => 'products/smart-tv.jpg',
                    'featured' => true
                ],
                [
                    'sku' => 'LIV-002',
                    'name' => 'Soundbar 2.1 Channel',
                    'description' => 'Wireless soundbar with subwoofer. Bluetooth connectivity and multiple sound modes for enhanced audio.',
                    'price' => 25000.00,
                    'stock_quantity' => 18,
                    'image' => 'products/soundbar.jpg'
                ],
                [
                    'sku' => 'LIV-003',
                    'name' => 'Air Conditioner 1.5 Ton',
                    'description' => 'Inverter air conditioner with 5-star energy rating. Dual filtration and smart temperature control.',
                    'price' => 95000.00,
                    'stock_quantity' => 12,
                    'image' => 'products/air-conditioner.jpg'
                ],
                [
                    'sku' => 'LIV-004',
                    'name' => 'Ceiling Fan 52 Inch',
                    'description' => 'Energy-efficient ceiling fan with LED lights and remote control. Reversible motor and multiple speed settings.',
                    'price' => 8500.00,
                    'stock_quantity' => 30,
                    'image' => 'products/ceiling-fan.jpg'
                ],
                [
                    'sku' => 'LIV-005',
                    'name' => 'Home Theater System',
                    'description' => '5.1 channel home theater system with wireless rear speakers. Includes DVD player and karaoke function.',
                    'price' => 45000.00,
                    'stock_quantity' => 8,
                    'image' => 'products/home-theater.jpg'
                ]
            ],
            'other' => [
                [
                    'sku' => 'OTH-001',
                    'name' => 'Laptop Cooling Pad',
                    'description' => 'Adjustable laptop cooling pad with dual fans. USB powered with blue LED lighting and ergonomic design.',
                    'price' => 3500.00,
                    'stock_quantity' => 40,
                    'image' => 'products/laptop-cooling-pad.jpg'
                ],
                [
                    'sku' => 'OTH-002',
                    'name' => 'Wireless Power Bank 10000mAh',
                    'description' => 'Fast charging power bank with wireless charging capability. LED display and multiple ports.',
                    'price' => 7500.00,
                    'stock_quantity' => 50,
                    'image' => 'products/power-bank.jpg'
                ],
                [
                    'sku' => 'OTH-003',
                    'name' => 'Bluetooth Speaker Portable',
                    'description' => 'Waterproof Bluetooth speaker with 12-hour battery life. Deep bass and crystal clear audio.',
                    'price' => 5500.00,
                    'stock_quantity' => 35,
                    'image' => 'products/bluetooth-speaker.jpg'
                ],
                [
                    'sku' => 'OTH-004',
                    'name' => 'USB Hub 7-Port',
                    'description' => 'High-speed USB 3.0 hub with individual power switches. Compact design with LED indicators.',
                    'price' => 2500.00,
                    'stock_quantity' => 60,
                    'image' => 'products/usb-hub.jpg'
                ],
                [
                    'sku' => 'OTH-005',
                    'name' => 'Wireless Charger Pad',
                    'description' => 'Fast wireless charging pad compatible with Qi-enabled devices. Includes LED charging indicator.',
                    'price' => 4000.00,
                    'stock_quantity' => 45,
                    'image' => 'products/wireless-charger.jpg'
                ]
            ]
        ];
    }
}