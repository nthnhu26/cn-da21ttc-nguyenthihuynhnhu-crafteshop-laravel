<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Exports\CategoriesExport;
use App\Exports\ProductsExport;
use App\Exports\UsersExport;
use App\Exports\PromotionsExport;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    public function export(Request $request)
    {
        $type = $request->input('type');
        $format = $request->input('format', 'excel');
        $selectedIds = $request->input('selected', []);

        switch ($type) {
            case 'categories':
                $query = Category::query();

                // Áp dụng bộ lọc từ tìm kiếm
                if ($request->has('keyword') && $request->keyword) {
                    $query->where('category_name', 'like', '%' . $request->keyword . '%')
                        ->orWhere('description', 'like', '%' . $request->keyword . '%');
                }
                if ($request->has('status') && $request->status !== null) {
                    $query->where('is_active', $request->status);
                }
    
                // Nếu có chọn dòng cụ thể, chỉ lấy các ID được chọn
                if (!empty($selectedIds)) {
                    $query->whereIn('category_id', $selectedIds);
                }
    
                $data = $query->get();
                $export = new CategoriesExport($data);
                $viewName = 'admin.exports.categories_pdf';
                break;
            case 'products':
                $data = $selectedIds ? Product::whereIn('id', $selectedIds)->with('category')->get() : null;
                $export = new ProductsExport($data);
                $viewName = 'admin.exports.products_pdf';

                break;
            case 'users':
                $data = $selectedIds ? User::whereIn('id', $selectedIds)->get() : null;
                $export = new UsersExport($data);
                $viewName = 'admin.exports.users_pdf';
                break;
            case 'promotions':
                $data = $selectedIds ? Promotion::whereIn('id', $selectedIds)->get() : null;
                $export = new PromotionsExport($data);
                $viewName = 'admin.exports.promotions_pdf';
                break;
            default:
                return back()->with('error', 'Loại dữ liệu không hợp lệ.');
        }

        $fileName = $type . '_' . now()->format('d-m-Y_H-i-s');

        // if ($format === 'excel') {
        //     return Excel::download($export, $fileName . '.xlsx');
        // } elseif ($format === 'pdf') {
        //     $pdf = PDF::loadView($viewName, [
        //         'data' => $data,
        //         'date' => now()->format('d/m/Y H:i:s'),
        //         'tableName' => 'Danh sách ' . $this->getTableName($type),
        //     ]);
        //     return $pdf->download($fileName . '.pdf');
        // }

        return back()->with('error', 'Định dạng xuất không hợp lệ.');
    }

    private function getTableName($type)
    {
        switch ($type) {
            case 'categories':
                return 'danh mục';
            case 'products':
                return 'sản phẩm';
            case 'users':
                return 'người dùng';
            case 'promotions':
                return 'khuyến mãi';
            default:
                return '';
        }
    }
}
