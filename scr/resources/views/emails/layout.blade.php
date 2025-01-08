<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Thông báo từ Đồ Thủ Công Mỹ Nghệ')</title>
    <style>
        body {
            background-color: #f4f4f4;
            font-family: 'Arial', sans-serif;
            line-height: 1.8;
            margin: 0;
            padding: 0;
            color: #333333;
        }

        .email-container {
            max-width: 700px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
            border-radius: 15px 15px 0 0;
        }

        .email-header h1 {
            font-size: 28px;
            margin: 0;
            font-weight: 700;
        }

        .email-body {
            padding: 30px;
            font-size: 16px;
            line-height: 1.8;
        }

        .email-footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-radius: 0 0 15px 15px;
            font-size: 14px;
            color: #666666;
            border-top: 1px solid #eeeeee;
        }

        .btn-primary {
            background: #1e3c72;
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin: 20px 0;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        .btn-primary:hover {
            background: #2a5298;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>@yield('title')</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            @yield('content')
        </div>

        <!-- Footer -->
        <div class="email-footer">
            &copy; 2024 Đồ Thủ Công Mỹ Nghệ. Bảo lưu mọi quyền.
        </div>
    </div>
</body>
</html>
