<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class CategoryController extends BaseController
{
    public function __construct(){
        $this->applyLocaleMiddleware();
    }
    /**
     * Display a listing of categories
     */
    public function index()
    {
        $categories = Category::where('is_active', true)
            ->withCount(['products' => function ($query) {
                $query->where('status', true);
            }])
            ->with(['products' => function ($query) {
                $query->where('status', true)
                      ->limit(3);
            }])
            ->orderBy('sort_order')
            ->get();

        return view('category.show', compact('categories'));
    }

    /**
     * Display the specified category and its products
     */
    public function show($id)
    {
        // Check if we're in admin context
        if (request()->is('admin/*')) {
            $category = Category::findOrFail($id);
            $category->load(['products' => function ($query) {
                $query->latest()->take(5);
            }]);
            
            return view('admin.categories.show', compact('category'));
        }
        
        // Public context - handle 'all' or find by slug
        if ($id === 'all') {
            $category = null;
            $products = Product::where('status', true)
                ->orderBy('created_at', 'desc')
                ->paginate(12);
        } else {
            $category = Category::where('slug', $id)
                ->where('is_active', true)
                ->firstOrFail();
            
            $products = $category->products()
                ->where('status', true)
                ->orderBy('created_at', 'desc')
                ->paginate(12);
        }
        
        return view('category.show', compact('category', 'products'));
    }

}