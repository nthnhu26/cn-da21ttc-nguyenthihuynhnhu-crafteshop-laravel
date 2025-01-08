<?php
// app/Http/Controllers/StatisticsController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        $timeFrame = $request->get('time_frame', 'month'); // day, week, month, year

        // 1. Thống kê doanh thu
        $revenueStats = $this->getRevenueStats($timeFrame);
        $productRevenue = $this->getProductRevenue();

        // 2. Thống kê đơn hàng
        $orderStats = $this->getOrderStats($timeFrame);
        $orderStatusStats = $this->getOrderStatusStats();

        // 3. Thống kê sản phẩm
        $inventoryStats = $this->getInventoryStats();


        // 4. Thống kê người dùng
        $userStats = $this->getUserStats($timeFrame);

        return view('admin.statistics.index', compact(
            'revenueStats',
            'productRevenue',
            'orderStats',
            'orderStatusStats',

            'inventoryStats',

            'userStats'
        ));
    }

    private function getRevenueStats($timeFrame)
    {
        $query = Payment::where('status', 'completed')
            ->select(
                DB::raw('DATE(payment_date) as date'),
                DB::raw('SUM(amount) as total_revenue')
            );

        switch ($timeFrame) {
            case 'day':
                $query->whereBetween('payment_date', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()]);
                break;
            case 'week':
                $query->whereBetween('payment_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereBetween('payment_date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
                break;
            case 'year':
                $query->whereBetween('payment_date', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
                break;
        }

        return $query->groupBy('date')->get();
    }

    private function getProductRevenue()
    {
        return DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.order_id')
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

    private function getOrderStats($timeFrame)
    {
        $query = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total_orders'),
            DB::raw('SUM(final_amount) as total_amount')
        );

        switch ($timeFrame) {
            case 'day':
                $query->whereDate('created_at', Carbon::today());
                break;
            case 'week':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('created_at', Carbon::now()->month);
                break;
            case 'year':
                $query->whereYear('created_at', Carbon::now()->year);
                break;
        }

        return $query->groupBy('date')->get();
    }

    private function getOrderStatusStats()
    {
        return Order::select('order_status', DB::raw('COUNT(*) as count'))
            ->groupBy('order_status')
            ->get();
    }

    //Thống kê sản phẩm theo danh mục
    private function getInventoryStats()
    {
        // Get all categories first
        $categories = Category::all();

        // Get product counts for categories that have products
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

        // Merge data for all categories
        return $categories->map(function ($category) use ($productCounts) {
            $stats = $productCounts->get($category->category_id);
            return [
                'category_name' => $category->category_name,
                'total_products' => $stats ? $stats->total_products : 0,
                'total_stock' => $stats ? $stats->total_stock : 0
            ];
        });
    }


    private function getUserStats($timeFrame)
    {
        $query = User::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as new_users')
        );

        switch ($timeFrame) {
            case 'day':
                $query->whereDate('created_at', Carbon::today());
                break;
            case 'week':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('created_at', Carbon::now()->month);
                break;
            case 'year':
                $query->whereYear('created_at', Carbon::now()->year);
                break;
        }

        return $query->groupBy('date')->get();
    }
}
