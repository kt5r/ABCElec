<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Kitchen',
                'slug' => 'kitchen',
                'description' => 'Kitchen appliances and accessories for modern homes',
                'image' => 'categories/kitchen.jpg',
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'Bathroom',
                'slug' => 'bathroom',
                'description' => 'Bathroom fixtures and accessories',
                'image' => 'categories/bathroom.jpg',
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'name' => 'Living',
                'slug' => 'living',
                'description' => 'Living room electronics and entertainment systems',
                'image' => 'categories/living.jpg',
                'is_active' => true,
                'sort_order' => 3
            ],
            [
                'name' => 'Other',
                'slug' => 'other',
                'description' => 'Other electronic accessories and gadgets',
                'image' => 'categories/other.jpg',
                'is_active' => true,
                'sort_order' => 4
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}