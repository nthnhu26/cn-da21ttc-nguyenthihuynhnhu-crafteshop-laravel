<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not Found</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
        }

        .error {
            font-size: 100px;
            font-weight: bold;
            color: #343a40;
            position: relative;
            display: inline-block;
        }

        .error {
            color: #5a5c69;
            font-size: 7rem;
            position: relative;
            line-height: 1;
            width: 12.5rem;
        }

        .error:before {
            content: attr(data-text);
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            color: #4e73df;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            animation: glitch-left 3s infinite linear;
        }

        .error:after {
            content: attr(data-text);
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            color: #5395b6;
            text-shadow: -1px -1px 2px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            animation: glitch-right 2.5s infinite linear;
        }

        /* Hiệu ứng chạy toàn bộ chữ */
        @keyframes glitch-left {
            0% {
                clip: rect(0px, 0px, 100px, 0px);
            }
            25% {
                clip: rect(0px, 300px, 100px, 0px);
            }
            50% {
                clip: rect(0px, 600px, 100px, 0px);
            }
            75% {
                clip: rect(0px, 900px, 100px, 0px);
            }
            100% {
                clip: rect(0px, 1200px, 100px, 0px);
            }
        }

        @keyframes glitch-right {
            0% {
                clip: rect(0px, 0px, 100px, 0px);
            }
            25% {
                clip: rect(0px, 300px, 100px, 0px);
            }
            50% {
                clip: rect(0px, 600px, 100px, 0px);
            }
            75% {
                clip: rect(0px, 900px, 100px, 0px);
            }
            100% {
                clip: rect(0px, 1200px, 100px, 0px);
            }
        }

        .content {
            text-align: center;
        }

        .lead,
        .text-gray-500 {
            color: #6c757d;
        }

        .btn-primary {
            text-transform: uppercase;
        }
    </style>
</head>

<body>
    <div class="content">
        <div class="error" data-text="404">404</div>
        <p class="lead text-gray-800 mb-5">Không tìm thấy trang này.</p>
        <p class="text-gray-500 mb-0">Có thể bạn đã nhập sai đường dẫn hoặc trang này đã bị xóa.</p>
        <a href="{{route('home')}}" class="btn btn-primary mt-4">&larr; Trở lại trang chủ</a>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>

</html>
