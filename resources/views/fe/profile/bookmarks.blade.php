@extends('fe.index')
@section('title', 'Bài Viết Đã Lưu — VNKR')
@section('meta_description', 'Danh sách bài viết bạn đã lưu trên VNKR.')

@section('main')
<div class="container" style="padding-top:24px;padding-bottom:40px;">
  <div style="max-width:860px;margin:0 auto;">

    <div class="d-flex align-items-center justify-content-between mb-4">
      <h1 style="font-size:22px;font-weight:900;color:var(--brand);margin:0;">
        <i class="bi bi-bookmark-fill me-2"></i>Bài Viết Đã Lưu
      </h1>
      <span class="text-muted" style="font-size:13px;">{{ $bookmarks->total() }} bài</span>
    </div>

    @if($bookmarks->isEmpty())
      <div class="text-center py-5 text-muted">
        <i class="bi bi-bookmark fs-1 d-block mb-3 text-muted"></i>
        <p style="font-size:15px;">Bạn chưa lưu bài viết nào.</p>
        <a href="{{ route('index') }}" class="vnkr-btn vnkr-btn--brand vnkr-btn--sm">
          <i class="bi bi-house me-1"></i>Về Trang Chủ
        </a>
      </div>
    @else
      <div style="display:flex;flex-direction:column;gap:12px;">
        @foreach($bookmarks as $bm)
          @if($bm->article)
          <div style="display:flex;gap:14px;padding:14px;background:#fff;border:1px solid var(--border);border-radius:6px;align-items:flex-start;">
            @if($bm->article->image)
            <a href="{{ route('detail', $bm->article->slug) }}" style="flex-shrink:0;">
              <img src="{{ asset('storage/images/'.$bm->article->image) }}"
                   alt="{{ $bm->article->name }}"
                   loading="lazy"
                   style="width:110px;height:75px;object-fit:cover;border-radius:4px;">
            </a>
            @endif
            <div style="flex:1;min-width:0;">
              @if($bm->article->category)
              <a href="{{ $bm->article->category->slug ? route('category.slug', $bm->article->category->slug) : route('result', $bm->article->category_id) }}" class="cat-badge mb-1 d-inline-block" style="font-size:11px;">{{ $bm->article->category->name }}</a>
              @endif
              <h3 style="font-size:15px;font-weight:800;margin:0 0 4px;line-height:1.4;">
                <a href="{{ route('detail', $bm->article->slug) }}" style="color:var(--text);">{{ $bm->article->name }}</a>
              </h3>
              <p style="font-size:13px;color:var(--text-muted);margin:0 0 6px;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">
                {{ Str::limit($bm->article->tomtat, 120) }}
              </p>
              <div style="font-size:12px;color:#aaa;">
                <i class="bi bi-clock me-1"></i>Lưu lúc {{ $bm->created_at->format('H:i, d/m/Y') }}
                &nbsp;·&nbsp;
                <i class="bi bi-eye me-1"></i>{{ number_format($bm->article->view_count) }} lượt xem
              </div>
            </div>
            {{-- Remove bookmark --}}
            <form method="POST" action="{{ route('bookmark.toggle', $bm->article_id) }}" style="flex-shrink:0;">
              @csrf
              <button type="submit" title="Xóa khỏi danh sách" class="vnkr-input-action" style="color:#c0392b;font-size:18px;">
                <i class="bi bi-bookmark-x"></i>
              </button>
            </form>
          </div>
          @endif
        @endforeach
      </div>

      <div class="mt-4">
        {{ $bookmarks->links() }}
      </div>
    @endif

  </div>
</div>
@endsection
