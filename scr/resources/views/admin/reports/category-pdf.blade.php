<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Báo cáo theo danh mục</title>
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
        <h1>Báo cáo theo danh mục</h1>
        <p>Từ ngày: {{ date('d/m/Y', strtotime($startDate)) }}</p>
        <p>Đến ngày: {{ date('d/m/Y', strtotime($endDate)) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Danh mục</th>
                <th>Số sản phẩm</th>
                <th>Tổng tồn kho</th>
                <th>Doanh thu</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
            <tr>
                <td>{{ $category->category_name }}</td>
                <td>{{ $category->products_count }}</td>
                <td>{{ $category->products_sum_stock }}</td>
                <td>{{ number_format($category->revenue) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>