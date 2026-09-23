@extends('fe.index')
@section('title', 'Chỉnh Sửa Hồ Sơ — VNKR')

@section('main')
<div class="container" style="padding-top:24px;padding-bottom:48px;max-width:720px;">

    @if(session('success'))
    <div class="vnkr-callout vnkr-callout--success mb-3">
        <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
    </div>
    @endif

    {{-- ===== THÔNG TIN CÁ NHÂN ===== --}}
    <div class="cat-block mb-4">
        <div class="cat-block-head"><h3><i class="bi bi-person me-1"></i>THÔNG TIN HỒ SƠ</h3></div>
        <form method="POST" action="{{ route('profile.update') }}" style="padding:16px 0 0;">
            @csrf
            @method('PUT')

            @if($errors->any())
            <div class="vnkr-callout vnkr-callout--danger">{{ $errors->first() }}</div>
            @endif

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="vnkr-field" data-size="medium" @error('name') data-state="error" @enderror>
                        <label class="vnkr-label fw-semibold">Tên hiển thị <span class="text-danger">*</span></label>
                        <div class="vnkr-input-wrap">
                            <span class="vnkr-input-icon vnkr-input-icon--left"><i class="bi bi-person"></i></span>
                            <input type="text" name="name" class="vnkr-input"
                                   value="{{ old('name', $user->name) }}" required autocomplete="name">
                        </div>
                        @error('name')<span class="vnkr-input-error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="vnkr-field" data-size="medium" @error('username') data-state="error" @enderror>
                        <label class="vnkr-label fw-semibold">
                            Username <small class="text-muted">(dùng cho URL tác giả)</small>
                        </label>
                        <div class="vnkr-input-wrap">
                            <span class="vnkr-input-icon vnkr-input-icon--left" style="font-size:13px;width:auto;">@</span>
                            <input type="text" name="username" class="vnkr-input"
                                   value="{{ old('username', $user->username) }}" placeholder="vd: nguyenvan" autocomplete="username">
                        </div>
                        @error('username')<span class="vnkr-input-error">{{ $message }}</span>@enderror
                        <span class="vnkr-input-helper">Chỉ dùng chữ, số, dấu gạch dưới hoặc gạch ngang.</span>
                    </div>
                </div>
                <div class="col-12">
                    <div class="vnkr-field vnkr-field--textarea" data-size="medium">
                        <label class="vnkr-label fw-semibold">Giới thiệu bản thân</label>
                        <div class="vnkr-input-wrap">
                            <textarea name="bio" class="vnkr-input" rows="3"
                                      placeholder="Vài dòng về bạn...">{{ old('bio', $user->bio) }}</textarea>
                        </div>
                        <span class="vnkr-input-helper">Tối đa 500 ký tự.</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="vnkr-field" data-size="medium" @error('facebook_url') data-state="error" @enderror>
                        <label class="vnkr-label fw-semibold">
                            <i class="bi bi-facebook me-1" style="color:#1877f2;"></i>Facebook URL
                        </label>
                        <div class="vnkr-input-wrap">
                            <input type="url" name="facebook_url" class="vnkr-input"
                                   value="{{ old('facebook_url', $user->facebook_url) }}"
                                   placeholder="https://facebook.com/...">
                        </div>
                        @error('facebook_url')<span class="vnkr-input-error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="vnkr-field" data-size="medium" @error('twitter_url') data-state="error" @enderror>
                        <label class="vnkr-label fw-semibold">
                            <i class="bi bi-twitter-x me-1"></i>Twitter / X URL
                        </label>
                        <div class="vnkr-input-wrap">
                            <input type="url" name="twitter_url" class="vnkr-input"
                                   value="{{ old('twitter_url', $user->twitter_url) }}"
                                   placeholder="https://twitter.com/...">
                        </div>
                        @error('twitter_url')<span class="vnkr-input-error">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <a href="{{ route('profile.show') }}" class="vnkr-btn vnkr-btn--ghost vnkr-btn--sm">
                    <i class="bi bi-arrow-left me-1"></i>Trở lại
                </a>
                <button type="submit" class="vnkr-btn vnkr-btn--brand vnkr-btn--sm">
                    <i class="bi bi-save me-1"></i>Lưu Thay Đổi
                </button>
            </div>
        </form>
    </div>

    {{-- ===== ĐỔI MẬT KHẨU ===== --}}
    <div class="cat-block">
        <div class="cat-block-head"><h3><i class="bi bi-lock me-1"></i>ĐỔI MẬT KHẨU</h3></div>
        <form method="POST" action="{{ route('profile.password') }}" style="padding:16px 0 0;">
            @csrf
            @method('PUT')

            @if($errors->has('current_password'))
            <div class="vnkr-callout vnkr-callout--danger">{{ $errors->first('current_password') }}</div>
            @endif

            <div class="row g-3">
                <div class="col-12">
                    <div class="vnkr-field" data-size="medium">
                        <label class="vnkr-label fw-semibold">Mật khẩu hiện tại</label>
                        <div class="vnkr-input-wrap">
                            <span class="vnkr-input-icon vnkr-input-icon--left"><i class="bi bi-lock"></i></span>
                            <input type="password" name="current_password" class="vnkr-input" required autocomplete="current-password">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="vnkr-field" data-size="medium">
                        <label class="vnkr-label fw-semibold">Mật khẩu mới</label>
                        <div class="vnkr-input-wrap">
                            <span class="vnkr-input-icon vnkr-input-icon--left"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" name="password" class="vnkr-input" required minlength="8" autocomplete="new-password">
                        </div>
                        <span class="vnkr-input-helper">Tối thiểu 8 ký tự.</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="vnkr-field" data-size="medium">
                        <label class="vnkr-label fw-semibold">Xác nhận mật khẩu mới</label>
                        <div class="vnkr-input-wrap">
                            <span class="vnkr-input-icon vnkr-input-icon--left"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" name="password_confirmation" class="vnkr-input" required autocomplete="new-password">
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="vnkr-btn vnkr-btn--primary vnkr-btn--sm">
                    <i class="bi bi-key me-1"></i>Đổi Mật Khẩu
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
