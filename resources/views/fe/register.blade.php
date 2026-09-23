@extends('fe.index')
@section('title', 'Đăng Ký — Tham Gia Cộng Đồng VNKR Miễn Phí')
@section('main')
<div class="container" style="padding-top:40px;padding-bottom:60px;">
  <div class="row justify-content-center">
    <div class="col-lg-5 col-md-7">
      <div class="text-center mb-4">
        <div style="font-size:36px;font-weight:900;color:var(--brand);border-bottom:4px solid var(--accent);display:inline-block;padding:4px 16px;border-radius:4px;">VNKR</div>
        <p class="mt-2 mb-1" style="font-size:15px;font-weight:700;color:var(--brand);">Cộng đồng phục vụ cộng đồng</p>
        <p class="text-muted" style="font-size:13px;">Tham gia hoàn toàn miễn phí — Đóng góp để nhận quyền lợi</p>
      </div>

      {{-- Lợi ích nhanh --}}
      <div class="vnkr-callout vnkr-callout--info mb-3">
        <div style="font-size:13px;font-weight:700;margin-bottom:8px;"><i class="bi bi-gift-fill me-1" style="color:var(--accent);"></i>Khi tham gia bạn được:</div>
        <div style="font-size:13px;line-height:1.9;">
          <div><i class="bi bi-check-circle-fill me-2" style="color:#27ae60;"></i>Đọc tất cả nội dung không giới hạn</div>
          <div><i class="bi bi-check-circle-fill me-2" style="color:#27ae60;"></i>Bình luận và tương tác với cộng đồng</div>
          <div><i class="bi bi-check-circle-fill me-2" style="color:#27ae60;"></i>Nhận bản tin email hàng tuần</div>
          <div><i class="bi bi-star-fill me-2" style="color:#F0A500;"></i>Đóng góp tích cực → Badge + quyền lợi đặc biệt</div>
        </div>
      </div>

      <div class="auth-card card">
        <div class="card-header"><h4 class="mb-0"><i class="bi bi-person-plus me-2"></i>Đăng Ký Tài Khoản — Miễn Phí</h4></div>
        <div class="card-body p-4">
          @if($errors->any())
            <div class="vnkr-callout vnkr-callout--danger">
              @foreach($errors->all() as $e)<div><i class="bi bi-x-circle me-1"></i>{{ $e }}</div>@endforeach
            </div>
          @endif
          <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="mb-3">
              <div class="vnkr-field" data-size="medium">
                <label class="vnkr-label fw-600">Tên hiển thị</label>
                <div class="vnkr-input-wrap">
                  <span class="vnkr-input-icon vnkr-input-icon--left"><i class="bi bi-person"></i></span>
                  <input type="text" name="name" class="vnkr-input" placeholder="Tên của bạn" required value="{{ old('name') }}" autocomplete="name">
                </div>
              </div>
            </div>
            <div class="mb-3">
              <div class="vnkr-field" data-size="medium">
                <label class="vnkr-label fw-600">Email</label>
                <div class="vnkr-input-wrap">
                  <span class="vnkr-input-icon vnkr-input-icon--left"><i class="bi bi-envelope"></i></span>
                  <input type="email" name="email" class="vnkr-input" placeholder="email@example.com" required value="{{ old('email') }}" autocomplete="email">
                </div>
              </div>
            </div>
            <div class="mb-3">
              <div class="vnkr-field" data-size="medium">
                <label class="vnkr-label fw-600">Mật khẩu</label>
                <div class="vnkr-input-wrap">
                  <span class="vnkr-input-icon vnkr-input-icon--left"><i class="bi bi-lock"></i></span>
                  <input type="password" name="password" class="vnkr-input" placeholder="Tối thiểu 8 ký tự" required autocomplete="new-password">
                </div>
              </div>
            </div>
            <div class="mb-3">
              <div class="vnkr-field" data-size="medium">
                <label class="vnkr-label fw-600">Xác nhận mật khẩu</label>
                <div class="vnkr-input-wrap">
                  <span class="vnkr-input-icon vnkr-input-icon--left"><i class="bi bi-lock-fill"></i></span>
                  <input type="password" name="password_confirmation" class="vnkr-input" placeholder="Nhập lại mật khẩu" required autocomplete="new-password">
                </div>
              </div>
            </div>
            <div class="mb-3 form-check">
              <input type="checkbox" class="form-check-input" id="agree" required>
              <label class="form-check-label" for="agree" style="font-size:13px;">
                Tôi đồng ý với <a href="/terms" class="brand-color">Điều khoản sử dụng</a>
                và <a href="/privacy" class="brand-color">Chính sách bảo mật</a> của VNKR.
              </label>
            </div>
            <button type="submit" class="vnkr-btn vnkr-btn--primary vnkr-btn--full">
              <i class="bi bi-person-check me-1"></i>Tạo Tài Khoản — Miễn Phí
            </button>
          </form>
          <hr class="my-3">
          <div class="text-center" style="font-size:14px;">
            Đã có tài khoản? <a href="{{ route('login') }}" class="brand-color fw-bold">Đăng nhập</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
