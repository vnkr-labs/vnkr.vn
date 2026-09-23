@extends('fe.index')
@section('title', 'Lịch Sử Đọc — VNKR')
@section('meta_description', 'Danh sách bài viết bạn đã đọc gần đây trên VNKR.')

@section('main')
<div class="container" style="padding-top:24px;padding-bottom:40px;">
  <div style="max-width:860px;margin:0 auto;">

    <div class="d-flex align-items-center justify-content-between mb-4">
      <h1 style="font-size:22px;font-weight:900;color:var(--brand);margin:0;">
        <i class="bi bi-clock-history me-2"></i>Lịch Sử Đọc
      </h1>
      <span class="text-muted" style="font-size:13px;">{{ $history->total() }} lượt đọc</span>
    </div>

    @if($history->isEmpty())
      <div class="text-center py-5 text-muted">
        <i class="bi bi-clock-history fs-1 d-block mb-3 text-muted"></i>
        <p style="font-size:15px;">Bạn chưa đọc bài nào kể từ khi đăng nhập.</p>
        <a href="{{ route('index') }}" class="vnkr-btn vnkr-btn--brand vnkr-btn--sm">
          <i class="bi bi-house me-1"></i>Về Trang Chủ
        </a>
      </div>
    @else
      <div style="display:flex;flex-direction:column;gap:10px;">
        @foreach($history as $h)
          @if($h->article)
          <div style="display:flex;gap:14px;padding:12px 14px;background:#fff;border:1px solid var(--border);border-radius:6px;align-items:center;">
            @if($h->article->image)
            <a href="{{ route('detail', $h->article->slug) }}" style="flex-shrink:0;">
              <img src="{{ asset('storage/images/'.$h->article->image) }}"
                   alt="{{ $h->article->name }}"
                   loading="lazy"
                   style="width:90px;height:60px;object-fit:cover;border-radius:4px;">
            </a>
            @endif
            <div style="flex:1;min-width:0;">
              @if($h->article->category)
              <a href="{{ $h->article->category->slug ? route('category.slug', $h->article->category->slug) : route('result', $h->article->category_id) }}" class="cat-badge mb-1 d-inline-block" style="font-size:11px;">{{ $h->article->category->name }}</a>
              @endif
              <h3 style="font-size:14px;font-weight:800;margin:0 0 3px;line-height:1.4;">
                <a href="{{ route('detail', $h->article->slug) }}" style="color:var(--text);">{{ $h->article->name }}</a>
              </h3>
              <div style="font-size:12px;color:#aaa;">
                <i class="bi bi-clock me-1"></i>Đọc lúc {{ $h->read_at->format('H:i, d/m/Y') }}
              </div>
            </div>
          </div>
          @endif
        @endforeach
      </div>

      <div class="mt-4">
        {{ $history->links() }}
      </div>
    @endif

  </div>
</div>
@endsection
