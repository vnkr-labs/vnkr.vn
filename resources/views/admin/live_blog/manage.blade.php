@extends('admin.master')
@section('title', 'Live Blog — ' . Str::limit($article->name, 40))
@section('title-page', 'Live Blog: ' . Str::limit($article->name, 50))

@section('main-content')
<section class="container mt-4">

  @if(session('success'))
  <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
  @endif

  <div class="row g-3">

    {{-- LEFT: Compose --}}
    <div class="col-lg-5">
      <div class="card sticky-top" style="top:70px;">
        <div class="card-header d-flex justify-content-between align-items-center
          {{ $article->is_live ? 'bg-danger text-white' : 'bg-secondary text-white' }}">
          <h5 class="mb-0">
            @if($article->is_live)
              <i class="bi bi-broadcast me-1"></i><span style="animation:blink-live 1s infinite;display:inline-block;">LIVE</span>
            @else
              <i class="bi bi-broadcast-pin me-1"></i>Chế độ Live (Đang tắt)
            @endif
          </h5>
          <form method="POST" action="{{ route('live_blog.toggle', $article) }}">
            @csrf
            <button type="submit" class="btn btn-sm {{ $article->is_live ? 'btn-light text-danger' : 'btn-success' }}">
              {{ $article->is_live ? 'Tắt Live' : 'Bật Live' }}
            </button>
          </form>
        </div>
        <div class="card-body">
          <div class="mb-2" style="font-size:13px;color:#555;">
            <a href="{{ route('detail', $article->slug) }}" target="_blank" class="text-decoration-none">
              <i class="bi bi-box-arrow-up-right me-1"></i>Xem bài viết →
            </a>
          </div>
          <form method="POST" action="{{ route('live_blog.store', $article) }}">
            @csrf
            <div class="mb-3">
              <label class="form-label fw-bold">Nội dung Update Mới</label>
              <textarea name="content" class="form-control" rows="5" required
                placeholder="Nhập thông tin cập nhật mới nhất về sự kiện..."
                style="font-size:14px;resize:vertical;">{{ old('content') }}</textarea>
              @error('content')<div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div>@enderror
            </div>
            <div class="form-check mb-3">
              <input type="checkbox" class="form-check-input" name="is_pinned" id="is_pinned" value="1">
              <label class="form-check-label" for="is_pinned">
                <i class="bi bi-pin me-1"></i>Ghim update này lên đầu
              </label>
            </div>
            <button type="submit" class="btn btn-danger w-100" {{ !$article->is_live ? 'disabled' : '' }}>
              <i class="bi bi-send me-1"></i>Đăng Update
              @if(!$article->is_live)
                <small>(Bật Live trước)</small>
              @endif
            </button>
          </form>
        </div>
      </div>
    </div>

    {{-- RIGHT: Updates feed --}}
    <div class="col-lg-7">
      <div class="card">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
          <h5 class="mb-0"><i class="bi bi-chat-left-dots me-1"></i>Updates ({{ $updates->count() }})</h5>
          <small class="text-muted">Mới nhất ở trên</small>
        </div>
        <div class="card-body p-0" id="updates-feed">
          @forelse($updates as $u)
          <div class="update-item {{ $u->is_pinned ? 'pinned' : '' }}" id="update-{{ $u->id }}">
            <div class="d-flex justify-content-between align-items-start">
              <div style="flex:1;">
                @if($u->is_pinned)
                <span class="badge bg-warning text-dark mb-1"><i class="bi bi-pin me-1"></i>Ghim</span>
                @endif
                <p style="margin:0;font-size:14px;line-height:1.65;">{{ $u->content }}</p>
                <div style="font-size:12px;color:#888;margin-top:6px;">
                  <i class="bi bi-clock me-1"></i>{{ $u->posted_at->format('H:i:s, d/m/Y') }}
                  &nbsp;·&nbsp;
                  <span>{{ $u->admin->name ?? 'Admin' }}</span>
                </div>
              </div>
              <form method="POST" action="{{ route('live_blog.destroy', [$article, $u]) }}" class="ms-2"
                    onsubmit="return confirm('Xóa update này?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-xs btn-outline-danger"><i class="bi bi-trash3"></i></button>
              </form>
            </div>
          </div>
          @empty
          <div class="text-center py-4 text-muted" id="empty-msg">Chưa có update nào. Đăng update đầu tiên ở bên trái.</div>
          @endforelse
        </div>
      </div>
    </div>

  </div>
</section>

<style>
@keyframes blink-live{0%,100%{opacity:1}50%{opacity:.4}}
.update-item{padding:14px 16px;border-bottom:1px solid #f0f0f0;}
.update-item.pinned{background:#fffbe6;border-left:4px solid #f59e0b;}
.update-item.new-flash{animation:flash-new .8s ease-out;}
@keyframes flash-new{0%{background:#e0f2fe}100%{background:transparent}}
</style>
@endsection
