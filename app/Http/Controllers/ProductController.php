<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    /**
     * Display a listing of products
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'images'])
            ->where('is_active', true);

        // Filter by category
        if ($request->has('category') && $request->category) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('short_description', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Price range filter
        if ($request->has('min_price') && $request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price') && $request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'popularity':
                $query->orderBy('views_count', 'desc');
                break;
            default:
                $query->orderBy($sortBy, $sortOrder);
        }

        $products = $query->paginate(12)->appends($request->all());

        // Get categories for filter sidebar
        $categories = Category::where('is_active', true)
            ->withCount(['products' => function ($query) {
                $query->where('is_active', true);
            }])
            ->orderBy('name')
            ->get();

        // Get price range for filter
        $priceRange = Product::where('is_active', true)
            ->selectRaw('MIN(price) as min_price, MAX(price) as max_price')
            ->first();

        return view('products.index', compact(
            'products',
            'categories',
            'priceRange',
            'request'
        ));
    }

    /**
     * Display the specified product
     */
    public function show(Product $product)
    {
        // Check if product is active
        if (!$product->is_active) {
            abort(404);
        }

        // Increment view count
        $product->increment('views_count');

        // Load relationships
        $product->load(['category', 'images', 'reviews.user']);

        // Get related products
        $relatedProducts = Product::with(['category', 'images'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        // Calculate average rating
        $averageRating = $product->reviews()->avg('rating') ?? 0;
        $reviewsCount = $product->reviews()->count();

        return view('products.show', compact(
            'product',
            'relatedProducts',
            'averageRating',
            'reviewsCount'
        ));
    }

    /**
     * Search products via AJAX
     */
    public function search(Request $request)
    {
        if (!$request->has('q') || strlen($request->q) < 2) {
            return response()->json([]);
        }

        $searchTerm = $request->q;
        
        $products = Product::with(['category'])
            ->where('is_active', true)
            ->where(function ($query) use ($searchTerm) {
                $query->where('name', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('description', 'LIKE', "%{$searchTerm}%");
            })
            ->limit(10)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->formatted_price,
                    'image' => $product->primary_image_url,
                    'url' => route('products.show', $product),
                    'category' => $product->category->name ?? ''
                ];
            });

        return response()->json($products);
    }

    /**
     * Get product details for AJAX requests
     */
    public function getProductDetails(Product $product)
    {
        if (!$product->is_active) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'formatted_price' => $product->formatted_price,
            'description' => $product->short_description,
            'image' => $product->primary_image_url,
            'stock_quantity' => $product->stock_quantity,
            'is_in_stock' => $product->is_in_stock
        ]);
    }
}