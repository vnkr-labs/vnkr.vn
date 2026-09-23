@extends('fe.index')
@section('title', 'Tìm kiếm: ' . request('s') . ' — VNKR')

@section('main')
@php $query = request('s'); @endphp

<div class="container" style="padding-top:20px;">
  <div class="main-wrapper">
    <div>
      {{-- Search heading --}}
      <div class="cat-block mb-3">
        <div class="d-flex align-items-center gap-2 flex-wrap">
          <i class="bi bi-search brand-color fs-5"></i>
          <h1 style="font-size:20px;font-weight:700;margin:0;">
            Kết quả tìm kiếm: "<span class="brand-color">{{ $query }}</span>"
          </h1>
          <span class="text-muted" style="font-size:13px;">
            — {{ $total ?? $products->count() }} bài viết
          </span>
        </div>
      </div>

      @if($products->isEmpty())
      {{-- Empty state --}}
      <div class="cat-block text-center py-5">
        <i class="bi bi-search fs-1 text-muted d-block mb-3"></i>
        <h3 style="font-size:18px;color:#555;">Không tìm thấy kết quả cho "<strong>{{ $query }}</strong>"</h3>
        <p class="text-muted" style="font-size:14px;">Hãy thử từ khóa khác hoặc kiểm tra lại chính tả.</p>
        <a href="{{ route('index') }}" class="vnkr-btn vnkr-btn--brand vnkr-btn--sm mt-2">
          <i class="bi bi-house me-1"></i>Về trang chủ
        </a>
      </div>
      @else
      {{-- Results list --}}
      <div class="cat-block">
        @foreach($products as $item)
        <article class="news-list-item align-items-start" style="gap:14px;padding:14px 0;">
          <a href="{{ route('detail', $item->slug) }}" style="flex-shrink:0;">
            <img src="{{ asset('storage/images/'.$item->image) }}"
                 alt="{{ $item->name }}" loading="lazy" decoding="async"
                 style="width:130px;height:90px;object-fit:cover;border-radius:4px;">
          </a>
          <div class="info" style="flex:1;">
            @if($item->category)
              <span class="cat-badge mb-1">{{ $item->category->name }}</span>
            @endif
            <h3 style="font-size:16px;font-weight:700;line-height:1.4;margin:0 0 5px;">
              <a href="{{ route('detail', $item->slug) }}">
                {{-- Highlight matched keyword --}}
                {!! preg_replace('/(' . preg_quote($query, '/') . ')/iu',
                    '<mark style="background:#fff3cd;color:var(--brand);font-weight:700;">$1</mark>',
                    e($item->name)) !!}
              </a>
            </h3>
            <p class="text-muted mb-1" style="font-size:13px;">
              {!! preg_replace('/(' . preg_quote($query, '/') . ')/iu',
                  '<mark style="background:#fff3cd;color:var(--brand);font-weight:600;">$1</mark>',
                  e(Str::limit($item->tomtat, 120))) !!}
            </p>
            <div style="font-size:12px;color:#888;">
              <i class="bi bi-clock me-1"></i>
              {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}
            </div>
          </div>
        </article>
        @endforeach
      </div>

      {{-- Pagination --}}
      @if($products instanceof \Illuminate\Pagination\LengthAwarePaginator && $products->hasPages())
      <div class="d-flex justify-content-center mt-3">
        {{ $products->links() }}
      </div>
      @endif

      @endif

      {{-- Search tips --}}
      <div class="cat-block mt-3" style="background:#fafafa;">
        <div class="cat-block-head"><h3>GỢI Ý TÌM KIẾM</h3></div>
        <div class="d-flex flex-wrap gap-2">
          @php
          $suggestions = \App\Models\Category::where('status',1)->limit(8)->get();
          @endphp
          @foreach($suggestions as $sg)
          <a href="{{ route('search') }}?s={{ urlencode($sg->name) }}" class="tag-pill">
            <i class="bi bi-search me-1"></i>{{ $sg->name }}
          </a>
          @endforeach
        </div>
      </div>
    </div>

    {{-- SIDEBAR --}}
    <aside class="sidebar">
      <div class="sidebar-widget">
        <div class="widget-title"><i class="bi bi-fire me-1"></i>Tin Nổi Bật</div>
        @foreach(\App\Models\Product::orderBy('created_at','desc')->limit(5)->get() as $i => $hot)
        <div class="most-read-item">
          <div class="rank">{{ $i+1 }}</div>
          <h5><a href="{{ route('detail', $hot->slug) }}">{{ $hot->name }}</a></h5>
        </div>
        @endforeach
      </div>

      <div class="sidebar-widget">
        <div class="widget-title"><i class="bi bi-grid me-1"></i>Chuyên Mục</div>
        <ul class="list-unstyled mb-0">
          @foreach(\App\Models\Category::where('status',1)->get() as $ac)
          <li class="border-bottom py-2">
            <a href="{{ $ac->slug ? route('category.slug', $ac->slug) : route('result', $ac->id) }}" style="font-size:13.5px;font-weight:500;">
              <i class="bi bi-chevron-right me-1 brand-color"></i>{{ $ac->name }}
            </a>
          </li>
          @endforeach
        </ul>
      </div>
    </aside>
  </div>
</div>
@endsection
