@extends('admin.master')

@section('title', 'Chỉnh Sửa Thành Viên')

@section('title-page', 'Chỉnh Sửa Thành Viên Cộng Đồng')

@section('main-content')
    <section class="container mt-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title"><i class="bi bi-person-gear me-2"></i>Chỉnh Sửa: {{ $user->name }}</h3>
            </div>
            <form role="form" action="{{ route('user.update', $user) }}" method="POST">
                @method('PUT')
                @csrf
                <input type="hidden" name="id" value="{{ $user->id }}">
                <div class="card-body">

                    <div class="mb-3">
                        <label for="name" class="form-label">Tên Thành Viên <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Mật Khẩu <small class="text-muted">(để trống nếu không đổi)</small></label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                            name="password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Xác Nhận Mật Khẩu</label>
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                            id="password_confirmation" name="password_confirmation">
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Cấp độ cộng đồng --}}
                    <div class="mb-3">
                        <label for="role" class="form-label">Cấp Độ Trong Cộng Đồng <span class="text-danger">*</span></label>
                        <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                            <option value="user"  {{ old('role', $user->role) == 'user'  ? 'selected' : '' }}>Thành viên cộng đồng</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Quản trị viên</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text" style="color:#777;">
                            <i class="bi bi-info-circle me-1"></i>Tất cả thành viên đều có quyền đọc và tham gia miễn phí.
                        </div>
                    </div>

                    {{-- Cấp độ đóng góp --}}
                    <div class="mb-3">
                        <label class="form-label">Cấp Độ Đóng Góp</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_author" id="is_author" value="1"
                                {{ old('is_author', $user->is_author) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_author" style="font-size:13.5px;">
                                <span style="color:#218838;font-weight:700;">
                                    <i class="bi bi-pen-fill me-1"></i>Cộng tác viên VNKR
                                </span>
                                <small class="text-muted d-block">Có quyền gửi bài viết để xét đăng, tên tác giả hiển thị trên bài</small>
                            </label>
                        </div>
                    </div>

                    {{-- Trạng thái --}}
                    <div class="mb-3">
                        <label class="form-label">Trạng Thái Tài Khoản</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" id="status1" value="1"
                                {{ old('status', $user->status) == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="status1">
                                <i class="bi bi-check-circle-fill me-1" style="color:#27ae60;"></i>Hoạt động
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" id="status0" value="0"
                                {{ old('status', $user->status) == 0 ? 'checked' : '' }}>
                            <label class="form-check-label" for="status0">
                                <i class="bi bi-x-circle-fill me-1" style="color:#dc3545;"></i>Tạm khóa tài khoản
                            </label>
                        </div>
                    </div>

                </div>

                <div class="card-footer d-flex justify-content-between align-items-center">
                    <a href="{{ route('user.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Trở Lại
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Lưu Thay Đổi
                    </button>
                </div>
            </form>
        </div>
    </section>
@endsection
