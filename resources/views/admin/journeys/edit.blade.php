@extends('admin.master')
@section('title', 'Chỉnh Sửa Lộ Trình')
@section('title-page', 'Chỉnh Sửa Journey: ' . $journey->title)

@section('main-content')
<section class="container mt-4" style="max-width:900px;">
  <div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between">
      <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Chỉnh Sửa Lộ Trình</h5>
      <a href="{{ route('journey.show', $journey->slug) }}" target="_blank" class="btn btn-sm btn-light">
        <i class="bi bi-eye me-1"></i>Xem trước
      </a>
    </div>
    <form method="POST" action="{{ route('admin.journeys.update', $journey) }}">
      @csrf @method('PUT')
      <div class="card-body">

        @if($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <div class="row g-3">
          <div class="col-md-8">
            <label class="form-label">Tiêu Đề <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $journey->title) }}" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Slug <span class="text-danger">*</span></label>
            <input type="text" name="slug" class="form-control" value="{{ old('slug', $journey->slug) }}" required>
          </div>

          <div class="col-12">
            <label class="form-label">Mô Tả</label>
            <textarea name="description" class="form-control" rows="2">{{ old('description', $journey->description) }}</textarea>
          </div>

          <div class="col-md-3">
            <label class="form-label">Icon Bootstrap</label>
            <input type="text" name="icon" class="form-control" value="{{ old('icon', $journey->icon) }}">
          </div>
          <div class="col-md-3">
            <label class="form-label">Màu sắc</label>
            <input type="color" name="color" class="form-control form-control-color" value="{{ old('color', $journey->color ?? '#3b82d4') }}">
          </div>
          <div class="col-md-3">
            <label class="form-label">Thời Gian Ước Tính (phút)</label>
            <input type="number" name="estimated_minutes" class="form-control" value="{{ old('estimated_minutes', $journey->estimated_minutes) }}" min="1" max="600">
          </div>
          <div class="col-md-3">
            <label class="form-label">Chuyên Mục</label>
            <select name="category_id" class="form-select">
              <option value="">— Không có —</option>
              @foreach($categories as $cat)
              <option value="{{ $cat->id }}" {{ old('category_id', $journey->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-12">
            <div class="form-check">
              <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1"
                     {{ old('is_active', $journey->is_active) ? 'checked' : '' }}>
              <label class="form-check-label" for="is_active">Bật lộ trình (hiển thị công khai)</label>
            </div>
          </div>

          <div class="col-12">
            <label class="form-label fw-bold">Bài Viết Trong Lộ Trình <small class="text-muted fw-normal">(giữ Ctrl/⌘ để chọn nhiều)</small></label>
            <select name="article_ids[]" class="form-select" multiple style="height:220px;">
              @foreach($articles as $a)
              <option value="{{ $a->id }}" {{ in_array($a->id, old('article_ids', $selectedIds)) ? 'selected' : '' }}>
                [{{ $a->id }}] {{ Str::limit($a->name, 80) }}
              </option>
              @endforeach
            </select>
            <div class="form-text">Thứ tự lộ trình = thứ tự chọn từ trên xuống.</div>
          </div>
        </div>

      </div>
      <div class="card-footer d-flex gap-2">
        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Cập Nhật</button>
        <a href="{{ route('admin.journeys.index') }}" class="btn btn-secondary">Hủy</a>
      </div>
    </form>
  </div>
</section>
@endsection
