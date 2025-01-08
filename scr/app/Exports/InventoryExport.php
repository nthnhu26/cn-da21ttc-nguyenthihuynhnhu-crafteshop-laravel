<?php
// app/Exports/InventoryExport.php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class InventoryExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $products;

    public function __construct($products)
    {
        $this->products = $products;
    }

    public function collection()
    {
        return $this->products;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Tên sản phẩm',
            'Danh mục',
            'Tồn kho',
            'Giá',
            'Giá trị tồn',
            'Đã bán (30 ngày)',
            'Trạng thái'
        ];
    }

    public function map($product): array
    {
        return [
            $product->product_id,
            $product->product_name,
            $product->category->category_name,
            $product->stock,
            $product->price,
            $product->stock * $product->price,
            $product->monthly_sales ?? 0,
            $this->getStatus($product->stock)
        ];
    }

    private function getStatus($stock)
    {
        if ($stock == 0) return 'Hết hàng';
        if ($stock < 10) return 'Sắp hết';
        return 'Còn hàng';
    }
}