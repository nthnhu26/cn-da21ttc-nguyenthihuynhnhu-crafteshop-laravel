<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Báo cáo tồn kho</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f5f5f5; }
        .header { margin-bottom: 20px; }
        .stats { margin-bottom: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Báo cáo tồn kho</h1>
        <p>Ngày xuất: {{ date('d/m/Y H:i') }}</p>
    </div>

    <div class="stats">
        <p>Tổng số sản phẩm: {{ $inventoryStats['total_products'] }}</p>
        <p>Tổng giá trị tồn kho: {{ number_format($inventoryStats['total_stock_value']) }} VNĐ</p>
        <p>Sản phẩm sắp hết: {{ $inventoryStats['low_stock_products'] }}</p>
        <p>Sản phẩm hết hàng: {{ $inventoryStats['out_of_stock_products'] }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên sản phẩm</th>
                <th>Danh mục</th>
                <th>Giá</th>
                <th>Tồn kho</th>
                <th>Giá trị tồn kho</th>
                <th>Doanh số tháng</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->product_id }}</td>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->category->category_name }}</td>
                <td>{{ number_format($product->price) }}</td>
                <td>{{ $product->stock }}</td>
                <td>{{ number_format($product->stock * $product->price) }}</td>
                <td>{{ $product->monthly_sales ?? 0 }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>