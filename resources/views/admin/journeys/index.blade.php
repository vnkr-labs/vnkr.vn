@extends('admin.master')
@section('title', 'Quản Lý Lộ Trình (Journeys)')
@section('title-page', 'Journeys — Lộ Trình Đọc')

@section('main-content')
<section class="container mt-4">

  {{-- Header --}}
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h5 class="mb-0"><i class="bi bi-map me-2" style="color:#3b82d4;"></i>Lộ Trình Đọc (Journeys)</h5>
      <small class="text-muted">Hướng dẫn đọc có cấu trúc — ý tưởng từ github/docs Journeys</small>
    </div>
    <a href="{{ route('admin.journeys.create') }}" class="btn btn-primary btn-sm">
      <i class="bi bi-plus-lg me-1"></i>Tạo Lộ Trình Mới
    </a>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <div class="card">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Tiêu Đề</th>
            <th>Slug</th>
            <th>Chuyên Mục</th>
            <th class="text-center">Bài Viết</th>
            <th class="text-center">Phút</th>
            <th class="text-center">Trạng Thái</th>
            <th class="text-center">Hành Động</th>
          </tr>
        </thead>
        <tbody>
          @forelse($journeys as $j)
          <tr>
            <td>{{ $j->id }}</td>
            <td>
              <div class="d-flex align-items-center gap-2">
                <span style="width:28px;height:28px;border-radius:50%;background:{{ $j->color ?? '#3b82d4' }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                  <i class="bi {{ $j->icon ?? 'bi-map' }}" style="color:#fff;font-size:13px;"></i>
                </span>
                <div>
                  <div class="fw-600">{{ $j->title }}</div>
                  @if($j->description)
                  <small class="text-muted">{{ Str::limit($j->description, 60) }}</small>
                  @endif
                </div>
              </div>
            </td>
            <td><code style="font-size:12px;">{{ $j->slug }}</code></td>
            <td>{{ $j->category?->name ?? '—' }}</td>
            <td class="text-center">
              <span class="badge bg-primary" style="font-size:12px;">{{ $j->articles_count }}</span>
            </td>
            <td class="text-center">{{ $j->estimated_minutes ?? '—' }}</td>
            <td class="text-center">
              @if($j->is_active)
                <span class="badge bg-success">Bật</span>
              @else
                <span class="badge bg-secondary">Tắt</span>
              @endif
            </td>
            <td class="text-center">
              <a href="{{ route('admin.journeys.edit', $j) }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-pencil"></i>
              </a>
              <a href="{{ route('journey.show', $j->slug) }}" target="_blank" class="btn btn-sm btn-outline-success">
                <i class="bi bi-eye"></i>
              </a>
              <form method="POST" action="{{ route('admin.journeys.destroy', $j) }}" class="d-inline"
                    onsubmit="return confirm('Xóa lộ trình này?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
              </form>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8" class="text-center text-muted py-4">Chưa có lộ trình nào. <a href="{{ route('admin.journeys.create') }}">Tạo ngay</a></td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($journeys->hasPages())
    <div class="card-footer">{{ $journeys->links() }}</div>
    @endif
  </div>

</section>
@endsection
