<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class KitchenProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kitchenCategory = Category::where('name', 'Kitchen')->first();
        
        if (!$kitchenCategory) {
            $kitchenCategory = Category::create([
                'name' => 'Kitchen',
                'slug' => 'kitchen',
                'description' => 'Kitchen appliances and accessories',
                'status' => true,
                'sort_order' => 1
            ]);
        }

        $products = [
            [
                'name' => 'Smart Refrigerator 500L',
                'slug' => 'smart-refrigerator-500l',
                'description' => 'Energy-efficient smart refrigerator with Wi-Fi connectivity and touch display. Features include temperature control, inventory tracking, and mobile app integration.',
                'short_description' => 'Smart 500L refrigerator with Wi-Fi and touch display',
                'price' => 1299.99,
                'sale_price' => 1199.99,
                'sku' => 'KIT-REF-001',
                'stock_quantity' => 25,
                'image' => json_encode('products/kitchen/smart-refrigerator.jpg'),
                'status' => true,
                'featured' => true,
                'weight' => 75.5,
                'dimensions' => '180x70x65 cm'
            ],
            [
                'name' => 'Induction Cooktop 4-Burner',
                'slug' => 'induction-cooktop-4-burner',
                'description' => 'Premium 4-burner induction cooktop with digital controls and safety features. Energy efficient with precise temperature control and timer functions.',
                'short_description' => '4-burner induction cooktop with digital controls',
                'price' => 599.99,
                'sale_price' => 549.99,
                'sku' => 'KIT-IND-002',
                'stock_quantity' => 40,
                'image' => json_encode('products/kitchen/induction-cooktop.jpg'),
                'status' => true,
                'featured' => false,
                'weight' => 12.3,
                'dimensions' => '60x52x8 cm'
            ],
            [
                'name' => 'Multi-Function Microwave 28L',
                'slug' => 'multi-function-microwave-28l',
                'description' => 'Versatile 28L microwave with convection, grill, and steam functions. Pre-programmed settings for various cooking methods and easy-clean interior.',
                'short_description' => '28L microwave with convection and grill functions',
                'price' => 349.99,
                'sale_price' => null,
                'sku' => 'KIT-MIC-003',
                'stock_quantity' => 30,
                'image' => json_encode('products/kitchen/microwave-oven.jpg'),
                'status' => true,
                'featured' => true,
                'weight' => 18.7,
                'dimensions' => '48x39x29 cm'
            ],
            [
                'name' => 'Dishwasher 12 Place Settings',
                'slug' => 'dishwasher-12-place-settings',
                'description' => 'Energy-efficient dishwasher accommodating 12 place settings. Multiple wash cycles, delay start, and quiet operation with A++ energy rating.',
                'short_description' => '12 place setting dishwasher with multiple wash cycles',
                'price' => 799.99,
                'sale_price' => 699.99,
                'sku' => 'KIT-DIS-004',
                'stock_quantity' => 15,
                'image' => json_encode('products/kitchen/dishwasher.jpg'),
                'status' => true,
                'featured' => false,
                'weight' => 45.2,
                'dimensions' => '60x60x85 cm'
            ],
            [
                'name' => 'Stand Mixer 1000W',
                'slug' => 'stand-mixer-1000w',
                'description' => 'Professional-grade 1000W stand mixer with 5L bowl capacity. Includes dough hook, whisk, and paddle attachments. Variable speed control with pulse function.',
                'short_description' => '1000W stand mixer with 5L bowl and attachments',
                'price' => 449.99,
                'sale_price' => 399.99,
                'sku' => 'KIT-MIX-005',
                'stock_quantity' => 35,
                'image' => json_encode('products/kitchen/stand-mixer.jpg'),
                'status' => true,
                'featured' => true,
                'weight' => 8.5,
                'dimensions' => '35x22x35 cm'
            ]
        ];

        foreach ($products as $productData) {
            $productData['category_id'] = $kitchenCategory->id;
            Product::create($productData);
        }
    }
}