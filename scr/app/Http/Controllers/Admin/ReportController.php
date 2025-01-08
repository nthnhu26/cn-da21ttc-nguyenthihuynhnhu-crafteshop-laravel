<?php
// app/Http/Controllers/Admin/ReportController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Exports\InventoryExport;
use App\Exports\RevenueExport;
use App\Exports\BestSellersExport;
use App\Exports\CategoryExport;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    // Báo cáo doanh thu
    public function revenue(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfDay());

        $revenues = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('order_status', 'delivered')
            ->selectRaw('
                DATE(created_at) as date,
                COUNT(*) as total_orders,
                SUM(final_amount) as total_revenue,
                SUM(discount) as total_discount,
                SUM(shipping_fee) as total_shipping
            ')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $totalRevenue = $revenues->sum('total_revenue');
        $totalOrders = $revenues->sum('total_orders');
        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        if ($request->input('export') === 'excel') {
            return Excel::download(new RevenueExport($revenues), 'revenue-report.xlsx');
        }

        if ($request->input('export') === 'pdf') {
            $pdf = PDF::loadView('admin.reports.revenue-pdf', compact(
                'revenues',
                'totalRevenue',
                'totalOrders',
                'avgOrderValue',
                'startDate',
                'endDate'
            ));
            return $pdf->download('revenue-report.pdf');
        }

        return view('admin.reports.revenue', compact(
            'revenues',
            'totalRevenue',
            'totalOrders',
            'avgOrderValue',
            'startDate',
            'endDate'
        ));
    }

    public function inventory(Request $request)
    {
        $products = Product::with('category')
            ->select('products.*')
            ->selectRaw('
                (SELECT SUM(oi.quantity) 
                FROM order_items oi 
                JOIN orders o ON o.order_id = oi.order_id 
                WHERE oi.product_id = products.product_id 
                AND o.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                AND o.order_status = "delivered") as monthly_sales
            ')
            ->get();

        $inventoryStats = [
            'total_products' => $products->count(),
            'total_stock_value' => $products->sum(function ($product) {
                return $product->stock * $product->price;
            }),
            'low_stock_products' => $products->where('stock', '<', 10)->count(),
            'out_of_stock_products' => $products->where('stock', 0)->count(),
        ];

        if ($request->input('export') === 'excel') {
            return Excel::download(new InventoryExport($products), 'inventory-report.xlsx');
        }

        if ($request->input('export') === 'pdf') {
            $pdf = PDF::loadView('admin.reports.inventory-pdf', compact('products', 'inventoryStats'));
            return $pdf->download('inventory-report.pdf');
        }

        return view('admin.reports.inventory', compact('products', 'inventoryStats'));
    }
    // Báo cáo sản phẩm bán chạy
    public function bestSellers(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfDay());

        $bestSellers = DB::table('order_items')
            ->join('orders', 'orders.order_id', '=', 'order_items.order_id')
            ->join('products', 'products.product_id', '=', 'order_items.product_id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->where('orders.order_status', 'delivered')
            ->select(
                'products.product_name',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.total_price) as total_revenue')
            )
            ->groupBy('products.product_id', 'products.product_name')
            ->orderByDesc('total_quantity')
            ->limit(20)
            ->get();

        if ($request->input('export') === 'excel') {
            return Excel::download(new BestSellersExport($bestSellers), 'bestsellers-report.xlsx');
        }

        if ($request->input('export') === 'pdf') {
            $pdf = PDF::loadView('admin.reports.bestsellers-pdf', compact('bestSellers', 'startDate', 'endDate'));
            return $pdf->download('bestsellers-report.pdf');
        }

        return view('admin.reports.bestsellers', compact('bestSellers', 'startDate', 'endDate'));
    }

    // Báo cáo theo danh mục
    public function categoryReport(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfDay());

        $categories = Category::withCount('products')
            ->withSum('products', 'stock')
            ->get()
            ->map(function ($category) use ($startDate, $endDate) {
                // Tính doanh thu theo danh mục
                $revenue = Order::join('order_items', 'orders.order_id', '=', 'order_items.order_id')
                    ->join('products', 'products.product_id', '=', 'order_items.product_id')
                    ->where('products.category_id', $category->category_id)
                    ->whereBetween('orders.created_at', [$startDate, $endDate])
                    ->where('orders.order_status', 'delivered')
                    ->sum('order_items.total_price');

                $category->revenue = $revenue;
                return $category;
            });

        if ($request->input('export') === 'excel') {
            return Excel::download(new CategoryExport($categories), 'category-report.xlsx');
        }

        if ($request->input('export') === 'pdf') {
            $pdf = PDF::loadView('admin.reports.category-pdf', compact('categories', 'startDate', 'endDate'));
            return $pdf->download('category-report.pdf');
        }

        return view('admin.reports.category', compact('categories', 'startDate', 'endDate'));
    }
}
