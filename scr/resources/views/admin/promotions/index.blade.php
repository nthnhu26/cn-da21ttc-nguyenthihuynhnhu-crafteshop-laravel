@extends('admin.layouts.master')
@section('header')
Danh sách khuyến mãi
@endsection
@section('content')
<div class="container-fluid px-4">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary"></h6>
            <a href="{{ route('admin.promotions.create') }}" class="btn btn-primary">Thêm khuyến mãi</a>
        </div>
        <div class="card-body">
            <div class="btn-group mb-3">
                <a href="{{ route('admin.promotions.index', ['status' => 'all']) }}"
                    class="btn btn-{{ request('status') === 'all' ? 'primary' : 'secondary' }}">Tất cả</a>
                <a href="{{ route('admin.promotions.index', ['status' => 'ongoing']) }}"
                    class="btn btn-{{ request('status') === 'ongoing' ? 'primary' : 'secondary' }}">Đang diễn ra</a>
                <a href="{{ route('admin.promotions.index', ['status' => 'upcoming']) }}"
                    class="btn btn-{{ request('status') === 'upcoming' ? 'primary' : 'secondary' }}">Sắp diễn ra</a>
                <a href="{{ route('admin.promotions.index', ['status' => 'expired']) }}"
                    class="btn btn-{{ request('status') === 'expired' ? 'primary' : 'secondary' }}">Đã kết thúc</a>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên khuyến mãi</th>
                            <th>Loại</th>
                            <th>Giá trị</th>
                            <th>Ngày bắt đầu</th>
                            <th>Ngày kết thúc</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($promotions as $promotion)
                        <tr>
                            <td>{{ $promotion->promo_id }}</td>
                            <td>{{ $promotion->name }}</td>
                            <td>
                                @if($promotion->type == 'percent')
                                Giảm %
                                @else
                                Giảm cố định
                                @endif
                            </td>
                            <td>{{ $promotion->value }}</td>
                            <td>{{ $promotion->start_date }}</td>
                            <td>{{ $promotion->end_date }}</td>
                            <td class="text-white">
                                @switch($promotion->status)
                                @case('Đang diễn ra')
                                <span class="badge bg-success">Đang diễn ra</span>
                                @break
                                @case('Sắp diễn ra')
                                <span class="badge bg-info">Sắp diễn ra</span>
                                @break
                                @default
                                <span class="badge bg-danger">Đã kết thúc</span>
                                @endswitch
                            </td>
                            <td>
                                <a href="{{ route('admin.promotions.edit', $promotion->promo_id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.promotions.destroy', $promotion->promo_id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa?')"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endsection