<?php

namespace App\Exports;

use App\Models\Promotion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class PromotionsExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected $promotions;

    public function __construct($promotions = null)
    {
        $this->promotions = $promotions ?? Promotion::all();
    }

    public function collection()
    {
        return $this->promotions;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Tên khuyến mãi',
            'Mã khuyến mãi',
            'Giảm giá',
            'Ngày bắt đầu',
            'Ngày kết thúc',
            'Trạng thái',
        ];
    }

    public function map($promotion): array
    {
        return [
            $promotion->id,
            $promotion->name,
            $promotion->code,
            $promotion->discount_type == 'percentage' 
                ? $promotion->discount_value . '%' 
                : number_format($promotion->discount_value, 0, ',', '.') . ' VNĐ',
            $promotion->start_date->format('d/m/Y'),
            $promotion->end_date->format('d/m/Y'),
            $this->getStatus($promotion),
        ];
    }

    private function getStatus($promotion)
    {
        $now = now();
        if ($now < $promotion->start_date) {
            return 'Chưa bắt đầu';
        } elseif ($now > $promotion->end_date) {
            return 'Đã kết thúc';
        } else {
            return 'Đang diễn ra';
        }
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->mergeCells('A1:G1');
                $event->sheet->setCellValue('A1', 'Danh sách khuyến mãi - ' . now()->format('d/m/Y H:i:s'));
                $event->sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('A1:G1')->getFont()->setBold(true);
            },
        ];
    }
}

