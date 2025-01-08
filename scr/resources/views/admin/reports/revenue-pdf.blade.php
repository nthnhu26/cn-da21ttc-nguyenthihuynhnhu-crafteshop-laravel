<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Báo cáo doanh thu</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f5f5f5; }
        .header { margin-bottom: 20px; }
        .summary { margin-bottom: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Báo cáo doanh thu</h1>
        <p>Từ ngày: {{ date('d/m/Y', strtotime($startDate)) }}</p>
        <p>Đến ngày: {{ date('d/m/Y', strtotime($endDate)) }}</p>
    </div>

    <div class="summary">
        <p>Tổng doanh thu: {{ number_format($totalRevenue) }} VNĐ</p>
        <p>Tổng số đơn hàng: {{ $totalOrders }}</p>
        <p>Giá trị đơn hàng trung bình: {{ number_format($avgOrderValue) }} VNĐ</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Ngày</th>
                <th>Số đơn hàng</th>
                <th>Doanh thu</th>
                <th>Chiết khấu</th>
                <th>Phí vận chuyển</th>
            </tr>
        </thead>
        <tbody>
            @foreach($revenues as $revenue)
            <tr>
                <td>{{ date('d/m/Y', strtotime($revenue->date)) }}</td>
                <td>{{ $revenue->total_orders }}</td>
                <td>{{ number_format($revenue->total_revenue) }}</td>
                <td>{{ number_format($revenue->total_discount) }}</td>
                <td>{{ number_format($revenue->total_shipping) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>