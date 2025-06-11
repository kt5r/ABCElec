<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;

class DashboardController extends BaseController
{
    public function __construct(){
        // $this->middleware('admin')
        $this->applyLocaleMiddleware();
    }
    
    public function index()
    {
        $stats = [
            'total_products' => Product::count(),
            'total_categories' => Category::count(),
            'total_orders' => Order::count(),
            'total_customers' => User::where('role_id', '2')->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_revenue' => Order::where('payment_status', 'completed')->sum('total_amount'),
        ];
        
        $recentOrders = Order::with('user')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();
        
        $lowStockProducts = Product::where('manage_stock', true)
            ->where('stock_quantity', '<=', 5)
            ->limit(5)
            ->get();
        
        return view('admin.dashboard', compact('stats', 'recentOrders', 'lowStockProducts'));
    }
}
