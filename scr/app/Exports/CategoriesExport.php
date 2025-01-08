<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class CategoriesExport implements FromCollection, WithHeadings, WithMapping, WithEvents
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
            'ID',
            'Tên danh mục',
            'Mô tả',
            'Thứ tự hiển thị',
            'Trạng thái',
        ];
    }

    public function map($category): array
    {
        return [
            $category->category_id,
            $category->category_name,
            $category->description,
            $category->display_order,
            $category->is_active ? 'Hiển thị' : 'Không hiển thị',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->mergeCells('A1:E1');
                $event->sheet->setCellValue('A1', 'Danh sách danh mục - ' . now()->format('d/m/Y H:i:s'));
                $event->sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('A1:E1')->getFont()->setBold(true);
            },
        ];
    }
}
