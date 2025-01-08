{{-- resources/views/admin/statistics/pdf.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>Báo cáo thống kê</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Báo cáo thống kê từ {{ $startDate }} đến {{ $endDate }}</h1>

    <h2>Thống kê đơn hàng</h2>
    <p>Tổng đơn hàng: {{ $orderStats->total_orders }}</p>
    <p>Doanh thu: {{ number_format($orderStats->total_revenue) }}đ</p>
    <p>Khách hàng mới: {{ $customerStats['new'] }}</p>
    <p>Khách hàng hoạt động: {{ $customerStats['active'] }}</p>

    <h2 >Thống kê tồn kho</h2>
    <table>
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Tồn kho</th>
                <th>Tổng thay đổi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inventoryStats as $product)
            <tr>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->stock }}</td>
                <td>{{ $product->total_changes }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>