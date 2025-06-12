<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends BaseController
{
    public function __construct(){
        $this->middleware('auth');
        $this->applyLocaleMiddleware();
    }
    /**
     * Show admin dashboard
     */
    public function dashboard()
    {
        // Get dashboard statistics
        $stats = $this->getDashboardStats();
        
        // Get recent orders
        $recentOrders = Order::with(['user', 'orderItems'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get low stock products
        $lowStockProducts = Product::where('stock_quantity', '<=', 10)
            ->where('status', true)
            ->orderBy('stock_quantity', 'asc')
            ->limit(10)
            ->get();

        // Get sales data for chart (last 30 days)
        $salesData = $this->getSalesChartData();

        // Get top selling products
        $topProducts = $this->getTopSellingProducts();

        // Get recent customers
        $recentCustomers = User::where('role_id', '2')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', array_merge(
            $stats,
            compact(
                'recentOrders',
                'lowStockProducts',
                'salesData',
                'topProducts',
                'recentCustomers'
            )
        ));
    }

    /**
     * Get dashboard statistics
     */
    private function getDashboardStats()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        return [
            // Orders
            'total_orders' => Order::count(),
            'today_orders' => Order::whereDate('created_at', $today)->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'this_month_orders' => Order::where('created_at', '>=', $thisMonth)->count(),
            'last_month_orders' => Order::whereBetween('created_at', [$lastMonth, $lastMonthEnd])->count(),

            // Revenue
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
            'today_revenue' => Order::where('payment_status', 'paid')
                ->whereDate('created_at', $today)
                ->sum('total_amount'),
            'this_month_revenue' => Order::where('payment_status', 'paid')
                ->where('created_at', '>=', $thisMonth)
                ->sum('total_amount'),
            'last_month_revenue' => Order::where('payment_status', 'paid')
                ->whereBetween('created_at', [$lastMonth, $lastMonthEnd])
                ->sum('total_amount'),

            // Products
            'total_products' => Product::count(),
            'active_products' => Product::where('status', true)->count(),
            'low_stock_products' => Product::where('stock_quantity', '<=', 10)->count(),
            'out_of_stock_products' => Product::where('stock_quantity', 0)->count(),

            // Customers
            'total_customers' => User::where('role_id', '2')->count(),
            'new_customers_today' => User::where('role_id', '2')
                ->whereDate('created_at', $today)
                ->count(),
            'new_customers_this_month' => User::where('role_id', '2')
                ->where('created_at', '>=', $thisMonth)
                ->count(),

            // Categories
            'total_categories' => Category::count(),
            'active_categories' => Category::where('is_active', true)->count(),
        ];
    }

    /**
     * Get sales chart data for the last 30 days
     */
    private function getSalesChartData()
    {
        $data = [];
        $startDate = Carbon::now()->subDays(29);

        for ($i = 0; $i < 30; $i++) {
            $date = $startDate->copy()->addDays($i);
            $revenue = Order::where('payment_status', 'paid')
                ->whereDate('created_at', $date)
                ->sum('total_amount');
            
            $orders = Order::whereDate('created_at', $date)->count();

            $data[] = [
                'date' => $date->format('M d'),
                'revenue' => $revenue,
                'orders' => $orders
            ];
        }

        return $data;
    }

    /**
     * Get top selling products
     */
    private function getTopSellingProducts($limit = 10)
    {
        return Product::select('products.*')
            ->selectRaw('COALESCE(SUM(order_items.quantity), 0) as total_sold')
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
            ->where(function ($query) {
                $query->where('orders.payment_status', 'paid')
                      ->orWhereNull('orders.payment_status');
            })
            ->groupBy('products.id')
            ->orderBy('total_sold', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get monthly comparison data
     */
    public function getMonthlyComparison()
    {
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        $thisMonthData = [
            'orders' => Order::where('created_at', '>=', $thisMonth)->count(),
            'revenue' => Order::where('payment_status', 'paid')
                ->where('created_at', '>=', $thisMonth)
                ->sum('total_amount'),
            'customers' => User::where('role_id', '2')
                ->where('created_at', '>=', $thisMonth)
                ->count(),
        ];

        $lastMonthData = [
            'orders' => Order::whereBetween('created_at', [$lastMonth, $lastMonthEnd])->count(),
            'revenue' => Order::where('payment_status', 'paid')
                ->whereBetween('created_at', [$lastMonth, $lastMonthEnd])
                ->sum('total_amount'),
            'customers' => User::where('role_id', '2')
                ->whereBetween('created_at', [$lastMonth, $lastMonthEnd])
                ->count(),
        ];

        // Calculate percentage changes
        $comparison = [];
        foreach ($thisMonthData as $key => $value) {
            $lastValue = $lastMonthData[$key];
            $change = $lastValue > 0 ? (($value - $lastValue) / $lastValue) * 100 : 0;
            
            $comparison[$key] = [
                'current' => $value,
                'previous' => $lastValue,
                'change' => round($change, 2),
                'trend' => $change >= 0 ? 'up' : 'down'
            ];
        }

        return $comparison;
    }

    /**
     * Get order status distribution
     */
    public function getOrderStatusDistribution()
    {
        return Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();
    }

    /**
     * Get category sales data
     */
    public function getCategorySalesData()
    {
        return Category::select('categories.name')
            ->selectRaw('COALESCE(SUM(order_items.quantity * order_items.price), 0) as total_sales')
            ->leftJoin('products', 'categories.id', '=', 'products.category_id')
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
            ->where(function ($query) {
                $query->where('orders.payment_status', 'paid')
                      ->orWhereNull('orders.payment_status');
            })
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total_sales', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Export dashboard data
     */
    public function exportDashboardData(Request $request)
    {
        $format = $request->get('format', 'csv');
        $type = $request->get('type', 'overview');

        switch ($type) {
            case 'orders':
                return $this->exportOrdersData($format);
            case 'products':
                return $this->exportProductsData($format);
            case 'customers':
                return $this->exportCustomersData($format);
            default:
                return $this->exportOverviewData($format);
        }
    }

    /**
     * Export orders data
     */
    private function exportOrdersData($format)
    {
        $orders = Order::with(['user', 'orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->get();

        $data = $orders->map(function ($order) {
            return [
                'Order Number' => $order->order_number,
                'Customer' => $order->user->name,
                'Email' => $order->user->email,
                'Status' => ucfirst($order->status),
                'Payment Status' => ucfirst($order->payment_status),
                'Total Amount' => $order->total_amount,
                'Items Count' => $order->orderItems->count(),
                'Order Date' => $order->created_at->format('Y-m-d H:i:s'),
            ];
        });

        return $this->generateExport($data, 'orders', $format);
    }

    /**
     * Export products data
     */
    private function exportProductsData($format)
    {
        $products = Product::with('category')->get();
        return $this->generateExport($products, 'products', $format);
    }

    /**
     * Export customers data
     */
    private function exportCustomersData($format)
    {
        $customers = User::where('role_id', '2')->get();
        return $this->generateExport($customers, 'customers', $format);
    }

    /**
     * Export overview data
     */
    private function exportOverviewData($format)
    {
        $overview = $this->getDashboardStats();
        return $this->generateExport($overview, 'overview', $format);
    }

    /**
     * Generate export file
     */
    private function generateExport($data, $filename, $format)
    {
        $timestamp = now()->format('Y-m-d_H-i-s');
        $filename = "{$filename}_{$timestamp}";

        if ($format === 'csv') {
            return response()->streamDownload(function () use ($data) {
                $handle = fopen('php://output', 'w');
                
                if ($data->isNotEmpty()) {
                    // Write headers
                    fputcsv($handle, array_keys($data->first()));
                    
                    // Write data
                    foreach ($data as $row) {
                        fputcsv($handle, $row);
                    }
                }
                
                fclose($handle);
            }, $filename . '.csv', [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '.csv"',
            ]);
        }

        // Add other export formats as needed (Excel, PDF, etc.)
        return response()->json(['error' => 'Export format not supported'], 400);
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('create', User::class);

        return view('admin.users.create', [
            'roles' => [
                'admin' => __('Admin'),
                'operation_manager' => __('Operation Manager'),
                'sales_manager' => __('Sales Manager'),
                'customer' => __('Customer'),
            ],
        ]);
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'in:admin,operation_manager,sales_manager,customer'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'is_active' => true,
        ]);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', __('User created successfully.'));
    }

    /**
     * Display sales reports for Sales Manager
     */
    public function reports()
    {
        $sales = Order::selectRaw('DATE(created_at) as date, SUM(total_amount) as total_sales, COUNT(*) as order_count')
            ->where('payment_status', 'paid')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        return view('admin.reports.index', compact('sales'));
    }

    /**
     * Display a listing of orders
     */
    public function orders(Request $request)
    {
        $query = Order::with(['user'])
            ->latest();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->has('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display a listing of users
     */
    public function users(Request $request)
    {
        $query = User::latest();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status === 'active');
        }

        $users = $query->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Display the specified user's details.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function showUser(User $user)
    {
        $this->authorize('view', $user);

        return view('admin.users.show', [
            'user' => $user,
            'recentOrders' => $user->orders()->latest()->take(5)->get(),
            'totalOrders' => $user->orders()->count(),
            'totalSpent' => $user->orders()->sum('total_amount'),
        ]);
    }

}