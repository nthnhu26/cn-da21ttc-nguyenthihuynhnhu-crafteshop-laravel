<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\InventoryChange;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Payment;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $timeFrame = $request->get('time_frame', 'month');
        $startDate = $this->getStartDate($timeFrame);
        $endDate = $this->getEndDate($timeFrame);

        $revenueStats = $this->getRevenueStats($startDate, $endDate);
        $productRevenue = $this->getProductRevenue($startDate, $endDate);
        $orderStats = $this->getOrderStats($startDate, $endDate);
        $orderStatusStats = $this->getOrderStatusStats($startDate, $endDate);
        $inventoryStats = $this->getInventoryStats();
        $userStats = $this->getUserStats($startDate, $endDate);

        return view('admin.dashboard.index', compact(
            'revenueStats',
            'productRevenue',
            'orderStats', 
            'orderStatusStats',
            'inventoryStats',
            'userStats',
            'timeFrame'
        ));
    }

    private function getStartDate($timeFrame)
    {
        return match($timeFrame) {
            'day' => Carbon::today(),
            'week' => Carbon::now()->startOfWeek(),
            'month' => Carbon::now()->startOfMonth(),
            'year' => Carbon::now()->startOfYear(),
            default => Carbon::now()->startOfMonth(),
        };
    }

    private function getEndDate($timeFrame) 
    {
        return match($timeFrame) {
            'day' => Carbon::today()->endOfDay(),
            'week' => Carbon::now()->endOfWeek(),
            'month' => Carbon::now()->endOfMonth(),
            'year' => Carbon::now()->endOfYear(),
            default => Carbon::now()->endOfMonth(),
        };
    }

    private function getRevenueStats($startDate, $endDate)
    {
        return Payment::where('status', 'completed')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(payment_date) as date'),
                DB::raw('SUM(amount) as total_revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    private function getProductRevenue($startDate, $endDate)
    {
        return DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.order_id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->where('orders.order_status', 'delivered')
            ->select(
                'products.product_name',
                DB::raw('SUM(order_items.quantity * order_items.unit_price) as total_revenue'),
                DB::raw('COUNT(order_items.order_item_id) as total_orders')
            )
            ->groupBy('products.product_id', 'products.product_name')
            ->orderBy('total_revenue', 'desc')
            ->take(10)
            ->get();
    }

    private function getOrderStats($startDate, $endDate)
    {
        return Order::whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(final_amount) as total_amount')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    private function getOrderStatusStats($startDate, $endDate)
    {
        return Order::whereBetween('created_at', [$startDate, $endDate])
            ->select('order_status', DB::raw('COUNT(*) as count'))
            ->groupBy('order_status')
            ->get();
    }

    private function getInventoryStats()
    {
        $categories = Category::all();
        $productCounts = DB::table('products')
            ->rightJoin('categories', 'products.category_id', '=', 'categories.category_id')
            ->select(
                'categories.category_id',
                'categories.category_name',
                DB::raw('COUNT(products.product_id) as total_products'),
                DB::raw('COALESCE(SUM(products.stock), 0) as total_stock')
            )
            ->groupBy('categories.category_id', 'categories.category_name')
            ->get()
            ->keyBy('category_id');

        return $categories->map(function ($category) use ($productCounts) {
            $stats = $productCounts->get($category->category_id);
            return [
                'category_name' => $category->category_name,
                'total_products' => $stats ? $stats->total_products : 0,
                'total_stock' => $stats ? $stats->total_stock : 0
            ];
        });
    }

    private function getUserStats($startDate, $endDate)
    {
        return User::whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as new_users')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }
}