@extends('admin.master')

@section('title', 'Phân Quyền Cộng Đồng')
@section('title-page', 'Phân Quyền Thành Viên')

@section('main-content')
<section class="container mt-4">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">
                <i class="bi bi-shield-lock me-2" style="color:#F0A500;"></i>Phân Quyền Thành Viên Cộng Đồng
            </h3>
            <div style="font-size:12.5px;color:#888;">
                <i class="bi bi-info-circle me-1"></i>Mọi thành viên đều đọc & tham gia miễn phí — chỉ khác quyền quản trị
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Vai Trò</th>
                        <th>Số Thành Viên</th>
                        <th>Mô Tả</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $i => $row)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>
                            @if($row->role === 'admin' || $row->role == 1)
                                <span class="badge" style="background:#0A3D62;color:#fff;font-size:12px;padding:4px 12px;">
                                    <i class="bi bi-shield-fill me-1"></i>Quản trị viên
                                </span>
                            @else
                                <span class="badge" style="background:#e8f2fa;color:#0A3D62;font-size:12px;padding:4px 12px;">
                                    <i class="bi bi-people-fill me-1"></i>Thành viên cộng đồng
                                </span>
                            @endif
                        </td>
                        <td>
                            <strong style="font-size:16px;">{{ number_format($row->total) }}</strong>
                            <span class="text-muted" style="font-size:12px;"> người</span>
                        </td>
                        <td style="font-size:13px;color:#666;">
                            @if($row->role === 'admin' || $row->role == 1)
                                Toàn quyền quản trị hệ thống, bài viết, thành viên
                            @else
                                Đọc bài, bình luận, lưu bài, tham gia cộng đồng — hoàn toàn miễn phí
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">Chưa có dữ liệu</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer text-muted" style="font-size:12.5px;">
            <i class="bi bi-people me-1"></i>
            Để thay đổi quyền từng thành viên, vào
            <a href="{{ route('user.index') }}" class="fw-bold">Quản lý Thành viên</a> → Chỉnh sửa.
        </div>
    </div>

</section>
@endsection
