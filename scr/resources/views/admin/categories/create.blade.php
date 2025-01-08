@extends('admin.layouts.master')
@section('header')
Báo cáo thống kê
@endsection
@section('content')
<div class="container-fluid px-4">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ isset($category) ? 'Chỉnh sửa' : 'Tạo mới' }} Danh mục</h4>
                <form action="{{ isset($category) ? route('admin.categories.update', $category) : route('admin.categories.store') }}" method="POST">
                    @csrf
                    @if(isset($category))
                    @method('PUT')
                    @endif
                    <div class="mb-3">
                        <label for="category_name" class="form-label">Tên danh mục</label>
                        <input type="text" class="form-control @error('category_name') is-invalid @enderror"
                            id="category_name" name="category_name" value="{{ old('category_name', $category->category_name ?? '') }}">
                        @error('category_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                            id="description" name="description" rows="3">{{ old('description', $category->description ?? '') }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="display_order" class="form-label">Thứ tự hiển thị</label>
                        <input type="text" class="form-control @error('display_order') is-invalid @enderror"
                            id="display_order" name="display_order" value="{{ old('display_order', $category->display_order ?? '') }}">
                        @error('display_order')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <input type="hidden" name="is_active" value="0">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1"
                                {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Kích hoạt</label>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Hủy</a>
                        <button type="submit" class="btn btn-primary">{{ isset($category) ? 'Cập nhật' : 'Tạo mới' }} danh mục</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection