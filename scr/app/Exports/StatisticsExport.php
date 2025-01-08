<?php
// app/Exports/StatisticsExport.php
namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StatisticsExport implements FromCollection, WithHeadings
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return Order::whereBetween('created_at', [$this->startDate, $this->endDate])
            ->select('order_id', 'user_id', 'final_amount', 'created_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Order ID',
            'User  ID',
            'Final Amount',
            'Created At',
        ];
    }
}