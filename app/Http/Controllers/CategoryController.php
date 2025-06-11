<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class CategoryController extends BaseController
{
    public function __construct(){
        $this->middleware('auth');
        $this->applyLocaleMiddleware();
    }
    /**
     * Display a listing of categories
     */
    public function index()
    {
        $categories = Category::where('is_active', true)
            ->withCount(['products' => function ($query) {
                $query->where('is_active', true);
            }])
            ->with(['products' => function ($query) {
                $query->where('is_active', true)
                      ->limit(3);
            }])
            ->orderBy('sort_order')
            ->get();

        return view('categories.index', compact('categories'));
    }

    /**
     * Display the specified category and its products
     */
    public function show($slug, Request $request)
    {
        $category = Category::where('slug', $slug)->first();
        
        // Check if category is active
        if (!$category->is_active) {
            abort(404);
        }

        $query = Product::where('category_id', $category->id)
            ->where('status', true);

        // Search within category
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
            default:
                $query->orderBy($sortBy, $sortOrder);
        }

        $products = $query->paginate(12)->appends($request->all());

        // Get price range for this category
        $priceRange = Product::where('category_id', $category->id)
            ->where('status', true)
            ->selectRaw('MIN(price) as min_price, MAX(price) as max_price')
            ->first();

        return view('category.show', compact(
            'category',
            'products',
            'priceRange',
            'request'
        ));
    }

    /**
     * Get products for a category via AJAX
     */
    public function getProducts(Category $category, Request $request)
    {
        if (!$category->is_active) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        $query = Product::where('category_id', $category->id)
            ->where('is_active', true);

        // Apply filters if provided
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
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
            default:
                $query->orderBy('created_at', 'desc');
        }

        $products = $query->limit($request->get('limit', 12))->get();

        return response()->json([
            'products' => $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'formatted_price' => $product->formatted_price,
                    'image' => $product->primary_image_url,
                    'url' => route('products.show', $product),
                    'short_description' => $product->short_description,
                    'is_in_stock' => $product->is_in_stock,
                    'stock_quantity' => $product->stock_quantity
                ];
            })
        ]);
    }

    /**
     * Get category statistics
     */
    public function getStats(Category $category)
    {
        if (!$category->is_active) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        $stats = [
            'total_products' => $category->products()->where('is_active', true)->count(),
            'in_stock_products' => $category->products()
                ->where('is_active', true)
                ->where('stock_quantity', '>', 0)
                ->count(),
            'average_price' => $category->products()
                ->where('is_active', true)
                ->avg('price'),
            'price_range' => $category->products()
                ->where('is_active', true)
                ->selectRaw('MIN(price) as min_price, MAX(price) as max_price')
                ->first()
        ];

        return response()->json($stats);
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
        ]);

        Category::create($validated);
        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
        ]);

        $category->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }

}