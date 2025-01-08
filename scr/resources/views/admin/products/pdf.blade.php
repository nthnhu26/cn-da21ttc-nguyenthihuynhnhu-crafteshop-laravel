<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Danh sách danh mục</title>
</head>
<body>
    <h3>Danh sách danh mục</h3>
    <table border="1" cellpadding="5" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Mã sản phẩm</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Tồn kho</th>
                <th>Danh mục</th>
                <th>Trạng thái</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product -> product_id }}</td>
                <td>{{ $product -> product_name }}</td>
                <td>{{ $product -> price }}</td>
                <td>{{ $product -> stock }}</td>
                <td>{{ $product -> category -> category_name }}</td>
                <td>{{ $product -> status ? 'Còn hàng' : 'Hết hàng' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
