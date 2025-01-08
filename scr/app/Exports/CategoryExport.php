<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CategoryExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $categories;

    public function __construct($categories)
    {
        $this->categories = $categories;
    }

    public function collection()
    {
        return $this->categories;
    }

    public function headings(): array
    {
        return [
            'Tên danh mục',
            'Số lượng sản phẩm',
            'Tổng tồn kho',
            'Doanh thu',
        ];
    }

    public function map($category): array
    {
        return [
            $category->category_name,
            $category->products_count,
            $category->products_sum_stock,
            $category->revenue,
        ];
    }
}
