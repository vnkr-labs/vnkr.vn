@extends('fe.index')
@section('title', ($author->name ?? $author->username) . ' — Tác giả VNKR')
@section('meta_description', 'Bài viết của ' . $author->name . ' trên VNKR — ' . ($author->bio ? Str::limit($author->bio, 120) : 'Biên tập viên VNKR'))
@section('canonical', route('author.show', $author->username))
@section('og_type', 'profile')

@section('main')
<div class="container" style="padding-top:20px;">
  <div class="main-wrapper">

    {{-- ===== AUTHOR CARD ===== --}}
    <div>
      <div class="cat-block mb-4">
        <div class="d-flex align-items-center gap-4 flex-wrap p-2">
          {{-- Avatar --}}
          <img src="{{ $author->avatar_url }}" alt="{{ $author->name }}"
               loading="eager" decoding="async"
               style="width:90px;height:90px;border-radius:50%;object-fit:cover;border:3px solid var(--brand);flex-shrink:0;">

          {{-- Info --}}
          <div style="flex:1;">
            <h1 style="font-size:22px;font-weight:800;color:var(--brand);margin:0 0 4px;">
              {{ $author->name }}
              <i class="bi bi-patch-check-fill text-primary ms-1" style="font-size:16px;" title="Biên tập viên VNKR"></i>
            </h1>
            <div style="font-size:13px;color:var(--text-muted);margin-bottom:8px;">
              <i class="bi bi-pen me-1 brand-color"></i>Biên tập viên VNKR
              <span class="mx-2">·</span>
              <i class="bi bi-newspaper me-1 brand-color"></i>{{ $articles->total() }} bài viết
            </div>
            @if($author->bio)
            <p style="font-size:14px;color:#444;margin:0 0 8px;">{{ $author->bio }}</p>
            @endif
            <div class="d-flex gap-2 flex-wrap">
              @if($author->facebook_url)
              <a href="{{ $author->facebook_url }}" target="_blank" rel="noopener noreferrer"
                 class="btn-share fb" style="padding:4px 12px;font-size:12.5px;">
                <i class="bi bi-facebook me-1"></i>Facebook
              </a>
              @endif
              @if($author->twitter_url)
              <a href="{{ $author->twitter_url }}" target="_blank" rel="noopener noreferrer"
                 class="btn-share tw" style="padding:4px 12px;font-size:12.5px;">
                <i class="bi bi-twitter-x me-1"></i>Twitter/X
              </a>
              @endif
            </div>
          </div>
        </div>
      </div>

      {{-- ===== ARTICLES ===== --}}
      <div class="cat-block">
        <div class="cat-block-head">
          <h2><i class="bi bi-collection me-1"></i>BÀI VIẾT CỦA {{ strtoupper($author->name) }}</h2>
        </div>

        @forelse($articles as $item)
        <article class="news-list-item align-items-start" style="gap:14px;padding:14px 0;">
          <a href="{{ route('detail', $item->slug) }}" style="flex-shrink:0;">
            <img src="{{ $item->image ? asset('storage/images/'.$item->image) : asset('client/images/favicon.png') }}"
                 alt="{{ $item->name }}" loading="lazy" decoding="async"
                 style="width:130px;height:90px;object-fit:cover;border-radius:4px;">
          </a>
          <div class="info" style="flex:1;">
            @if($item->category)
              <span class="cat-badge mb-1">{{ $item->category->name }}</span>
            @endif
            <h3 style="font-size:15px;font-weight:700;line-height:1.4;margin:0 0 5px;">
              <a href="{{ route('detail', $item->slug) }}">{{ $item->name }}</a>
            </h3>
            <p class="text-muted mb-1" style="font-size:13px;">{{ Str::limit($item->tomtat, 110) }}</p>
            <div style="font-size:12px;color:#888;">
              <i class="bi bi-clock me-1"></i>{{ $item->created_at->format('d/m/Y') }}
              @if($item->view_count > 0)
                <span class="ms-2"><i class="bi bi-eye me-1"></i>{{ number_format($item->view_count) }}</span>
              @endif
              @foreach($item->tags->take(3) as $tag)
                <a href="{{ route('search') }}?s={{ urlencode($tag->name) }}"
                   class="tag-pill ms-1" style="font-size:11px;">{{ $tag->name }}</a>
              @endforeach
            </div>
          </div>
        </article>
        @empty
        <div class="text-center py-5 text-muted">
          <i class="bi bi-newspaper fs-1 d-block mb-2"></i>
          Tác giả chưa có bài viết nào.
        </div>
        @endforelse

        {{-- Pagination --}}
        @if($articles->hasPages())
        <div class="d-flex justify-content-center mt-3">
          {{ $articles->links() }}
        </div>
        @endif
      </div>
    </div>

    {{-- ===== SIDEBAR ===== --}}
    <aside class="sidebar">
      {{-- PTB box --}}
      <div class="ptb-section mb-4">
        <div class="d-flex align-items-center gap-2 mb-3">
          <img src="{{ $author->avatar_url }}" alt="{{ $author->name }}"
               style="width:48px;height:48px;border-radius:50%;object-fit:cover;border:2px solid var(--brand);">
          <div>
            <div style="font-size:14px;font-weight:800;color:var(--brand);">{{ $author->name }}</div>
            <div style="font-size:12px;color:var(--text-muted);">Biên tập viên VNKR</div>
          </div>
        </div>
        @if($author->bio)
        <p style="font-size:13px;color:#555;margin:0 0 10px;">{{ Str::limit($author->bio, 120) }}</p>
        @endif
        <div class="d-flex gap-2 flex-wrap">
          @if($author->facebook_url)
          <a href="{{ $author->facebook_url }}" target="_blank" class="vnkr-btn vnkr-btn--brand vnkr-btn--sm">
            <i class="bi bi-facebook me-1"></i>Facebook
          </a>
          @endif
          <a href="{{ route('contact.show') }}" class="vnkr-btn vnkr-btn--primary vnkr-btn--sm">
            <i class="bi bi-envelope me-1"></i>Liên hệ
          </a>
        </div>
      </div>

      {{-- Tin mới nhất --}}
      <div class="sidebar-widget">
        <div class="widget-title"><i class="bi bi-clock me-1"></i>Bài Viết Mới Nhất</div>
        @foreach($latestArticles as $i => $hot)
        <div class="most-read-item">
          <div class="rank">{{ $i + 1 }}</div>
          <h5><a href="{{ route('detail', $hot->slug) }}">{{ $hot->name }}</a></h5>
        </div>
        @endforeach
      </div>
    </aside>

  </div>
</div>
@endsection
