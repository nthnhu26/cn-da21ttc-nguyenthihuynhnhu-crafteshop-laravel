<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ProductsExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected $products;

    public function __construct($products = null)
    {
        $this->products = $products ?? Product::with('category')->get();
    }

    public function collection()
    {
        return $this->products;
    }

    public function headings(): array
    {
        return [
            'Mã sản phẩm',
            'Tên sản phẩm',
            'Giá',
            'Tồn kho',
            'Danh mục',
            'Trạng thái'
        ];
    }

    public function map($product): array
    {
        return [
            $product->product_id,
            $product->product_name,
            number_format($product->price, 0, ',', '.') . ' VNĐ',
            $product->stock,
            $product->category->category_name,
            $this->getStatus($product)
        ];
    }


    private function getStatus($product)
    {
        if ($product->stock > 0) {
            return $product->stock < 5 ? 'Sắp hết hàng' : 'Còn hàng';
        }
        return 'Hết hàng';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->mergeCells('A1:H1');
                $event->sheet->setCellValue('A1', 'Danh sách sản phẩm - ' . now()->format('d/m/Y H:i:s'));
                $event->sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('A1:H1')->getFont()->setBold(true);
            },
        ];
    }
}

