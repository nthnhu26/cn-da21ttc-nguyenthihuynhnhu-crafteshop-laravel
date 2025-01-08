<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StatisticsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Models\InventoryChange;

class StatisticsController extends Controller
{
    // public function index(Request $request)
    // {
    //     $timeRange = $request->get('time_range', 'day');
    //     $startDate = Carbon::parse($request->get('start_date', Carbon::now()->startOfDay()));
    //     $endDate = Carbon::parse($request->get('end_date', Carbon::now()->endOfDay()));

    //     $orderStats = $this->getOrderStats($startDate, $endDate);
    //     $customerStats = $this->getCustomerStats($startDate, $endDate);
    //     $inventoryStats = $this->getInventoryStats();

    //     return view('admin.statistics.index', compact(
    //         'orderStats',
    //         'customerStats',
    //         'inventoryStats',
    //         'timeRange',
    //         'startDate',
    //         'endDate'
    //     ));
    // }


    // private function getOrderStats($startDate, $endDate)
    // {
    //     return Order::whereBetween('created_at', [$startDate, $endDate])
    //         ->select(
    //             DB::raw('COUNT(*) as total_orders'),
    //             DB::raw('SUM(final_amount) as total_revenue'),
    //             DB::raw('COUNT(DISTINCT user_id) as unique_customers'),
    //             DB::raw("COUNT(CASE WHEN order_status = 'pending' THEN 1 END) as pending_orders"),
    //             DB::raw("COUNT(CASE WHEN order_status = 'confirmed' THEN 1 END) as confirmed_orders"),
    //             DB::raw("COUNT(CASE WHEN order_status = 'processing' THEN 1 END) as processing_orders"),
    //             DB::raw("COUNT(CASE WHEN order_status = 'shipped' THEN 1 END) as shipped_orders"),
    //             DB::raw("COUNT(CASE WHEN order_status = 'delivered' THEN 1 END) as delivered_orders"),
    //             DB::raw("COUNT(CASE WHEN order_status = 'cancelled' THEN 1 END) as cancelled_orders")
    //         )->first();
    // }

    // private function getCustomerStats($startDate, $endDate)
    // {
    //     $totalCustomers = User::where('role_id', 1)->count();
    //     $newCustomers = User::where('role_id', 1)
    //         ->whereBetween('created_at', [$startDate, $endDate])
    //         ->count();

    //     $activeCustomers = Order::whereBetween('created_at', [$startDate, $endDate])
    //         ->distinct('user_id')
    //         ->count('user_id');

    //     return [
    //         'total' => $totalCustomers,
    //         'new' => $newCustomers,
    //         'active' => $activeCustomers
    //     ];
    // }

    // private function getInventoryStats()
    // {
    //     return Product::with(['inventoryChanges' => function ($query) {
    //         $query->latest()->take(10);
    //     }])
    //         ->select('products.*')
    //         ->selectRaw('(SELECT SUM(quantity_change) FROM inventory_changes WHERE product_id = products.product_id) as total_changes')
    //         ->get();
    // }

    // public function getChartData(Request $request)
    // {
    //     $startDate = Carbon::parse($request->get('start_date', Carbon::now()->startOfDay()));
    //     $endDate = Carbon::parse($request->get('end_date', Carbon::now()->endOfDay()));

    //     $data = Order::whereBetween('created_at', [$startDate, $endDate])
    //         ->select(
    //             DB::raw('DATE(created_at) as date'),
    //             DB::raw('COUNT(*) as order_count'),
    //             DB::raw('SUM(final_amount) as revenue')
    //         )
    //         ->groupBy('date')
    //         ->orderBy('date', 'asc')
    //         ->get();

    //     return response()->json($data);
    // }

    // public function getOrderStatusChartData(Request $request)
    // {
    //     $startDate = Carbon::parse($request->get('start_date', Carbon::now()->startOfDay()));
    //     $endDate = Carbon::parse($request->get('end_date', Carbon::now()->endOfDay()));

    //     $orderStats = Order::whereBetween('created_at', [$startDate, $endDate])
    //         ->select(
    //             'order_status',
    //             DB::raw('COUNT(*) as total')
    //         )
    //         ->groupBy('order_status')
    //         ->get();

    //     $labels = [
    //         'pending' => 'Chờ xác nhận',
    //         'confirmed' => 'Đã xác nhận',
    //         'processing' => 'Đang xử lý',
    //         'shipped' => 'Đang giao hàng',
    //         'delivered' => 'Đã giao hàng',
    //         'cancelled' => 'Đã hủy'
    //     ];

    //     $data = [
    //         'labels' => [],
    //         'data' => [],
    //     ];

    //     foreach ($labels as $status => $label) {
    //         $data['labels'][] = $label;
    //         $data['data'][] = $orderStats->where('order_status', $status)->first()->total ?? 0;
    //     }

    //     return response()->json($data);
    // }

    // public function exportExcel(Request $request)
    // {
    //     $startDate = $request->get('start_date', Carbon::now()->startOfDay());
    //     $endDate = $request->get('end_date', Carbon::now()->endOfDay());

    //     return Excel::download(new StatisticsExport($startDate, $endDate), 'statistics.xlsx');
    // }

    // public function exportPdf(Request $request)
    // {
    //     $startDate = $request->get('start_date', Carbon::now()->startOfDay());
    //     $endDate = $request->get('end_date', Carbon::now()->endOfDay());

    //     $orderStats = $this->getOrderStats($startDate, $endDate);
    //     $customerStats = $this->getCustomerStats($startDate, $endDate);
    //     $inventoryStats = $this->getInventoryStats();

    //     $pdf = Pdf::loadView('admin.statistics.pdf', compact('orderStats', 'customerStats', 'inventoryStats', 'startDate', 'endDate'));
    //     return $pdf->download('statistics.pdf');
    // }
    public function index()
    {
        $products = Product::with('category')
            ->select('products.*', 
            DB::raw('(SELECT COUNT(*) FROM inventory_changes 
            WHERE product_id = products.product_id) as changes_count'))
            ->selectRaw('(SELECT SUM(quantity_change) FROM inventory_changes WHERE product_id = products.product_id) as total_changes')
            ->get();
            
        $totalProducts = Product::count();
        $outOfStock = Product::where('stock', '<=', 0)->count();
        $lowStock = Product::where('stock', '>', 0)
                          ->where('stock', '<=', 10)
                          ->count();

        return view('admin.statistics.index', compact(
            'products', 
            'totalProducts',
            'outOfStock',
            'lowStock'
        ));
    }

    public function show($id)
    {
        $product = Product::with(['category', 'inventoryChanges' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])->findOrFail($id);

        $monthlyChanges = InventoryChange::where('product_id', $id)
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(quantity_change) as total_change'),
                DB::raw('COUNT(*) as changes_count')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('admin.statistics.show', compact('product', 'monthlyChanges'));
    }
}