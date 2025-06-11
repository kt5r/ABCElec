<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends BaseController
{
    public function __construct(){
        $this->middleware('auth');
        $this->applyLocaleMiddleware();
    }
    /**
     * Display a listing of products
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Filter by category if specified
        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $products = $query->latest()->paginate(10);
        $categories = Category::where('is_active', true)->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Category::where('status', true)->get();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(ProductRequest $request)
    {
        $data = $request->validated();
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['featured_image'] = $path;
        }

        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Set default values
        $data['manage_stock'] = $request->has('manage_stock');
        $data['in_stock'] = $request->has('in_stock');
        $data['featured'] = $request->has('featured');
        $data['status'] = $request->has('status');

        $product = Product::create($data);

        return redirect()
            ->route('products.index')
            ->with('success', __('Product created successfully.'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {
        $data = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->featured_image) {
                Storage::disk('public')->delete($product->featured_image);
            }
            $path = $request->file('image')->store('products', 'public');
            $data['featured_image'] = $path;
        }

        // Set boolean values
        $data['manage_stock'] = $request->has('manage_stock');
        $data['in_stock'] = $request->has('in_stock');
        $data['featured'] = $request->has('featured');
        $data['status'] = $request->has('status');

        $product->update($data);

        return redirect()
            ->route('products.index')
            ->with('success', __('Product updated successfully.'));
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        // Delete product image
        if ($product->featured_image) {
            Storage::disk('public')->delete($product->featured_image);
        }

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', __('Product deleted successfully.'));
    }

    /**
     * Display the specified product
     */
    public function show($slug)
    {
        $product = Product::where('slug', $slug)->first();

        // Check if product is active
        if (!$product->status) {
            abort(404);
        }

        // Get related products
        $product->load(['category']);
        
        $relatedProducts = Product::with('category')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', true)
            ->limit(4)
            ->get();

        return view('product.show', compact(
            'product',
            'relatedProducts',
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
            ->where('status', true)
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
        if (!$product->status) {
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