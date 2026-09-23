@extends('admin.master')
@section('title', 'Thêm Redirect Mới')
@section('title-page', 'Thêm Redirect Mới')

@section('main-content')
<section class="container mt-4" style="max-width:700px;">
  <div class="card">
    <div class="card-header bg-primary text-white">
      <h5 class="mb-0"><i class="bi bi-signpost-2 me-2"></i>Thêm Redirect Mới</h5>
    </div>
    <form method="POST" action="{{ route('admin.redirects.store') }}">
      @csrf
      <div class="card-body">

        @if($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <div class="mb-3">
          <label class="form-label">URL Nguồn (From) <span class="text-danger">*</span></label>
          <div class="input-group">
            <span class="input-group-text text-muted" style="font-size:13px;">vnkr.vn</span>
            <input type="text" name="from_path" class="form-control" value="{{ old('from_path') }}"
                   placeholder="/tin-tuc/bai-viet-cu" required>
          </div>
          <div class="form-text">Đường dẫn cũ cần redirect đi. Bắt đầu bằng <code>/</code></div>
        </div>

        <div class="mb-3">
          <label class="form-label">URL Đích (To) <span class="text-danger">*</span></label>
          <input type="text" name="to_path" class="form-control" value="{{ old('to_path') }}"
                 placeholder="/detail/bai-viet-moi hoặc https://..." required>
          <div class="form-text">Đường dẫn mới hoặc URL đầy đủ bên ngoài.</div>
        </div>

        <div class="mb-3">
          <label class="form-label">Loại Redirect <span class="text-danger">*</span></label>
          <select name="status_code" class="form-select">
            <option value="301" {{ old('status_code', '301') == '301' ? 'selected' : '' }}>301 — Permanent (SEO-safe)</option>
            <option value="302" {{ old('status_code') == '302' ? 'selected' : '' }}>302 — Temporary</option>
          </select>
          <div class="form-text">Dùng <strong>301</strong> cho URL đã đổi vĩnh viễn — Google sẽ chuyển link equity.</div>
        </div>

        <div class="form-check">
          <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1"
                 {{ old('is_active', '1') ? 'checked' : '' }}>
          <label class="form-check-label" for="is_active">Bật ngay (có hiệu lực ngay sau khi lưu)</label>
        </div>

      </div>
      <div class="card-footer d-flex gap-2">
        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Lưu Redirect</button>
        <a href="{{ route('admin.redirects.index') }}" class="btn btn-secondary">Hủy</a>
      </div>
    </form>
  </div>
</section>
@endsection
