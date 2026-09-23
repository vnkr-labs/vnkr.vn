@extends('admin.master')

@section('title', 'Thêm Thành Viên Mới')

@section('title-page', 'Thêm Thành Viên Cộng Đồng')

@section('main-content')
    <section class="container mt-4">

        <div class="alert" style="background:#f0f7ff;border:1px solid #c5d9ea;border-left:4px solid #0A3D62;border-radius:4px;padding:10px 16px;margin-bottom:16px;font-size:13px;">
            <i class="bi bi-people-fill me-2" style="color:#F0A500;"></i>
            <strong>VNKR hoàn toàn miễn phí</strong> — Thành viên mới có thể đọc, bình luận và tham gia cộng đồng ngay lập tức.
        </div>

        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title"><i class="bi bi-person-plus me-2"></i>Thêm Thành Viên Mới</h3>
            </div>
            <form role="form" method="POST" action="{{ route('user.store') }}">
                @csrf
                <div class="card-body">

                    <div class="mb-3">
                        <label for="name" class="form-label">Tên Thành Viên <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                            id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                            id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Mật Khẩu <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Xác Nhận Mật Khẩu <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                            id="password_confirmation" name="password_confirmation" required>
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Cấp Độ Trong Cộng Đồng</label>
                        <select name="role" id="role" class="form-select @error('role') is-invalid @enderror">
                            <option value="user"  {{ old('role') == 'user'  ? 'selected' : '' }} selected>Thành viên cộng đồng</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Quản trị viên</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text" style="color:#777;">
                            <i class="bi bi-info-circle me-1"></i>Tất cả cấp độ đều được đọc và tham gia cộng đồng miễn phí.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Trạng Thái Tài Khoản</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" id="status1" value="1"
                                {{ old('status', '1') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="status1">
                                <i class="bi bi-check-circle-fill me-1" style="color:#27ae60;"></i>Hoạt động
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" id="status0" value="0"
                                {{ old('status') == '0' ? 'checked' : '' }}>
                            <label class="form-check-label" for="status0">
                                <i class="bi bi-x-circle-fill me-1" style="color:#dc3545;"></i>Tạm khóa tài khoản
                            </label>
                        </div>
                        @error('status')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="card-footer d-flex justify-content-between align-items-center">
                    <a href="{{ route('user.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Trở Lại
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-person-check me-1"></i>Thêm Thành Viên
                    </button>
                </div>
            </form>
        </div>

    </section>
@endsection
