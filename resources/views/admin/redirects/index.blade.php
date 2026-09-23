@extends('admin.master')
@section('title', 'Quản Lý Redirects')
@section('title-page', 'Database Redirects')

@section('main-content')
<section class="container mt-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h5 class="mb-0"><i class="bi bi-signpost-2 me-2" style="color:#7c5cd8;"></i>Database Redirects</h5>
      <small class="text-muted">Quản lý 301/302 redirect qua DB — ý tưởng từ github/docs Redirects</small>
    </div>
    <a href="{{ route('admin.redirects.create') }}" class="btn btn-primary btn-sm">
      <i class="bi bi-plus-lg me-1"></i>Thêm Redirect
    </a>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
      <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  {{-- Search --}}
  <form class="mb-3 d-flex gap-2" method="GET">
    <input type="text" name="search" class="form-control form-control-sm" style="max-width:320px;"
           value="{{ request('search') }}" placeholder="Tìm theo URL...">
    <button class="btn btn-sm btn-outline-secondary">Tìm</button>
    @if(request('search'))<a href="{{ route('admin.redirects.index') }}" class="btn btn-sm btn-outline-secondary">Xóa lọc</a>@endif
  </form>

  <div class="card">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Từ (From)</th>
            <th>Đến (To)</th>
            <th class="text-center">Loại</th>
            <th class="text-center">Lượt hit</th>
            <th class="text-center">Trạng Thái</th>
            <th class="text-center">Hành Động</th>
          </tr>
        </thead>
        <tbody>
          @forelse($redirects as $r)
          <tr>
            <td>{{ $r->id }}</td>
            <td><code style="font-size:12px;color:#7c5cd8;">{{ $r->from_path }}</code></td>
            <td><code style="font-size:12px;">{{ $r->to_path }}</code></td>
            <td class="text-center">
              <span class="badge {{ $r->status_code === 301 ? 'bg-success' : 'bg-info' }}">{{ $r->status_code }}</span>
            </td>
            <td class="text-center">
              <span class="badge bg-secondary">{{ number_format($r->hit_count) }}</span>
            </td>
            <td class="text-center">
              @if($r->is_active)
                <span class="badge bg-success">Bật</span>
              @else
                <span class="badge bg-secondary">Tắt</span>
              @endif
            </td>
            <td class="text-center d-flex gap-1 justify-content-center">
              <form method="POST" action="{{ route('admin.redirects.toggle', $r) }}" class="d-inline">
                @csrf @method('PATCH')
                <button class="btn btn-sm {{ $r->is_active ? 'btn-warning' : 'btn-success' }}" title="{{ $r->is_active ? 'Tắt' : 'Bật' }}">
                  <i class="bi {{ $r->is_active ? 'bi-pause' : 'bi-play' }}"></i>
                </button>
              </form>
              <form method="POST" action="{{ route('admin.redirects.destroy', $r) }}" class="d-inline"
                    onsubmit="return confirm('Xóa redirect này?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
              </form>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="text-center text-muted py-4">Chưa có redirect nào. <a href="{{ route('admin.redirects.create') }}">Thêm ngay</a></td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($redirects->hasPages())
    <div class="card-footer">{{ $redirects->links() }}</div>
    @endif
  </div>

</section>
@endsection
