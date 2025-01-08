@extends('admin.layouts.master')
@section('header')
Quản lý sản phẩm
@endsection
@section('content')
<div class="container-fluid px-4">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary"></h6>
            <div>
                <div class="dropdown mb-4">
                    <button class="btn btn-primary dropdown-toggle" type="button"
                        id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        Hành động
                    </button>
                    <div class="dropdown-menu animated--fade-in"
                        aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="{{ route('admin.products.create') }}">Thêm Sản phẩm mới</a>
                        <a class="dropdown-item" href="{{ route('admin.products.import-form') }}">Nhập file Excel </a>
                        <a class="dropdown-item" href="#" id="exportExcel">Xuất Excel</a>
                        <a class="dropdown-item" href="#" id="exportPDF">Xuất PDF</a>
                        <a class="dropdown-item" href="#" id="deleteSelected">Xóa được chọn</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif
            @if(session('summary'))
            <div class="alert alert-info">
                <p>Thành công: {{ session('summary')['success_count'] }}</p>
                <p>Lỗi: {{ session('summary')['error_count'] }}</p>
                @if(session('summary')['errors'])
                <ul>
                    @foreach(session('summary')['errors'] as $error)
                    <li>Dòng {{ $error['row'] }}: {{ $error['errors'] }}</li>
                    @endforeach
                </ul>
                @endif
            </div>
            @endif

            <form action="{{ route('admin.products.index') }}" method="GET" class="mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <select name="category" class="form-control">
                            <option value="">Tất cả danh mục</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->category_id }}" {{ request('category') == $category->category_id ? 'selected' : '' }}>
                                {{ $category->category_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-control">
                            <option value="">Tất cả trạng thái</option>
                            <option value="in_stock" {{ request('status') == 'in_stock' ? 'selected' : '' }}>Còn hàng</option>
                            <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Hết hàng</option>
                            <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>Sắp hết hàng</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Lọc</button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Đặt lại</a>
                    </div>
                </div>
            </form>
            <form id="deleteSelectedForm" action="{{ route('admin.products.deleteSelected') }}" method="POST" style="display: none;">
                @csrf
                <input type="hidden" name="selected" id="deleteSelectedIds">
            </form>
            <!-- Danh sách danh mục -->
            <form id="exportForm" action="{{ route('admin.products.export') }}" method="POST" style="display: none;">
                @csrf
                <input type="hidden" name="selected" id="selectedIds">
                <input type="hidden" name="format">
            </form>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>#</th>
                            <th>Tên</th>
                            <th>Giá</th>
                            <th>Tồn kho</th>
                            <th>Danh mục</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td><input type="checkbox" value="{{ $product->product_id }}" class="selectRow"></td>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $product->product_name }}</td>
                            <td>{{ number_format($product->price, 0, ',', '.') }} VNĐ</td>
                            <td>
                                {{ $product->stock }}

                            </td>
                            <td>{{ $product->category->category_name }}</td>
                            <td>
                                @if($product->status === 'in_stock')
                                    <span class="badge badge-success">Còn hàng</span>
                                @elseif($product->status === 'low_stock')
                                    <span class="badge badge-warning">Sắp hết hàng</span>
                                @elseif($product->status === 'out_of_stock')
                                    <span class="badge badge-danger">Hết hàng</span>
                                @else
                                    <span class="badge badge-secondary">Không xác định</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.products.show', $product->product_id) }}" class="btn btn-sm btn-info hover-icon-btn">
                                    <i class="fas fa-eye"></i>
                                    <span class="tooltip-text">Xem chi tiết</span>
                                </a>

                                <a href="{{ route('admin.products.edit', $product->product_id) }}" class="btn btn-sm btn-primary hover-icon-btn">
                                    <i class="fas fa-edit"></i>
                                    <span class="tooltip-text">Chỉnh sửa</span>
                                </a>

                                <a href="{{ route('admin.products.manage-inventory', $product->product_id) }}" class="btn btn-sm btn-info hover-icon-btn">
                                    <i class="fas fa-warehouse"></i>
                                    <span class="tooltip-text">Quản lý tồn kho</span>
                                </a>

                                <a href="{{ route('admin.products.manage-images', $product->product_id) }}" class="btn btn-sm btn-secondary hover-icon-btn">
                                    <i class="fas fa-images"></i>
                                    <span class="tooltip-text">Quản lý hình ảnh</span>
                                </a>

                                <form action="{{ route('admin.products.destroy', $product->product_id) }}" method="POST" class="d-inline" id="delete-form-{{ $product->product_id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger hover-icon-btn" onclick="confirmDelete('{{ $product->product_id }}')">
                                        <i class="fas fa-trash"></i>
                                        <span class="tooltip-text">Xóa sản phẩm</span>
                                    </button>
                                </form>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Select All functionality
        const selectAllCheckbox = document.getElementById('selectAll');
        const rowCheckboxes = document.querySelectorAll('.selectRow');

        selectAllCheckbox.addEventListener('change', function() {
            rowCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Get selected IDs helper function
        function getSelectedIds() {
            return Array.from(document.querySelectorAll('.selectRow:checked')).map(cb => cb.value);
        }

        // Export functionality
        document.getElementById('exportExcel').addEventListener('click', function(e) {
            e.preventDefault();
            handleExport('excel');
        });

        document.getElementById('exportPDF').addEventListener('click', function(e) {
            e.preventDefault();
            handleExport('pdf');
        });

        function handleExport(format) {
            const selectedIds = getSelectedIds();
            document.getElementById('selectedIds').value = selectedIds.join(',');
            document.querySelector('#exportForm input[name="format"]').value = format;
            document.getElementById('exportForm').submit();
        }

        // Delete selected functionality
        document.getElementById('deleteSelected').addEventListener('click', function(e) {
            e.preventDefault();
            const selectedIds = getSelectedIds();

            if (selectedIds.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Cảnh báo',
                    text: 'Vui lòng chọn ít nhất một sản phẩm để xóa!',
                });
                return;
            }

            Swal.fire({
                title: 'Xác nhận xóa',
                text: 'Bạn có chắc chắn muốn xóa các sản phẩm đã chọn?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteSelectedIds').value = selectedIds.join(',');
                    document.getElementById('deleteSelectedForm').submit();
                }
            });
        });
    });
</script>
@endsection