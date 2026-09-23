@extends('admin.master')
@section('title', 'Quản Lý Reusables')
@section('title-page', 'Reusables — Nội Dung Tái Sử Dụng')

@section('main-content')
<section class="container mt-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h5 class="mb-0"><i class="bi bi-puzzle me-2" style="color:#27ae60;"></i>Reusables — Snippet Tái Sử Dụng</h5>
      <small class="text-muted">Nhúng vào bài viết bằng cú pháp <code>{{'{{'}}reusable:slug{{'}}'}}</code> — ý tưởng từ github/docs Reusables</small>
    </div>
    <a href="{{ route('admin.reusables.create') }}" class="btn btn-primary btn-sm">
      <i class="bi bi-plus-lg me-1"></i>Thêm Reusable
    </a>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
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
            <th>Cú Pháp Nhúng</th>
            <th class="text-center">Loại</th>
            <th class="text-center">Trạng Thái</th>
            <th>Cập Nhật</th>
            <th class="text-center">Hành Động</th>
          </tr>
        </thead>
        <tbody>
          @forelse($reusables as $r)
          <tr>
            <td>{{ $r->id }}</td>
            <td>
              <div class="fw-600">{{ $r->title }}</div>
              <small class="text-muted">{{ Str::limit(strip_tags($r->content), 60) }}</small>
            </td>
            <td>
              <code style="background:#f3f0ff;padding:2px 8px;border-radius:4px;font-size:12px;color:#7c5cd8;cursor:pointer;"
                    onclick="navigator.clipboard.writeText('{{'{{'}}reusable:{{ $r->slug }}{{'}}'}}')" title="Click để sao chép">
                {{'{{'}}reusable:{{ $r->slug }}{{'}}'}}
              </code>
            </td>
            <td class="text-center">
              <span class="badge {{ $r->type === 'html' ? 'bg-danger' : ($r->type === 'markdown' ? 'bg-info' : 'bg-secondary') }}">
                {{ strtoupper($r->type) }}
              </span>
            </td>
            <td class="text-center">
              @if($r->is_active)
                <span class="badge bg-success">Bật</span>
              @else
                <span class="badge bg-secondary">Tắt</span>
              @endif
            </td>
            <td><small class="text-muted">{{ $r->updated_at->format('d/m/Y H:i') }}</small></td>
            <td class="text-center d-flex gap-1 justify-content-center">
              <a href="{{ route('admin.reusables.edit', $r) }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-pencil"></i>
              </a>
              <form method="POST" action="{{ route('admin.reusables.destroy', $r) }}" class="d-inline"
                    onsubmit="return confirm('Xóa reusable này? Bài viết đang dùng sẽ hiển thị chuỗi rỗng.')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
              </form>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="text-center text-muted py-4">Chưa có reusable nào. <a href="{{ route('admin.reusables.create') }}">Tạo ngay</a></td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($reusables->hasPages())
    <div class="card-footer">{{ $reusables->links() }}</div>
    @endif
  </div>

</section>
@endsection
