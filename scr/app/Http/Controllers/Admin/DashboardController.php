<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StatsExport;
use App\Models\Order;

use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function dashboard()
    {
        $stats = [
            'total_users' => User::where('role_id', 2)->count(),
            'total_orders' => Order::count(),
            'today_revenue' => Order::whereDate('created_at', Carbon::today())->sum('final_amount'),
            'out_of_stock' => Product::where('status', 'out_of_stock')->count(),
            'revenue_labels' => Order::selectRaw('DATE(created_at) as date')
                ->groupBy('date')
                ->pluck('date')->toArray(),
            'revenue_data' => Order::selectRaw('DATE(created_at) as date, SUM(final_amount) as revenue')
                ->groupBy('date')
                ->pluck('revenue')->toArray(),
            'category_labels' => Category::pluck('category_name')->toArray(),
            'category_data' => Category::withCount('products')->pluck('products_count')->toArray(),
            'order_status_labels' => Order::select('order_status')->distinct()->pluck('order_status')->toArray(),
            'order_status_data' => Order::select('order_status')
                ->groupBy('order_status')
                ->selectRaw('COUNT(*) as count')->pluck('count')->toArray(),
            'top_products' => Product::join('order_items', 'products.product_id', '=', 'order_items.product_id')
                ->select('products.product_name', DB::raw('SUM(order_items.quantity) as total_sold'), DB::raw('SUM(order_items.total_price) as revenue'))
                ->groupBy('products.product_id', 'products.product_name')
                ->orderByDesc('total_sold')
                ->take(5)
                ->get(),
            'top_customers' => User::join('orders', 'users.user_id', '=', 'orders.user_id')
                ->select('users.name', DB::raw('COUNT(orders.order_id) as total_orders'), DB::raw('SUM(orders.final_amount) as total_spent'))
                ->groupBy('users.user_id', 'users.name')
                ->orderByDesc('total_spent')
                ->take(5)
                ->get(),
        ];
    
    
        return view('admin.dashboard', compact('stats'));
    }

}
