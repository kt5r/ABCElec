<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends BaseController
{
    public function __construct(){
        $this->middleware('auth');
        $this->applyLocaleMiddleware();
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

