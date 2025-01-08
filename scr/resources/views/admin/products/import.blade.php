@extends('admin.layouts.master')
@section('header')
Báo cáo thống kê
@endsection
@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Import Sản Phẩm</h1>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Form Import Sản Phẩm</h6>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->has('error'))
            <div class="alert alert-danger">
                {{ $errors->first('error') }}
            </div>
        @endif

        @if($errors->has('duplicates'))
            <div class="alert alert-danger">
                <strong>Lỗi: Phát hiện sản phẩm trùng lặp</strong>
                <ul class="mt-2 mb-0">
                    @foreach($errors->get('duplicates') as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($errors->has('validation'))
            <div class="alert alert-danger">
                <strong>Lỗi dữ liệu trong file:</strong>
                <ul class="mt-2 mb-0">
                    @foreach($errors->get('validation') as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
            @csrf
            <div class="form-group mb-3">
                <label for="file">Chọn file Excel/CSV</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input @error('file') is-invalid @enderror" 
                           id="file" name="file" accept=".xlsx,.xls">
                    <label class="custom-file-label" for="file">Chọn file...</label>
                </div>
                @error('file')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-file-import fa-sm mr-2"></i>Import Sản Phẩm
            </button>
        </form>

        <!-- Hướng dẫn -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Hướng dẫn Import</h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>Lưu ý:</strong> Tên sản phẩm phải là duy nhất trong hệ thống.
                </div>
                
                <h6 class="font-weight-bold">Yêu cầu file Excel:</h6>
                <ul class="pl-4">
                    <li><strong>Các cột bắt buộc:</strong>
                        <ul>
                            <li>product_name (Tên sản phẩm - không được trùng)</li>
                            <li>price (Giá sản phẩm)</li>
                            <li>category_id (ID danh mục)</li>
                        </ul>
                    </li>
                    <li><strong>Các cột tùy chọn:</strong>
                        <ul>
                            <li>description (Mô tả chi tiết)</li>
                            <li>short_description (Mô tả ngắn)</li>
                            <li>stock (Số lượng tồn kho)</li>
                            <li>weight (Khối lượng)</li>
                            <li>dimensions (Kích thước)</li>
                            <li>material (Chất liệu)</li>
                            <li>origin (Xuất xứ)</li>
                            <li>warranty_period (Thời gian bảo hành)</li>
                            <li>images (URL ảnh, phân cách bằng dấu phẩy)</li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('file');
    const form = document.getElementById('importForm');
    
    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const fileSize = file.size / 1024 / 1024; // Convert to MB
            const allowedExtensions = ['xlsx', 'xls'];
            const fileExtension = file.name.split('.').pop().toLowerCase();

            if (!allowedExtensions.includes(fileExtension)) {
                alert('Vui lòng chọn file Excel (.xlsx, .xls)');
                this.value = '';
                return;
            }

            if (fileSize > 10) {
                alert('Kích thước file không được vượt quá 10MB');
                this.value = '';
                return;
            }

            const label = this.nextElementSibling;
            label.textContent = file.name;
        }
    });

    form.addEventListener('submit', function(e) {
        if (!fileInput.files.length) {
            e.preventDefault();
            alert('Vui lòng chọn file để import');
            return;
        }

        if (!confirm('Bạn có chắc chắn muốn import dữ liệu từ file này?')) {
            e.preventDefault();
        }
    });
});
</script>

@endsection