@extends('admin.master')
@section('title', 'Sửa Thông Báo — ' . $adSlot->label)
@section('title-page', 'Sửa Vị Trí Thông Báo Cộng Đồng')

@section('main-content')
<section class="container mt-4">
  <div class="card" style="max-width:760px;">
    <div class="card-header bg-primary text-white">
      <h3 class="card-title mb-0"><i class="bi bi-megaphone me-1"></i>{{ $adSlot->label }}</h3>
    </div>
    <form method="POST" action="{{ route('ad_slots.update', $adSlot) }}">
      @csrf @method('PUT')
      <div class="card-body">

        @if($errors->any())
        <div class="alert alert-danger" style="font-size:13px;">{{ $errors->first() }}</div>
        @endif

        <div class="mb-3">
          <label class="form-label fw-bold">Vị trí (không thay đổi)</label>
          <input type="text" class="form-control" value="{{ $adSlot->position }}" disabled>
        </div>

        <div class="mb-3">
          <label for="label" class="form-label fw-bold">Tên hiển thị</label>
          <input type="text" name="label" id="label" class="form-control"
                 value="{{ old('label', $adSlot->label) }}" required>
        </div>

        <div class="mb-3">
          <label for="code" class="form-label fw-bold">
            Nội Dung Thông Báo
            <small class="text-muted">(HTML — để trống để tắt thông báo)</small>
          </label>
          <textarea name="code" id="code" class="form-control"
                    rows="10"
                    placeholder="<!-- Dán banner HTML thông báo cộng đồng, sự kiện, lời kêu gọi đóng góp... -->"
                    style="font-family:monospace;font-size:12.5px;">{{ old('code', $adSlot->code) }}</textarea>
          <div class="form-text">HTML sẽ được render vào vị trí này trên trang. Chỉ dùng cho nội dung cộng đồng phi thương mại.</div>
        </div>

        <div class="mb-3">
          <div class="form-check">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" class="form-check-input" name="is_active" id="is_active" value="1"
                   {{ old('is_active', $adSlot->is_active) ? 'checked' : '' }}>
            <label class="form-check-label fw-bold" for="is_active">
              Bật vị trí thông báo này
            </label>
          </div>
          <div class="form-text">Chỉ hiển thị khi bật và có nội dung HTML.</div>
        </div>

      </div>
      <div class="card-footer d-flex gap-2">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Lưu</button>
        <a href="{{ route('ad_slots.index') }}" class="btn btn-secondary">Hủy</a>
      </div>
    </form>
  </div>
</section>
@endsection
