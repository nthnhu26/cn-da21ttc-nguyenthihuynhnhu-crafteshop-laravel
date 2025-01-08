<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Product;
use App\Http\Requests\Admin\PromotionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use Carbon\Carbon;

class PromotionController extends Controller
{
    public function index(Request $request)
    {
        $query = Promotion::query();

        if ($request->filled('status')) {
            $now = Carbon::now();
            switch ($request->status) {
                case 'upcoming':
                    $query->where('start_date', '>', $now);
                    break;
                case 'ongoing':
                    $query->where('start_date', '<=', $now)
                        ->where('end_date', '>=', $now);
                    break;
                case 'expired':
                    $query->where('end_date', '<', $now);
                    break;
            }
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $promotions = $query->latest()->paginate(10);
        return view('admin.promotions.index', compact('promotions'));
    }

    public function create()
    {
        $products = Product::all();
        $categories = Category::all();
        return view('admin.promotions.create', compact('products', 'categories'));
    }

    public function store(PromotionRequest $request)
    {
        try {
            DB::beginTransaction();

            $promotion = Promotion::create($request->validated());

            if ($promotion->type === 'percent' && $request->filled('product_ids')) {
                $promotion->products()->attach($request->product_ids);
            }

            DB::commit();

            return redirect()
                ->route('admin.promotions.index')
                ->with('success', 'Tạo chương trình khuyến mãi thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function edit(Promotion $promotion)
    {
        $products = Product::all();
        $categories = Category::all();
        return view('admin.promotions.edit', compact('promotion', 'products', 'categories'));
    }

    public function update(PromotionRequest $request, Promotion $promotion)
    {
        try {
            DB::beginTransaction();

            $promotion->update($request->validated());

            if ($promotion->type === 'percent') {
                $promotion->products()->sync($request->product_ids ?? []);
            } else {
                $promotion->products()->detach();
            }

            DB::commit();

            return redirect()
                ->route('admin.promotions.index')
                ->with('success', 'Cập nhật chương trình khuyến mãi thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function destroy(Promotion $promotion)
    {
        try {
            if ($promotion->isActive()) {
                return back()->with('error', 'Không thể xóa chương trình khuyến mãi đang diễn ra');
            }

            DB::beginTransaction();

            $promotion->products()->detach();
            $promotion->delete();

            DB::commit();

            return redirect()
                ->route('admin.promotions.index')
                ->with('success', 'Xóa chương trình khuyến mãi thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function getCategoryProducts($categoryId)
    {
        

        $products = Product::where('category_id', $categoryId)->get();

     

        return response()->json($products);
    }
}
