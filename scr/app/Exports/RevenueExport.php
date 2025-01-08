<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RevenueExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $revenues;

    public function __construct($revenues)
    {
        $this->revenues = $revenues;
    }

    public function collection()
    {
        return $this->revenues;
    }

    public function headings(): array
    {
        return [
            'Ngày',
            'Số đơn hàng',
            'Tổng doanh thu',
            'Tổng giảm giá',
            'Tổng phí giao hàng',
        ];
    }

    public function map($revenue): array
    {
        return [
            $revenue->date,
            $revenue->total_orders,
            $revenue->total_revenue,
            $revenue->total_discount,
            $revenue->total_shipping,
        ];
    }
}
