<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BestSellersExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $bestSellers;

    public function __construct($bestSellers)
    {
        $this->bestSellers = $bestSellers;
    }

    public function collection()
    {
        return $this->bestSellers;
    }

    public function headings(): array
    {
        return [
            'Tên sản phẩm',
            'Số lượng đã bán',
            'Doanh thu',
        ];
    }

    public function map($bestSeller): array
    {
        return [
            $bestSeller->product_name,
            $bestSeller->total_quantity,
            $bestSeller->total_revenue,
        ];
    }
}
