@extends('admin.master')
@section('title', 'Live Blog')
@section('title-page', 'Quản Lý Live Blog')

@section('main-content')
<section class="container mt-4">

  @if(session('success'))
  <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
  @endif

  <div class="card">
    <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
      <h3 class="card-title mb-0">
        <i class="bi bi-broadcast me-1"></i>Bài Viết Đang LIVE
        <span class="badge bg-white text-danger ms-2">{{ $articles->count() }}</span>
      </h3>
      <a href="{{ route('product.index') }}" class="btn btn-sm btn-light">
        <i class="bi bi-list me-1"></i>Tất cả bài viết
      </a>
    </div>

    @if($articles->isEmpty())
    <div class="card-body text-center text-muted py-5">
      <i class="bi bi-broadcast fs-1 d-block mb-3"></i>
      <p>Chưa có bài viết nào đang ở chế độ Live.</p>
      <p style="font-size:13px;">Vào <a href="{{ route('product.index') }}">quản lý bài viết</a> → chọn bài → bật Live Blog.</p>
    </div>
    @else
    <div class="card-body p-0">
      <table class="table table-hover mb-0">
        <thead class="table-light">
          <tr>
            <th>Bài viết</th>
            <th style="width:100px;text-align:center;">Updates</th>
            <th style="width:160px;">Cập nhật lần cuối</th>
            <th style="width:120px;">Thao tác</th>
          </tr>
        </thead>
        <tbody>
          @foreach($articles as $art)
          <tr>
            <td>
              <span class="badge bg-danger me-1" style="font-size:10px;animation:blink-live 1s infinite;">LIVE</span>
              <a href="{{ route('detail', $art->slug) }}" target="_blank" style="font-size:13.5px;">
                {{ Str::limit($art->name, 70) }}
              </a>
            </td>
            <td style="text-align:center;font-weight:700;color:#0A3D62;">{{ $art->live_updates_count }}</td>
            <td style="font-size:12px;color:#888;">{{ $art->updated_at->diffForHumans() }}</td>
            <td>
              <a href="{{ route('live_blog.manage', $art) }}" class="btn btn-xs btn-primary">
                <i class="bi bi-pencil"></i> Quản lý
              </a>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @endif
  </div>

  <div class="alert alert-info mt-3" style="font-size:13px;">
    <i class="bi bi-info-circle me-1"></i>
    Để bật Live Blog cho một bài viết: vào <a href="{{ route('product.index') }}">Danh sách bài viết</a>
    → chọn bài → nhấn <strong>Bật Live</strong>.
    Người đọc sẽ thấy updates tự động cập nhật mỗi 30 giây.
  </div>
</section>
<style>@keyframes blink-live{0%,100%{opacity:1}50%{opacity:.4}}</style>
@endsection
