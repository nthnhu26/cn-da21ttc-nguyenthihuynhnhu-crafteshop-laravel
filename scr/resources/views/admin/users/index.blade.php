@extends('admin.layouts.master')
@section('header')
Danh sách Người dùng
@endsection
@section('content')
<div class="container-fluid px-4">
    <div class="card shadow mb-4">

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tên</th>
                            <th>Email</th>
                            <th>Trạng thái tài khoản</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge bg-{{ $user->account_status === 'active' ? 'success' : ($user->account_status === 'locked' ? 'danger' : 'warning') }} text-white">
                                    {{ $user->account_status === 'active' ? 'Hoạt động' : 'Đã khóa'}}
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('admin.users.update-status', $user->user_id) }}"
                                    method="POST">
                                    @csrf
                                    <div class="d-flex align-items-center">
                                        <select name="status" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                                            <option value="active" {{ $user->account_status === 'active' ? 'selected' : '' }}>
                                                🟢 Hoạt động
                                            </option>
                                            <option value="locked" {{ $user->account_status === 'locked' ? 'selected' : '' }}>
                                                🔴 Đã khóa
                                            </option>
                                        </select>
                                    </div>
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