@extends('admin.master')
@section('title', 'Tạo Lộ Trình Mới')
@section('title-page', 'Tạo Lộ Trình (Journey) Mới')

@section('main-content')
<section class="container mt-4" style="max-width:900px;">
  <div class="card">
    <div class="card-header bg-primary text-white">
      <h5 class="mb-0"><i class="bi bi-map me-2"></i>Tạo Lộ Trình Mới</h5>
    </div>
    <form method="POST" action="{{ route('admin.journeys.store') }}">
      @csrf
      <div class="card-body">

        @if($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <div class="row g-3">
          <div class="col-md-8">
            <label class="form-label">Tiêu Đề <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required
                   id="journey-title" placeholder="Vd: Hành trình tìm hiểu Web3">
          </div>
          <div class="col-md-4">
            <label class="form-label">Slug <span class="text-danger">*</span></label>
            <input type="text" name="slug" id="journey-slug" class="form-control" value="{{ old('slug') }}" required
                   placeholder="hanh-trinh-web3">
          </div>

          <div class="col-12">
            <label class="form-label">Mô Tả</label>
            <textarea name="description" class="form-control" rows="2" placeholder="Giới thiệu ngắn về lộ trình...">{{ old('description') }}</textarea>
          </div>

          <div class="col-md-3">
            <label class="form-label">Icon Bootstrap</label>
            <input type="text" name="icon" class="form-control" value="{{ old('icon', 'bi-map') }}"
                   placeholder="bi-map">
            <div class="form-text">Ví dụ: bi-book, bi-lightning, bi-globe</div>
          </div>
          <div class="col-md-3">
            <label class="form-label">Màu sắc</label>
            <input type="color" name="color" class="form-control form-control-color" value="{{ old('color', '#3b82d4') }}">
          </div>
          <div class="col-md-3">
            <label class="form-label">Thời Gian Ước Tính (phút)</label>
            <input type="number" name="estimated_minutes" class="form-control" value="{{ old('estimated_minutes') }}"
                   min="1" max="600" placeholder="30">
          </div>
          <div class="col-md-3">
            <label class="form-label">Chuyên Mục</label>
            <select name="category_id" class="form-select">
              <option value="">— Không có —</option>
              @foreach($categories as $cat)
              <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-12">
            <div class="form-check">
              <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1"
                     {{ old('is_active', '1') ? 'checked' : '' }}>
              <label class="form-check-label" for="is_active">Bật lộ trình (hiển thị công khai)</label>
            </div>
          </div>

          <div class="col-12">
            <label class="form-label fw-bold">Chọn Bài Viết <small class="text-muted fw-normal">(giữ Ctrl/⌘ để chọn nhiều — thứ tự từ trên xuống)</small></label>
            <select name="article_ids[]" class="form-select" multiple style="height:220px;">
              @foreach($articles as $a)
              <option value="{{ $a->id }}" {{ in_array($a->id, old('article_ids', [])) ? 'selected' : '' }}>
                [{{ $a->id }}] {{ Str::limit($a->name, 80) }}
              </option>
              @endforeach
            </select>
          </div>
        </div>

      </div>
      <div class="card-footer d-flex gap-2">
        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Lưu Lộ Trình</button>
        <a href="{{ route('admin.journeys.index') }}" class="btn btn-secondary">Hủy</a>
      </div>
    </form>
  </div>
</section>
@endsection

@section('custom-js')
<script>
(function() {
  var titleEl = document.getElementById('journey-title');
  var slugEl  = document.getElementById('journey-slug');
  var edited  = false;
  slugEl.addEventListener('input', () => edited = true);
  titleEl.addEventListener('input', function() {
    if (!edited) slugEl.value = this.value.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9\-]/g, '').replace(/-+/g, '-');
  });
})();
</script>
@endsection
