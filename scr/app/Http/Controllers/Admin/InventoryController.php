<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Product;
use App\Models\InventoryChange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
            ->select('products.*', 
            DB::raw('(SELECT COUNT(*) FROM inventory_changes 
                WHERE product_id = products.product_id) as changes_count'))
            ->get();
            
        $totalProducts = Product::count();
        $outOfStock = Product::where('stock', '<=', 0)->count();
        $lowStock = Product::where('stock', '>', 0)
                          ->where('stock', '<=', 10)
                          ->count();

        return view('admin.inventory.index', compact(
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

        return view('admin.inventory.show', compact('product', 'monthlyChanges'));
    }
    
    public function addStock(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer',
            'reason' => 'required|string|max:255'
        ]);

        DB::transaction(function() use ($request, $id) {
            $product = Product::findOrFail($id);
            
            // Create inventory change record
            InventoryChange::create([
                'product_id' => $id,
                'quantity_change' => $request->quantity,
                'reason' => $request->reason
            ]);
            
            // Update product stock
            $product->stock += $request->quantity;
            $product->save();
        });

        return redirect()->back()->with('success', 'Stock updated successfully');
    }
}