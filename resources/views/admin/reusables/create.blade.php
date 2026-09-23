@extends('admin.master')
@section('title', 'Thêm Reusable Mới')
@section('title-page', 'Thêm Reusable Mới')

@section('main-content')
<section class="container mt-4" style="max-width:800px;">
  <div class="card">
    <div class="card-header bg-primary text-white">
      <h5 class="mb-0"><i class="bi bi-puzzle me-2"></i>Thêm Reusable Mới</h5>
    </div>
    <form method="POST" action="{{ route('admin.reusables.store') }}">
      @csrf
      <div class="card-body">

        @if($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <div class="row g-3">
          <div class="col-md-7">
            <label class="form-label">Tiêu Đề <span class="text-danger">*</span></label>
            <input type="text" name="title" id="reusable-title" class="form-control" value="{{ old('title') }}" required
                   placeholder="Vd: Thông báo bầu cử 2026">
          </div>
          <div class="col-md-5">
            <label class="form-label">Slug <span class="text-danger">*</span></label>
            <input type="text" name="slug" id="reusable-slug" class="form-control" value="{{ old('slug') }}" required
                   placeholder="thong-bao-bau-cu-2026">
            <div class="form-text">Chỉ dùng <code>a-z 0-9 -</code>. Cú pháp: <code>{{'{{'}}reusable:<strong>slug</strong>{{'}}'}}</code></div>
          </div>

          <div class="col-md-3">
            <label class="form-label">Loại Nội Dung</label>
            <select name="type" class="form-select">
              <option value="html"     {{ old('type', 'html') === 'html'     ? 'selected' : '' }}>HTML</option>
              <option value="markdown" {{ old('type') === 'markdown' ? 'selected' : '' }}>Markdown</option>
              <option value="text"     {{ old('type') === 'text'     ? 'selected' : '' }}>Văn bản thuần</option>
            </select>
          </div>
          <div class="col-md-9 d-flex align-items-end">
            <div class="form-check">
              <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1"
                     {{ old('is_active', '1') ? 'checked' : '' }}>
              <label class="form-check-label" for="is_active">Bật ngay (nhúng bài viết sẽ hiển thị)</label>
            </div>
          </div>

          <div class="col-12">
            <label class="form-label">Nội Dung <span class="text-danger">*</span></label>
            <textarea name="content" class="form-control" rows="8" id="reusable-content"
                      placeholder="Nhập nội dung HTML, Markdown hoặc văn bản...">{{ old('content') }}</textarea>
          </div>

          {{-- Preview --}}
          <div class="col-12" id="preview-wrapper" style="display:none;">
            <label class="form-label text-muted">Xem Trước (HTML)</label>
            <div id="reusable-preview"
                 style="border:1px solid #ddd;border-radius:6px;padding:16px;min-height:60px;background:#fafbfc;font-size:14px;"></div>
          </div>
        </div>

      </div>
      <div class="card-footer d-flex gap-2">
        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Lưu Reusable</button>
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
  var titleEl   = document.getElementById('reusable-title');
  var slugEl    = document.getElementById('reusable-slug');
  var contentEl = document.getElementById('reusable-content');
  var previewEl = document.getElementById('reusable-preview');
  var previewWr = document.getElementById('preview-wrapper');
  var previewBtn= document.getElementById('preview-btn');
  var edited    = false;

  slugEl.addEventListener('input', () => edited = true);
  titleEl.addEventListener('input', function() {
    if (!edited) slugEl.value = this.value.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9\-]/g, '').replace(/-+/g, '-');
  });

  previewBtn.addEventListener('click', function() {
    previewEl.innerHTML = contentEl.value;
    previewWr.style.display = 'block';
  });
})();
</script>
@endsection
