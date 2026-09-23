@extends('fe.index')

@section('title', 'Quên Mật Khẩu')

@section('main')
<div class="container" style="padding-top:40px;padding-bottom:60px;">
  <div class="row justify-content-center">
    <div class="col-lg-5 col-md-7">
      <div class="auth-card card">
        <div class="card-header">
          <h4 class="mb-0"><i class="bi bi-lock me-2"></i>Quên Mật Khẩu</h4>
        </div>
        <div class="card-body p-4">
          @if(session('status'))
            <div class="vnkr-callout vnkr-callout--success">
              <i class="bi bi-check-circle me-1"></i>{{ session('status') }}
            </div>
          @endif
          @if($errors->any())
            <div class="vnkr-callout vnkr-callout--danger">
              @foreach($errors->all() as $error)
                <div><i class="bi bi-x-circle me-1"></i>{{ $error }}</div>
              @endforeach
            </div>
          @endif
          <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-3">
              <div class="vnkr-field" data-size="medium">
                <label class="vnkr-label" for="email">Nhập Email</label>
                <div class="vnkr-input-wrap">
                  <span class="vnkr-input-icon vnkr-input-icon--left"><i class="bi bi-envelope"></i></span>
                  <input type="email" class="vnkr-input" id="email" name="email" placeholder="example@domain.com" required autocomplete="email">
                </div>
              </div>
            </div>
            <button type="submit" class="vnkr-btn vnkr-btn--primary vnkr-btn--full">
              <i class="bi bi-send me-1"></i>Gửi Liên Kết Đặt Mật Khẩu
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
