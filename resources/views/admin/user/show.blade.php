@extends('admin.master')

@section('title', 'Hồ Sơ Thành Viên')

@section('title-page', 'Hồ Sơ Thành Viên Cộng Đồng')

@section('main-content')
    <section class="container mt-4">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0"><i class="bi bi-person-circle me-2"></i>{{ $user->name }}</h3>
                @if($user->role === 'admin')
                    <span style="background:rgba(255,255,255,.2);padding:3px 12px;border-radius:3px;font-size:12px;font-weight:700;">Quản trị viên</span>
                @elseif($user->is_author)
                    <span style="background:#27ae60;padding:3px 12px;border-radius:3px;font-size:12px;font-weight:700;">Cộng tác viên</span>
                @else
                    <span style="background:rgba(255,255,255,.15);padding:3px 12px;border-radius:3px;font-size:12px;">Thành viên</span>
                @endif
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless" style="font-size:14px;">
                            <tr>
                                <td style="width:140px;font-weight:700;color:#555;"><i class="bi bi-person me-2 brand-color"></i>Tên</td>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight:700;color:#555;"><i class="bi bi-envelope me-2 brand-color"></i>Email</td>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight:700;color:#555;"><i class="bi bi-shield me-2 brand-color"></i>Cấp độ</td>
                                <td>
                                    @if($user->role === 'admin')
                                        <span style="background:#0A3D62;color:#fff;padding:2px 10px;border-radius:3px;font-size:12px;font-weight:700;">Quản trị viên</span>
                                    @elseif($user->is_author)
                                        <span style="background:#d4edda;color:#218838;padding:2px 10px;border-radius:3px;font-size:12px;font-weight:700;">Cộng tác viên</span>
                                    @else
                                        <span style="background:#f4f6f8;color:#555;padding:2px 10px;border-radius:3px;font-size:12px;">Thành viên cộng đồng</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="font-weight:700;color:#555;"><i class="bi bi-calendar me-2 brand-color"></i>Tham gia</td>
                                <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight:700;color:#555;"><i class="bi bi-clock me-2 brand-color"></i>Cập nhật</td>
                                <td>{{ $user->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        {{-- Đóng góp cộng đồng --}}
                        <div style="background:#f9fafb;border-radius:8px;padding:16px;border:1px solid #e5e7eb;">
                            <div style="font-weight:800;color:#0A3D62;margin-bottom:12px;font-size:14px;">
                                <i class="bi bi-award me-2" style="color:#F0A500;"></i>Đóng Góp Cộng Đồng
                            </div>
                            @php
                                $commentCount  = $user->comments()->count()  ?? 0;
                                $bookmarkCount = $user->bookmarks()->count()  ?? 0;
                                $articleCount  = $user->articles()->count()   ?? 0;
                            @endphp
                            <div class="row g-2">
                                <div class="col-4 text-center">
                                    <div style="font-size:20px;font-weight:900;color:#0A3D62;">{{ $commentCount }}</div>
                                    <div style="font-size:11px;color:#777;"><i class="bi bi-chat me-1"></i>Bình luận</div>
                                </div>
                                <div class="col-4 text-center">
                                    <div style="font-size:20px;font-weight:900;color:#F0A500;">{{ $bookmarkCount }}</div>
                                    <div style="font-size:11px;color:#777;"><i class="bi bi-bookmark me-1"></i>Bài đã lưu</div>
                                </div>
                                <div class="col-4 text-center">
                                    <div style="font-size:20px;font-weight:900;color:#218838;">{{ $articleCount }}</div>
                                    <div style="font-size:11px;color:#777;"><i class="bi bi-pen me-1"></i>Bài đăng</div>
                                </div>
                            </div>
                            @if($commentCount >= 20)
                            <div style="margin-top:10px;background:#fffef5;border:1px solid #f0c040;border-radius:4px;padding:8px 12px;font-size:12px;color:#b8860b;">
                                <i class="bi bi-star-fill me-1" style="color:#F0A500;"></i><strong>Thành viên tích cực</strong> — đủ điều kiện nhận quyền lợi đặc biệt
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <a href="{{ route('user.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i>Trở Lại</a>
                <a href="{{ route('user.edit', $user) }}" class="btn btn-primary"><i class="bi bi-pencil me-1"></i>Chỉnh Sửa</a>
            </div>
        </div>
    </section>
@endsection
