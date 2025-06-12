<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class HomeController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->applyLocaleMiddleware();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
     /**
     * Show the application homepage
     */
    public function index()
    {
        // Cache featured products and categories for performance
        $featuredProducts = Cache::remember('featured_products', 3600, function () {
            return Product::with(['category'])
                ->where('status', true)
                ->where('featured', true)
                ->orderBy('created_at', 'desc')
                ->limit(8)
                ->get();
        });

        $categories = Cache::remember('active_categories', 3600, function () {
            return Category::with(['products' => function ($query) {
                $query->where('status', true)->limit(3);
            }])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        });

        // Get latest products
        $latestProducts = Product::with(['category'])
            ->where('status', true)
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Get products by category for homepage sections
        $categoriesWithProducts = [];
        foreach (['kitchen', 'bathroom', 'living', 'other'] as $categorySlug) {
            $category = Category::where('slug', $categorySlug)
                ->where('is_active', true)
                ->first();
            
            if ($category) {
                $categoriesWithProducts[$categorySlug] = [
                    'category' => $category,
                    'products' => Product::where('category_id', $category->id)
                        ->where('status', true)
                        ->limit(4)
                        ->get()
                ];
            }
        }

        return view('home', compact(
            'featuredProducts',
            'categories',
            'latestProducts',
            'categoriesWithProducts'
        ));
    }
}
