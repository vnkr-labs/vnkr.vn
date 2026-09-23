@extends('fe.index')
@section('title', 'Liên Hệ Biên Tập Viên — VNKR')
@section('main')
<div class="container" style="padding-top:24px;max-width:900px;">
  <div class="row g-4">

    {{-- Info col --}}
    <div class="col-lg-4">
      <div class="cat-block" style="border-top-color:var(--accent);">
        <div class="d-flex align-items-center gap-2 mb-3">
          <div class="ptb-avatar">P</div>
          <div>
            <div style="font-weight:800;font-size:16px;color:var(--brand);">Phạm Thế Bảo</div>
            <div style="font-size:12.5px;color:var(--text-muted);">Biên tập viên VNKR</div>
          </div>
        </div>
        <ul class="list-unstyled" style="font-size:14px;line-height:2.2;color:#444;">
          <li><i class="bi bi-globe me-2 brand-color"></i><a href="https://vnkr.vn">vnkr.vn</a></li>
          <li><i class="bi bi-envelope me-2 brand-color"></i>phamthebao@vnkr.vn</li>
          <li><i class="bi bi-facebook me-2 brand-color"></i><a href="https://facebook.com/thekingbao" target="_blank">TheKingBao</a></li>
          <li><i class="bi bi-people-fill me-2 brand-color"></i>Cộng đồng TheKingBao</li>
        </ul>
        <div class="vnkr-callout vnkr-callout--info mt-3">
          <i class="bi bi-info-circle me-1"></i>
          VNKR là trang thông tin tổng hợp cá nhân, không phải cơ quan báo chí.
          Phản hồi sẽ được trả lời trong 24-48 giờ.
        </div>
      </div>
    </div>

    {{-- Form col --}}
    <div class="col-lg-8">
      <div class="cat-block">
        <div class="cat-block-head"><h3><i class="bi bi-send me-1"></i>GỬI PHẢN HỒI</h3></div>
        @if(session('success'))
          <div class="vnkr-callout vnkr-callout--success">
            <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
          </div>
        @endif
        @guest
          <div class="vnkr-callout vnkr-callout--info">
            <i class="bi bi-info-circle me-1"></i>
            Bạn cần <a href="{{ route('login') }}" class="brand-color fw-bold">đăng nhập</a> để gửi phản hồi.
          </div>
        @endguest
        <form action="{{ route('contact.submit') }}" method="POST">
          @csrf
          @if($errors->any())
            <div class="vnkr-callout vnkr-callout--danger">{{ $errors->first() }}</div>
          @endif
          <div class="row g-3">
            <div class="col-md-6">
              <div class="vnkr-field" data-size="medium">
                <label class="vnkr-label fw-semibold">Họ Tên <span class="text-danger">*</span></label>
                <div class="vnkr-input-wrap">
                  <span class="vnkr-input-icon vnkr-input-icon--left"><i class="bi bi-person"></i></span>
                  <input type="text" name="name" class="vnkr-input" placeholder="Tên của bạn" required value="{{ Auth::user()->name ?? old('name') }}" autocomplete="name">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="vnkr-field" data-size="medium">
                <label class="vnkr-label fw-semibold">Email <span class="text-danger">*</span></label>
                <div class="vnkr-input-wrap">
                  <span class="vnkr-input-icon vnkr-input-icon--left"><i class="bi bi-envelope"></i></span>
                  <input type="email" name="email" class="vnkr-input" placeholder="email@example.com" required value="{{ Auth::user()->email ?? old('email') }}" autocomplete="email">
                </div>
              </div>
            </div>
            <div class="col-12">
              <div class="vnkr-field vnkr-field--textarea" data-size="medium">
                <label class="vnkr-label fw-semibold">Nội Dung <span class="text-danger">*</span></label>
                <div class="vnkr-input-wrap">
                  <textarea name="message" class="vnkr-input" rows="6" required placeholder="Góp ý về nội dung, báo lỗi, đề xuất hợp tác, hoặc khiếu nại bản quyền..."></textarea>
                </div>
              </div>
            </div>
            <div class="col-12">
              <div style="font-size:12.5px;color:#888;margin-bottom:10px;">
                <i class="bi bi-shield-check me-1"></i>Thông tin của bạn được bảo mật theo <a href="/privacy" class="brand-color">Chính sách bảo mật</a> của VNKR.
              </div>
              <button type="submit" class="vnkr-btn vnkr-btn--brand">
                <i class="bi bi-send me-1"></i>Gửi Phản Hồi
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
