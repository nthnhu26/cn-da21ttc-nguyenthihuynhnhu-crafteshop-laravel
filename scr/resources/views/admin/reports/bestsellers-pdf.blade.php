<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Báo cáo sản phẩm bán chạy</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f5f5f5; }
        .header { margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Báo cáo sản phẩm bán chạy</h1>
        <p>Từ ngày: {{ date('d/m/Y', strtotime($startDate)) }}</p>
        <p>Đến ngày: {{ date('d/m/Y', strtotime($endDate)) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Số lượng bán</th>
                <th>Doanh thu</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bestSellers as $product)
            <tr>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->total_quantity }}</td>
                <td>{{ number_format($product->total_revenue) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>