@extends('admin.layouts.master')
@section('header')
Báo cáo thống kê
@endsection
@section('content')
<div class="container-fluid px-4">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách Banner</h6>
            <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">Tạo mới Banner</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tiêu đề</th>
                            <th>Hình ảnh</th>
                            <th>Thời gian bắt đầu</th>
                            <th>Thời gian kết thúc</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($banners as $banner)
                        <tr>
                            <td>{{ $banner->banner_id }}</td>
                            <td>{{ $banner->title }}</td>
                            <td>
                                <img src="{{ asset('storage/' . $banner->image_url) }}" alt="{{ $banner->title }}" style="max-width: 100px;">
                            </td>
                            <td>{{ $banner->start_datetime }}</td>
                            <td>{{ $banner->end_datetime }}</td>
                            <td class="text-white">
                                @switch($banner->status)
                                @case('ongoing')
                                <span class="badge bg-success">Đang diễn ra</span>
                                @break
                                @case('upcoming')
                                <span class="badge bg-info">Sắp diễn ra</span>
                                @break
                                @default
                                <span class="badge bg-danger">Đã kết thúc</span>
                                @endswitch
                            </td>
                            <td>
                                <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa banner này?')"><i class="fas fa-trash"></i></button>
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
@endsection