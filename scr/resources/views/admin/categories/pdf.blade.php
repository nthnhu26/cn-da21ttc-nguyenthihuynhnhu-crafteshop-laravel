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
                <th>#</th>
                <th>Tên danh mục</th>
                <th>Mô tả</th>
                <th>Thứ tự hiển thị</th>
                <th>Trạng thái</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $category->category_name }}</td>
                <td>{{ $category->description }}</td>
                <td>{{ $category->display_order }}</td>
                <td>{{ $category->is_active ? 'Hiển thị' : 'Không hiển thị' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
