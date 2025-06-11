<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesReportController extends BaseController
{
    public function __construct(){
        $this->middleware('auth');
        $this->applyLocaleMiddleware();
    }

    public function index(Request $request)
    {
        // Get date range from request or default to today
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        $startDate = Carbon::parse($date)->startOfDay();
        $endDate = Carbon::parse($date)->endOfDay();

        // Get daily sales summary
        $dailySales = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'completed')
            ->select(
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(total_amount) as total_sales'),
                DB::raw('AVG(total_amount) as average_order_value')
            )
            ->first();

        // Get sales by product
        $salesByProduct = OrderItem::whereHas('order', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                      ->where('payment_status', 'completed');
            })
            ->select(
                'product_id',
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(total) as total_sales')
            )
            ->with('product:id,name,sku')
            ->groupBy('product_id')
            ->orderByDesc('total_sales')
            ->get();

        // Get sales by hour
        $salesByHour = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'completed')
            ->select(
                DB::raw('HOUR(created_at) as hour'),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(total_amount) as total_sales')
            )
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        // Get payment method distribution
        $paymentMethods = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'completed')
            ->select(
                'payment_method',
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(total_amount) as total_sales')
            )
            ->groupBy('payment_method')
            ->get();

        return view('admin.reports.sales', compact(
            'date',
            'dailySales',
            'salesByProduct',
            'salesByHour',
            'paymentMethods'
        ));
    }
} 