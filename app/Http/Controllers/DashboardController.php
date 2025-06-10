<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Console\Migrations\BaseCommand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Routing\Controller as BaseController;

class DashboardController extends BaseController
{
    public function __construct()
    {
        $this->middleware('admin');
    }
    
    public function index()
    {
        $stats = [
            'total_products' => Product::count(),
            'total_categories' => Category::count(),
            'total_orders' => Order::count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_revenue' => Order::where('payment_status', 'completed')->sum('total_amount'),
        ];
        
        $recentOrders = Order::with('user')
            ->recentFirst()
            ->limit(5)
            ->get();
        
        $lowStockProducts = Product::where('manage_stock', true)
            ->where('stock_quantity', '<=', 5)
            ->limit(5)
            ->get();
        
        return view('admin.dashboard', compact('stats', 'recentOrders', 'lowStockProducts'));
    }
}

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('product_manager');
    }
    
    public function index(Request $request)
    {
        $query = Product::with('category');
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }
        
        $products = $query->paginate(15);
        $categories = Category::all();
        
        return view('admin.products.index', compact('products', 'categories'));
    }
    
    public function create()
    {
        $categories = Category::active()->get();
        return view('admin.products.create', compact('categories'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string',
            'sku' => 'required|string|unique:products,sku',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string',
        ]);
        
        $productData = $request->except(['images']);
        $productData['slug'] = Str::slug($request->name);
        
        // Handle image uploads
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->extension();
                $image->storeAs('public/products', $imageName);
                $images[] = $imageName;
            }
            $productData['images'] = $images;
            $productData['featured_image'] = $images[0] ?? null;
        }
        
        Product::create($productData);
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully!');
    }
    
    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }
    
    public function edit(Product $product)
    {
        $categories = Category::active()->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }
    
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string',
        ]);
        
        $productData = $request->except(['images']);
        $productData['slug'] = Str::slug($request->name);
        
        // Handle new image uploads
        if ($request->hasFile('images')) {
            // Delete old images
            if ($product->images) {
                foreach ($product->images as $oldImage) {
                    Storage::delete('public/products/' . $oldImage);
                }
            }
            
            $images = [];
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->extension();
                $image->storeAs('public/products', $imageName);
                $images[] = $imageName;
            }
            $productData['images'] = $images;
            $productData['featured_image'] = $images[0] ?? null;
        }
        
        $product->update($productData);
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
    }
    
    public function destroy(Product $product)
    {
        // Delete images
        if ($product->images) {
            foreach ($product->images as $image) {
                Storage::delete('public/products/' . $image);
            }
        }
        
        $product->delete();
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }
}

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('product_manager');
    }
    
    public function index()
    {
        $categories = Category::ordered()->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }
    
    public function create()
    {
        return view('admin.categories.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sort_order' => 'required|integer|min:0',
        ]);
        
        $categoryData = $request->except(['image']);
        $categoryData['slug'] = Str::slug($request->name);
        
        if ($request->hasFile('image')) {
            $imageName = time() . '_' . uniqid() . '.' . $request->file('image')->extension();
            $request->file('image')->storeAs('public/categories', $imageName);
            $categoryData['image'] = $imageName;
        }
        
        Category::create($categoryData);
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully!');
    }
    
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }
    
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sort_order' => 'required|integer|min:0',
        ]);
        
        $categoryData = $request->except(['image']);
        $categoryData['slug'] = Str::slug($request->name);
        
        if ($request->hasFile('image')) {
            // Delete old image
            if ($category->image) {
                Storage::delete('public/categories/' . $category->image);
            }
            
            $imageName = time() . '_' . uniqid() . '.' . $request->file('image')->extension();
            $request->file('image')->storeAs('public/categories', $imageName);
            $categoryData['image'] = $imageName;
        }
        
        $category->update($categoryData);
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully!');
    }
    
    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Cannot delete category with products.');
        }
        
        if ($category->image) {
            Storage::delete('public/categories/' . $category->image);
        }
        
        $category->delete();
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully!');
    }
}

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }
    
    public function index(Request $request)
    {
        $query = Order::with('user');
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        $orders = $query->recentFirst()->paginate(15);
        
        return view('admin.orders.index', compact('orders'));
    }
    
    public function show(Order $order)
    {
        $order->load('items.product', 'user');
        return view('admin.orders.show', compact('order'));
    }
    
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);
        
        $order->update(['status' => $request->status]);
        
        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Order status updated successfully!');
    }
}

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('reports');
    }
    
    public function sales(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());
        
        $orders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->with('items.product')
            ->get();
        
        $totalSales = $orders->sum('total_amount');
        $totalOrders = $orders->count();
        
        $dailySales = $orders->groupBy(function($order) {
            return $order->created_at->format('Y-m-d');
        })->map(function($dayOrders) {
            return $dayOrders->sum('total_amount');
        });
        
        $topProducts = $orders->flatMap->items
            ->groupBy('product_id')
            ->map(function($items) {
                return [
                    'product' => $items->first()->product,
                    'quantity' => $items->sum('quantity'),
                    'revenue' => $items->sum('total_price')
                ];
            })
            ->sortByDesc('revenue')
            ->take(10);
        
        return view('admin.reports.sales', compact(
            'orders', 'totalSales', 'totalOrders', 'dailySales', 'topProducts', 'startDate', 'endDate'
        ));
    }
}