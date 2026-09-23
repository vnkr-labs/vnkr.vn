@extends('admin.master')
@section('title', 'Chỉnh Sửa Reusable')
@section('title-page', 'Chỉnh Sửa: ' . $reusable->title)

@section('main-content')
<section class="container mt-4" style="max-width:800px;">
  <div class="card">
    <div class="card-header bg-primary text-white">
      <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Chỉnh Sửa Reusable</h5>
    </div>
    <form method="POST" action="{{ route('admin.reusables.update', $reusable) }}">
      @csrf @method('PUT')
      <div class="card-body">

        @if($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <div class="alert alert-info" style="font-size:13px;">
          <i class="bi bi-info-circle me-1"></i>Cú pháp nhúng trong bài viết:
          <code style="background:#e8f4fd;padding:2px 8px;border-radius:4px;">{{'{{'}}reusable:{{ $reusable->slug }}{{'}}'}}</code>
          <button type="button" class="btn btn-sm btn-outline-primary ms-2" style="font-size:11px;"
                  onclick="navigator.clipboard.writeText('{{'{{'}}reusable:{{ $reusable->slug }}{{'}}'}}')" >
            <i class="bi bi-clipboard me-1"></i>Sao chép
          </button>
        </div>

        <div class="row g-3">
          <div class="col-md-7">
            <label class="form-label">Tiêu Đề <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $reusable->title) }}" required>
          </div>
          <div class="col-md-5">
            <label class="form-label">Slug <span class="text-danger">*</span></label>
            <input type="text" name="slug" class="form-control" value="{{ old('slug', $reusable->slug) }}" required>
            <div class="form-text">⚠️ Đổi slug sẽ làm vỡ các bài viết đang dùng cú pháp cũ.</div>
          </div>

          <div class="col-md-3">
            <label class="form-label">Loại Nội Dung</label>
            <select name="type" class="form-select">
              <option value="html"     {{ old('type', $reusable->type) === 'html'     ? 'selected' : '' }}>HTML</option>
              <option value="markdown" {{ old('type', $reusable->type) === 'markdown' ? 'selected' : '' }}>Markdown</option>
              <option value="text"     {{ old('type', $reusable->type) === 'text'     ? 'selected' : '' }}>Văn bản thuần</option>
            </select>
          </div>
          <div class="col-md-9 d-flex align-items-end">
            <div class="form-check">
              <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1"
                     {{ old('is_active', $reusable->is_active) ? 'checked' : '' }}>
              <label class="form-check-label" for="is_active">Bật (nhúng bài viết sẽ hiển thị)</label>
            </div>
          </div>

          <div class="col-12">
            <label class="form-label">Nội Dung <span class="text-danger">*</span></label>
            <textarea name="content" class="form-control" rows="8" id="reusable-content">{{ old('content', $reusable->content) }}</textarea>
          </div>

          <div class="col-12" id="preview-wrapper" style="display:none;">
            <label class="form-label text-muted">Xem Trước</label>
            <div id="reusable-preview"
                 style="border:1px solid #ddd;border-radius:6px;padding:16px;min-height:60px;background:#fafbfc;font-size:14px;"></div>
          </div>
        </div>

      </div>
      <div class="card-footer d-flex gap-2">
        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Cập Nhật</button>
        <button type="button" class="btn btn-outline-secondary" id="preview-btn">
          <i class="bi bi-eye me-1"></i>Xem Trước
        </button>
        <a href="{{ route('admin.reusables.index') }}" class="btn btn-secondary">Hủy</a>
      </div>
    </form>
  </div>
</section>
@endsection

@section('custom-js')
<script>
(function() {
  var contentEl = document.getElementById('reusable-content');
  var previewEl = document.getElementById('reusable-preview');
  var previewWr = document.getElementById('preview-wrapper');
  document.getElementById('preview-btn').addEventListener('click', function() {
    previewEl.innerHTML = contentEl.value;
    previewWr.style.display = 'block';
  });
})();
</script>
@endsection
