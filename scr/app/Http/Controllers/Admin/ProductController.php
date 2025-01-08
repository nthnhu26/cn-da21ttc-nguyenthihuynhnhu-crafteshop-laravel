<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\InventoryChange;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Admin\ProductRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;
use Maatwebsite\Excel\Validators\ValidationException;
use Illuminate\Support\Facades\View;
use App\Exports\ProductsExport;
use Barryvdh\DomPDF\Facade\Pdf;


class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('status')) {
            if ($request->status === 'low_stock') {
                $query->where('stock', '<', 5)->where('stock', '>', 0);
            } elseif ($request->status === 'out_of_stock') {
                $query->where('stock', 0);
            } elseif ($request->status === 'in_stock') {
                $query->where('stock', '>', 0);
            }
        }

        $products = $query->get();
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.show', compact('product'));
    }
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(ProductRequest $request)
    {
        try {
            DB::beginTransaction();

            // Validate dữ liệu từ request
            $validatedData = $request->validated();

            // Tạo sản phẩm mới
            $product = Product::create($validatedData);

            // Cập nhật trạng thái sản phẩm
            $product->updateStatus(); // Gọi phương thức để cập nhật trạng thái

            // Ghi nhận thay đổi kho hàng
            InventoryChange::create([
                'product_id' => $product->product_id,
                'quantity_change' => $validatedData['stock'],
                'reason' => 'Tạo mới sản phẩm',
                'date' => now(),
            ]);

            // Kiểm tra và lưu ảnh nếu có
            if ($request->hasFile('images')) {
                $image = $request->file('images')[0]; // Lấy ảnh đầu tiên
                $path = $image->store('products', 'public');

                // Tạo bản ghi ProductImage với ảnh chính
                ProductImage::create([
                    'product_id' => $product->product_id,
                    'image_url' => $path,
                    'is_primary' => true, // Ảnh duy nhất, nên tự động là ảnh chính
                ]);
            }

            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được tạo thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Product creation failed: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Đã xảy ra lỗi khi tạo sản phẩm. Vui lòng thử lại!']);
        }
    }


    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        $validatedData = $request->validated();

        DB::transaction(function () use ($validatedData, $request, $product) {
            $oldStock = $product->stock;
            $product->update($validatedData);

            // Cập nhật trạng thái sản phẩm
            $product->updateStatus(); // Gọi phương thức để cập nhật trạng thái

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->product_id,
                        'image_url' => $path
                    ]);
                }
            }

            $stockChange = $validatedData['stock'] - $oldStock;
            if ($stockChange != 0) {
                InventoryChange::create([
                    'product_id' => $product->product_id,
                    'quantity_change' => $stockChange,
                    'reason' => 'Điều chỉnh số lượng',
                    'date' => now(),
                ]);
            }
        });

        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được cập nhật thành công.');
    }

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);

            // Xóa hình ảnh trong thư mục lưu trữ
            if ($product->image) {
                $imagePath = public_path('storage/products/' . $product->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // Xóa tồn kho liên quan
            $product->inventoryChanges()->delete();

            // Xóa sản phẩm
            $product->delete();

            return redirect()->route('admin.products.index')
                ->with('success', 'Sản phẩm đã được xóa thành công.');
        } catch (\Exception $e) {
            return redirect()->route('admin.products.index')
                ->with('error', 'Có lỗi xảy ra khi xóa sản phẩm.');
        }
    }


    public function manageInventory($product_id)
    {
        $product = Product::with('inventoryChanges')->findOrFail($product_id);
        return view('admin.products.manage-inventory', compact('product'));
    }

    public function updateInventory(Request $request, $product_id)
    {
        $request->validate([
            'quantity_change' => 'required|integer',
            'reason' => 'required|string|max:255',
        ]);

        $product = Product::findOrFail($product_id);

        DB::transaction(function () use ($request, $product) {
            $product->stock += $request->quantity_change;
            $product->save();

            // Cập nhật trạng thái sản phẩm
            $product->updateStatus(); // Gọi phương thức để cập nhật trạng thái

            InventoryChange::create([
                'product_id' => $product->product_id,
                'quantity_change' => $request->quantity_change,
                'reason' => $request->reason,
                'date' => now(),
            ]);
        });

        return redirect()->route('admin.products.manage-inventory', $product_id)
            ->with('success', 'Tồn kho đã được cập nhật thành công!');
    }

    public function manageImages($product_id)
    {
        $product = Product::with('images')->findOrFail($product_id);
        return view('admin.products.manage-images', compact('product'));
    }

    public function deleteImage($product_id, $image_id)
    {
        $image = ProductImage::where('product_id', $product_id)->findOrFail($image_id);
        Storage::disk('public')->delete($image->image_url);
        $image->delete();

        return redirect()->route('admin.products.manage-images', $product_id)
            ->with('success', 'Hình ảnh đã được xóa thành công!');
    }

    public function addImages(Request $request, $product_id)
    {
        $request->validate([
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $product = Product::findOrFail($product_id);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->product_id,
                    'image_url' => $path
                ]);
            }
        }

        return redirect()->route('admin.products.manage-images', $product_id)
            ->with('success', 'Hình ảnh đã được thêm thành công!');
    }

    public function setMainImage($product_id, $image_id)
    {
        // Bỏ trạng thái ảnh chính cũ
        ProductImage::where('product_id', $product_id)->update(['is_primary' => false]);

        // Cập nhật ảnh chính mới
        $image = ProductImage::where('product_id', $product_id)->findOrFail($image_id);
        $image->update(['is_primary' => true]);

        return redirect()->route('admin.products.manage-images', $product_id)
            ->with('success', 'Ảnh chính đã được cập nhật thành công!');
    }

    public function showImportForm()
    {
        return view('admin.products.import'); // Tạo file import.blade.php
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:10240',
        ], [
            'file.required' => 'Vui lòng chọn file Excel để import',
            'file.file' => 'Dữ liệu upload phải là file',
            'file.mimes' => 'File phải có định dạng xlsx hoặc xls',
            'file.max' => 'Kích thước file không được vượt quá 10MB',
        ]);

        try {
            $import = new ProductsImport;
            Excel::import($import, $request->file('file'));

            return redirect()->back()
                ->with('success', "Đã import thành công {$import->getSuccessCount()} sản phẩm");
        } catch (ValidationException $e) {
            $failures = $e->failures();
            $errors = collect($failures)->map(function ($failure) {
                $row = $failure->row();
                $productName = isset($failure->values()['product_name'])
                    ? $failure->values()['product_name']
                    : 'Không xác định';

                return "Dòng {$row} - {$productName}: " . implode(', ', $failure->errors());
            })->all();

            return redirect()->back()
                ->withErrors(['validation' => $errors])
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }
    }

    public function export(Request $request)
    {
        // Lấy danh sách ID đã chọn
        $selectedIds = $request->selected ? explode(',', $request->selected) : [];

        // Nếu không chọn, lấy toàn bộ danh mục
        $products = !empty($selectedIds)
            ? Product::whereIn('product_id', $selectedIds)->get()
            : Product::all();

        // Kiểm tra định dạng yêu cầu
        if ($request->format === 'excel') {
            return Excel::download(new ProductsExport($products), 'products.xlsx');
        } elseif ($request->format === 'pdf') {
            $pdf = PDF::loadView('admin.products.pdf', compact('products'));
            return $pdf->download('products.pdf');
        }

        // Trường hợp không hợp lệ
        return redirect()->back()->with('error', 'Định dạng file không hợp lệ.');
    }

    public function deleteSelected(Request $request)
    {
        $selectedIds = explode(',', $request->selected);

        try {
            // Lấy danh sách sản phẩm để xóa hình ảnh
            $products = Product::whereIn('product_id', $selectedIds)->get();

            foreach ($products as $product) {
                // Xóa hình ảnh trong thư mục lưu trữ
                if ($product->image) {
                    $imagePath = public_path('storage/products/' . $product->image);
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
                // Xóa tồn kho liên quan
                $product->inventoryChanges()->delete();
            }

            // Xóa sản phẩm
            Product::whereIn('product_id', $selectedIds)->delete();

            return redirect()->route('admin.products.index')
                ->with('success', 'Các sản phẩm đã được xóa thành công.');
        } catch (\Exception $e) {
            return redirect()->route('admin.products.index')
                ->with('error', 'Có lỗi xảy ra khi xóa các sản phẩm.');
        }
    }
}
