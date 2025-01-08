<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Product;
use App\Models\PromotionProduct;
use Illuminate\Http\Request;

class ProductPromotionController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $promotions = Promotion::active()->get();
        $productPromotions = PromotionProduct::with(['product', 'promotion'])->get();

        return view('admin.product-promotions.index', compact(
            'products', 
            'promotions', 
            'productPromotions'
        ));
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'promo_id' => 'required|exists:promotions,promo_id'
        ]);

        // Kiểm tra xem sản phẩm đã có khuyến mãi chưa
        $existingPromotion = PromotionProduct::where('product_id', $validatedData['product_id'])
            ->where('promo_id', $validatedData['promo_id'])
            ->first();

        if ($existingPromotion) {
            return back()->with('error', 'Sản phẩm đã có khuyến mãi này.');
        }

        PromotionProduct::create($validatedData);

        return back()->with('success', 'Áp dụng khuyến mãi cho sản phẩm thành công.');
    }

    public function remove(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'promo_id' => 'required|exists:promotions,promo_id'
        ]);

        PromotionProduct::where('product_id', $validatedData['product_id'])
            ->where('promo_id', $validatedData['promo_id'])
            ->delete();

        return back()->with('success', 'Xóa khuyến mãi khỏi sản phẩm thành công.');
    }
}