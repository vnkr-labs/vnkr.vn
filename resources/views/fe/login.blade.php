@extends('fe.index')
@section('title', 'Đăng Nhập — VNKR')
@section('main')
<div class="container" style="padding-top:40px;padding-bottom:60px;">
  <div class="row justify-content-center">
  <div class="col-lg-5 col-md-7">
    <div class="text-center mb-4">
      <div style="font-size:36px;font-weight:900;color:var(--brand);border-bottom:4px solid var(--accent);display:inline-block;padding:4px 16px;border-radius:4px;">VNKR</div>
      <p class="text-muted mt-2" style="font-size:14px;">Đăng nhập để tham gia cộng đồng TheKingBao</p>
      <div style="display:inline-flex;align-items:center;gap:6px;background:#f0f9ff;border:1px solid #c5d9ea;border-radius:20px;padding:4px 14px;font-size:12.5px;color:#0A3D62;margin-top:6px;">
        <i class="bi bi-people-fill" style="color:#F0A500;"></i>
        <strong>Cộng đồng phục vụ cộng đồng · Hoàn toàn miễn phí</strong>
      </div>
    </div>
      <div class="auth-card card">
        <div class="card-header"><h4 class="mb-0"><i class="bi bi-person-circle me-2"></i>Đăng Nhập</h4></div>
        <div class="card-body p-4">

          @if(session('success'))
          <div class="vnkr-callout vnkr-callout--success" style="font-size:13.5px;margin-bottom:16px;">
            <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
          </div>
          @endif
          @if($errors->any())
          <div class="vnkr-callout vnkr-callout--danger" style="font-size:13.5px;margin-bottom:16px;">
            <i class="bi bi-exclamation-triangle me-1"></i>{{ $errors->first() }}
          </div>
          @endif
          @if(Session::get('error'))
          <div class="vnkr-callout vnkr-callout--danger" style="font-size:13.5px;margin-bottom:16px;">
            {{ Session::get('error') }}
          </div>
          @endif

          <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="vnkr-field mb-3">
              <label class="vnkr-label" for="login-email">Email</label>
              <div class="vnkr-input-wrap">
                <svg class="vnkr-input-icon vnkr-input-icon--left" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                <input id="login-email" type="email" name="email" class="vnkr-input" placeholder="email@example.com" required value="{{ old('email') }}" autocomplete="email">
              </div>
            </div>

            <div class="vnkr-field mb-3">
              <label class="vnkr-label" for="login-password">Mật khẩu</label>
              <div class="vnkr-input-wrap">
                <svg class="vnkr-input-icon vnkr-input-icon--left" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                <input id="login-password" type="password" name="password" class="vnkr-input" placeholder="••••••••" required autocomplete="current-password">
              </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember" style="font-size:13.5px;">Ghi nhớ đăng nhập</label>
              </div>
              @if(Route::has('password.request'))
              <a href="{{ route('password.request') }}" style="font-size:13px;color:var(--brand);">Quên mật khẩu?</a>
              @endif
            </div>

            <button type="submit" class="vnkr-btn vnkr-btn--primary vnkr-btn--full" style="font-size:15px;">
              <i class="bi bi-box-arrow-in-right me-1"></i>Đăng Nhập
            </button>
          </form>

          <hr class="my-3">
          <div class="text-center" style="font-size:14px;">
            Chưa có tài khoản?
            <a href="{{ route('register') }}" style="color:var(--brand);font-weight:700;">Đăng ký miễn phí — tham gia ngay</a>
          </div>
          <div class="text-center mt-2" style="font-size:12.5px;color:#888;">
            <i class="bi bi-shield-check me-1" style="color:#27ae60;"></i>Không thu phí, không điều kiện ràng buộc.
            <a href="/about" style="color:var(--brand);">Xem quyền lợi</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
