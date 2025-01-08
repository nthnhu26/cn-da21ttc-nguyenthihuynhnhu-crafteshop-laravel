<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Exports\CategoriesExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::orderBy('display_order')->get();
        if ($request->has('is_active')) {
            $categories = $categories->where('is_active', $request->is_active);
        }
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(CategoryRequest $request)
    {
        Category::create($request->only(['category_name', 'description', 'display_order', 'is_active']));

        return redirect()->route('admin.categories.index')
            ->with('success', 'Danh mục được tạo thành công.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.create', compact('category'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $category->update($request->only(['category_name', 'description', 'display_order', 'is_active']));
        return redirect()->route('admin.categories.index')
            ->with('success', 'Danh mục được cập nhật thành công.');
    }

    public function destroy(Category $category)
    {
        try {
            if ($category->products()->exists()) {
                return redirect()->route('admin.categories.index')
                    ->with('error', 'Không thể xóa danh mục này vì đang có sản phẩm liên kết.');
            }

            $category->delete();
            return redirect()->route('admin.categories.index')
                ->with('success', 'Danh mục đã được xóa thành công.');
        } catch (\Exception $e) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Có lỗi xảy ra khi xóa danh mục.');
        }
    }
    public function getProducts($id)
    {
        $category = Category::findOrFail($id);
        $products = $category->products; // Quan hệ products trong Model Category
        return response()->json($products);
    }

    public function export(Request $request)
    {
        // Lấy danh sách ID đã chọn
        $selectedIds = $request->selected ? explode(',', $request->selected) : [];

        // Nếu không chọn, lấy toàn bộ danh mục
        $categories = !empty($selectedIds)
            ? Category::whereIn('category_id', $selectedIds)->get()
            : Category::all();

        // Kiểm tra định dạng yêu cầu
        if ($request->format === 'excel') {
            return Excel::download(new CategoriesExport($categories), 'categories.xlsx');
        } elseif ($request->format === 'pdf') {
            $pdf = PDF::loadView('admin.categories.pdf', compact('categories'));
            return $pdf->download('categories.pdf');
        }

        // Trường hợp không hợp lệ
        return redirect()->back()->with('error', 'Định dạng file không hợp lệ.');
    }

    public function deleteSelected(Request $request)
    {
        $selectedIds = explode(',', $request->selected);

        // Check if any category has related products
        $categoriesWithProducts = Category::whereIn('category_id', $selectedIds)
            ->whereHas('products')
            ->exists();

        if ($categoriesWithProducts) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Không thể xóa vì các danh mục đang chứa sản phẩm.');
        }

        try {
            Category::whereIn('category_id', $selectedIds)->delete();
            return redirect()->route('admin.categories.index')
                ->with('success', 'Các danh mục đã được xóa thành công.');
        } catch (\Exception $e) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Có lỗi xảy ra khi xóa các danh mục.');
        }
    }
}
