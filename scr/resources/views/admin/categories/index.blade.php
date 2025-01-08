@extends('admin.layouts.master')
@section('header')
Danh sách danh mục
@endsection
@section('content')
<div class="container-fluid px-4">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary"></h6>
            <div class="d-flex">
                <div class="dropdown mb-4">
                    <button class="btn btn-primary dropdown-toggle" type="button"
                        id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        Hành động
                    </button>
                    <div class="dropdown-menu animated--fade-in"
                        aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="{{ route('admin.categories.create') }}">Thêm danh mục</a>
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

            <!-- Tìm kiếm nâng cao -->
            <form method="GET" action="{{ route('admin.categories.index') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <select name="is_active" class="form-control">
                            <option value="">Chọn trạng thái</option>
                            <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Hiển thị</option>
                            <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Không hiển thị</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">Lọc</button>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Đặt lại</a>
                    </div>
                </div>
            </form>
            <form id="deleteSelectedForm" action="{{ route('admin.categories.deleteSelected') }}" method="POST" style="display: none;">
                @csrf
                <input type="hidden" name="selected" id="deleteSelectedIds">
            </form>
            <!-- Danh sách danh mục -->
            <form id="exportForm" action="{{ route('admin.categories.export') }}" method="POST" style="display: none;">
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
                            <th>Tên danh mục</th>
                            <th>Mô tả</th>
                            <th>Thứ tự hiển thị</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                        <tr>
                            <td><input type="checkbox" value="{{ $category->category_id }}" class="selectRow"></td>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $category->category_name }}</td>
                            <td>{{ Str::limit($category->description, 50) }}</td>
                            <td>{{ $category->display_order }}</td>
                            <td>
                                <span class="badge bg-{{ $category->is_active ? 'success' : 'danger' }} text-white">
                                    {{ $category->is_active ? 'Hiển thị' : 'Không hiển thị' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.categories.edit', $category->category_id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category->category_id) }}" method="POST" class="d-inline" id="delete-form-{{ $category->category_id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('{{ $category->category_id }}')">
                                        <i class="fas fa-trash"></i>
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
                    text: 'Vui lòng chọn ít nhất một danh mục để xóa!',
                });
                return;
            }

            Swal.fire({
                title: 'Xác nhận xóa',
                text: 'Bạn có chắc chắn muốn xóa các danh mục đã chọn?',
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