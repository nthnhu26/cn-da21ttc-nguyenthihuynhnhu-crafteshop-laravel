@extends('admin.layouts.master')
@section('header')
Danh sách các liên hệ
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
                            <th>Họ và tên</th>
                            <th>Email</th>
                            <th>Số điện thoại</th>
                            <th>Nội dung</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contacts as $contact)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $contact->name }}</td>
                            <td>{{ $contact->email }}</td>
                            <td>{{ $contact->phone }}</td>
                            <td>{{ Str::limit($contact->message, 50) }}</td>

                            <td>
                                <span class="badge bg-{{ $contact->status == 'pending' ? 'warning' : 'success' }} text-white">
                                    {{ $contact->status == 'pending' ? 'Đang chờ phản hồi' : 'Đã phản hồi' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.contacts.show', $contact) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
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