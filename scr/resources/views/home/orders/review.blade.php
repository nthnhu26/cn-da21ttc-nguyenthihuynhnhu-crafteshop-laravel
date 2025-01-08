@extends('home.layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Đánh giá sản phẩm</h1>

    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3 mb-md-0">
                    <img src="{{ $product->image_url }}" alt="{{ $product->product_name }}" class="img-fluid rounded">
                </div>
                <div class="col-md-8">
                    <h5 class="card-title">{{ $product->product_name }}</h5>
                    <p class="card-text">Đơn hàng #{{ $order->order_id }}</p>
                    <p class="card-text">Giá: {{ number_format($orderItem->unit_price) }}đ</p>
                    <p class="card-text">Số lượng: {{ $orderItem->quantity }}</p>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('orders.review.submit', ['order' => $order]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->product_id }}">
        <div class="mb-3">
            <label for="title" class="form-label">Tiêu đề đánh giá</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Đánh giá</label>
            <div class="rating">
                @for ($i = 5; $i >= 1; $i--)
                <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" required />
                <label for="star{{ $i }}" title="{{ $i }} sao"></label>
                @endfor
            </div>
        </div>

        <div class="mb-3">
            <label for="comment" class="form-label">Nhận xét</label>
            <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
        </div>

        <div class="mb-3">
            <label for="images" class="form-label">Hình ảnh (tối đa 5 ảnh)</label>
            <input
                type="file"
                class="form-control"
                id="images"
                name="images[]"
                multiple
                accept="image/*"
                onchange="previewImages(event)">
            <small class="text-muted">Bạn chỉ được tải lên tối đa 5 ảnh, mỗi ảnh không quá 2MB.</small>
        </div>

        <div id="preview-container" class="d-flex flex-wrap gap-2"></div>


        <div id="preview-container" class="d-flex flex-wrap gap-2"></div>

        <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
    </form>
</div>

<style>
    .rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
        gap: 5px;
    }

    .rating input {
        display: none;
    }

    .rating label {
        cursor: pointer;
        width: 30px;
        height: 30px;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' width='30' height='30' viewBox='0 0 24 24'%3e%3cpath d='M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z' fill='%23d3d3d3'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: center;
        background-size: 30px;
    }

    .rating label:hover,
    .rating label:hover~label,
    .rating input:checked~label {
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' width='30' height='30' viewBox='0 0 24 24'%3e%3cpath d='M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z' fill='%23ffc107'/%3e%3c/svg%3e");
    }

    #preview-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .preview-item {
        position: relative;
        width: 100px;
        height: 100px;
    }

    .preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 5px;
        border: 1px solid #ddd;
    }

    .preview-item .remove-btn {
        position: absolute;
        top: -5px;
        right: -5px;
        background: red;
        color: white;
        border: none;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
</style>

<script>
    const uploadedFiles = []; // Danh sách tệp đã tải lên

    const previewContainer = document.getElementById('preview-container');

    function previewImages(event) {
        const files = event.target.files;

        // Kiểm tra tổng số ảnh
        if (files.length > 5) {
            alert('Bạn chỉ được chọn tối đa 5 ảnh.');
            event.target.value = ''; // Reset input file
            return;
        }

        previewContainer.innerHTML = ''; // Xóa preview cũ

        Array.from(files).forEach((file) => {
            // Kiểm tra dung lượng từng file (2MB = 2 * 1024 * 1024 bytes)
            if (file.size > 2 * 1024 * 1024) {
                alert(`Ảnh ${file.name} vượt quá dung lượng cho phép (2MB).`);
                event.target.value = ''; // Reset input file
                return;
            }

            const reader = new FileReader();
            reader.onload = (e) => {
                const div = document.createElement('div');
                div.classList.add('preview-item');

                const img = document.createElement('img');
                img.src = e.target.result;

                const btn = document.createElement('button');
                btn.classList.add('remove-btn');
                btn.innerHTML = '&times;';
                btn.onclick = () => {
                    div.remove();
                };

                div.appendChild(img);
                div.appendChild(btn);
                previewContainer.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }
</script>
@endsection