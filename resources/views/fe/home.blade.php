@extends('fe.index')
@section('title', 'VNKR — Tin Tức Được Biên Tập Bởi Phạm Thế Bảo')
@section('meta_description', 'VNKR — Tin tức được biên tập trung thực bởi Phạm Thế Bảo, dành cho cộng đồng TheKingBao. Đọc ít hơn, hiểu nhiều hơn.')
@section('canonical', url('/'))

@section('main')

{{-- ===== BREAKING NEWS TICKER ===== --}}
<div class="breaking-bar">
  <div class="container d-flex align-items-center" style="overflow:hidden;">
    <span class="breaking-label"><i class="bi bi-lightning-fill me-1"></i>NÓNG</span>
    <div class="ticker-wrap">
      <div class="ticker-content">
        @foreach($breakingNews as $b)
          <a href="{{ route('detail', $b->slug) }}">{{ $b->name }}</a>
        @endforeach
      </div>
    </div>
  </div>
</div>

{{-- ===== MAIN LAYOUT ===== --}}
<div class="container">
  <div class="main-wrapper">

    {{-- =================== LEFT CONTENT =================== --}}
    <div>

      {{-- VEDETTE SECTION --}}
      @if($featuredProduct->count() >= 1)
      @php $first = $featuredProduct[0]; $subs = $featuredProduct->slice(1,3); @endphp
      <div class="vedette mb-4">
        {{-- Main article --}}
        <div class="vedette-main">
          <a href="{{ route('detail', $first->slug) }}">
            <img src="{{ asset('storage/images/'.$first->image) }}"
                 alt="{{ $first->name }}" loading="eager" decoding="async"
                 style="width:100%;height:340px;object-fit:cover;">
          </a>
          <div class="vedette-overlay">
            @if($first->category)
              <span class="cat-badge mb-1 d-inline-block">{{ $first->category->name }}</span>
            @endif
            <h2><a href="{{ route('detail', $first->slug) }}" style="color:#fff;">{{ $first->name }}</a></h2>
            <div class="meta">
              <i class="bi bi-clock me-1"></i>
              {{ \Carbon\Carbon::parse($first->created_at)->diffForHumans() }}
            </div>
          </div>
        </div>
        {{-- Side sub-articles --}}
        <div class="vedette-side">
          @foreach($subs as $sub)
          <div class="vedette-side-item">
            <a href="{{ route('detail', $sub->slug) }}">
              <img src="{{ asset('storage/images/'.$sub->image) }}" alt="{{ $sub->name }}" loading="lazy" decoding="async">
            </a>
            <div class="info">
              <h4><a href="{{ route('detail', $sub->slug) }}">{{ $sub->name }}</a></h4>
              <div class="meta">
                @if($sub->category)<span class="brand-color">{{ $sub->category->name }}</span> · @endif
                {{ \Carbon\Carbon::parse($sub->created_at)->diffForHumans() }}
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
      @endif

      {{-- CATEGORY BLOCKS --}}
      @foreach($catBlocks as $cat)
      @php $catPosts = $cat->blockPosts; @endphp
      <div class="cat-block">
        <div class="cat-block-head">
          <h3><a href="{{ $cat->slug ? route('category.slug', $cat->slug) : route('result', $cat->id) }}">{{ strtoupper($cat->name) }}</a></h3>
          <a href="{{ $cat->slug ? route('category.slug', $cat->slug) : route('result', $cat->id) }}" class="see-more">Xem thêm <i class="bi bi-arrow-right"></i></a>
        </div>
        @php $mainCat = $catPosts->first(); $restCat = $catPosts->slice(1,4); @endphp
        <div class="row g-0 mb-0">
          {{-- Main post of this category --}}
          <div class="col-md-6 pe-md-2 mb-3">
            <a href="{{ route('detail', $mainCat->slug) }}" class="d-block">
              <img src="{{ asset('storage/images/'.$mainCat->image) }}"
                   alt="{{ $mainCat->name }}" loading="lazy" decoding="async"
                   style="width:100%;height:190px;object-fit:cover;border-radius:4px;">
            </a>
            <h4 class="mt-2 mb-1" style="font-size:15px;font-weight:700;line-height:1.4;">
              <a href="{{ route('detail', $mainCat->slug) }}">{{ $mainCat->name }}</a>
            </h4>
            <p class="text-muted" style="font-size:13px;">{{ Str::limit($mainCat->tomtat, 90) }}</p>
            <span class="text-muted" style="font-size:11px;">
              <i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($mainCat->created_at)->diffForHumans() }}
            </span>
          </div>
          {{-- Rest list --}}
          <div class="col-md-6">
            @foreach($restCat as $rp)
            <div class="news-list-item">
              <a href="{{ route('detail', $rp->slug) }}">
                <img src="{{ asset('storage/images/'.$rp->image) }}" alt="{{ $rp->name }}" loading="lazy" decoding="async">
              </a>
              <div class="info">
                <h5><a href="{{ route('detail', $rp->slug) }}">{{ $rp->name }}</a></h5>
                <div class="meta">
                  <i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($rp->created_at)->diffForHumans() }}
                </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>
      @endforeach

      {{-- LATEST NEWS LIST --}}
      <div class="cat-block">
        <div class="cat-block-head">
          <h3>TIN MỚI NHẤT</h3>
        </div>
        @foreach ($newProduct as $item)
        <article class="news-list-item align-items-start" style="gap:14px;">
          <a href="{{ route('detail', $item->slug) }}" style="flex-shrink:0;">
            <img src="{{ asset('storage/images/'.$item->image) }}" alt="{{ $item->name }}"
                 loading="lazy" decoding="async"
                 style="width:120px;height:85px;object-fit:cover;border-radius:4px;">
          </a>
          <div class="info" style="flex:1;">
            @if($item->category)
              <span class="cat-badge mb-1">{{ $item->category->name }}</span>
            @endif
            <h5 style="font-size:15px;">
              <a href="{{ route('detail', $item->slug) }}">{{ $item->name }}</a>
            </h5>
            <p class="text-muted mb-1" style="font-size:13px;">{{ Str::limit($item->tomtat, 100) }}</p>
            <div class="meta">
              <i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}
            </div>
          </div>
        </article>
        @endforeach

        <div class="mt-3 d-flex justify-content-center">
          {{ $newProduct->links('vendor.pagination.custom-pagination') }}
        </div>
      </div>

    </div>
    {{-- =================== SIDEBAR =================== --}}
    <aside class="sidebar">

      {{-- Most Viewed --}}
      <div class="sidebar-widget">
        <div class="widget-title"><i class="bi bi-fire me-1"></i>Đọc Nhiều Nhất</div>
        @foreach($mostRead as $i => $mr)
        <div class="most-read-item">
          <div class="rank">{{ $i+1 }}</div>
          <h5><a href="{{ route('detail', $mr->slug) }}">{{ $mr->name }}</a></h5>
        </div>
        @endforeach
      </div>

      {{-- Market Rates Widget --}}
      <div class="sidebar-widget">
        <div class="widget-title"><i class="bi bi-graph-up me-1"></i>Thị Trường</div>
        <table class="market-table">
          <tr><th>Loại</th><th>Mua</th><th>Bán</th></tr>
          <tr><td>Vàng SJC</td><td class="up">141,9 tr</td><td class="down">144,9 tr</td></tr>
          <tr><td>USD</td><td class="up">25.130</td><td class="down">25.480</td></tr>
          <tr><td>EUR</td><td class="up">27.400</td><td class="down">28.100</td></tr>
          <tr><td>JPY</td><td class="up">168</td><td class="down">175</td></tr>
        </table>
        <p class="text-muted mt-2 mb-0" style="font-size:11px;">
          <i class="bi bi-clock me-1"></i>Cập nhật: {{ \Carbon\Carbon::now('Asia/Ho_Chi_Minh')->format('H:i d/m/Y') }}
        </p>
      </div>

      {{-- Weather Widget --}}
      <div class="sidebar-widget">
        <div class="widget-title"><i class="bi bi-cloud-sun me-1"></i>Thời Tiết</div>
        <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
          <span style="font-size:13px;"><i class="bi bi-geo-alt me-1"></i>Hà Nội</span>
          <span style="font-size:18px;font-weight:700;" class="brand-color">32°C</span>
        </div>
        <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
          <span style="font-size:13px;"><i class="bi bi-geo-alt me-1"></i>TP. HCM</span>
          <span style="font-size:18px;font-weight:700;" class="brand-color">35°C</span>
        </div>
        <div class="d-flex justify-content-between align-items-center py-1">
          <span style="font-size:13px;"><i class="bi bi-geo-alt me-1"></i>Đà Nẵng</span>
          <span style="font-size:18px;font-weight:700;" class="brand-color">31°C</span>
        </div>
      </div>

      {{-- Tham gia cộng đồng --}}
      <div class="sidebar-widget" style="background:linear-gradient(135deg,#f0f7ff,#fff9e6);border-top:3px solid var(--accent);">
        <div class="widget-title" style="color:var(--brand);"><i class="bi bi-people-fill me-1"></i>Tham Gia Cộng Đồng</div>
        <div style="text-align:center;padding:6px 0 12px;">
          <div style="font-size:28px;font-weight:900;color:var(--accent);line-height:1;">MIỄN PHÍ</div>
          <div style="font-size:12px;color:#555;margin-top:2px;">Không thu phí — Không điều kiện</div>
        </div>
        <div style="font-size:13px;color:#333;line-height:1.85;margin-bottom:12px;">
          <div><i class="bi bi-check-circle-fill me-1" style="color:#27ae60;"></i>Đọc không giới hạn</div>
          <div><i class="bi bi-check-circle-fill me-1" style="color:#27ae60;"></i>Bình luận & tương tác</div>
          <div><i class="bi bi-star-fill me-1" style="color:#F0A500;"></i>Đóng góp → Nhận quyền lợi</div>
        </div>
        @guest
        <a href="{{ route('register') }}" class="vnkr-btn vnkr-btn--brand vnkr-btn--full vnkr-btn--sm">
          <i class="bi bi-person-plus me-1"></i>Đăng ký ngay — Miễn phí
        </a>
        <div style="text-align:center;margin-top:8px;font-size:12px;color:#777;">
          Đã có tài khoản? <a href="{{ route('login') }}" style="color:var(--brand);">Đăng nhập</a>
        </div>
        @else
        <div style="text-align:center;font-size:13px;color:#27ae60;font-weight:700;padding:6px 0;">
          <i class="bi bi-check-circle-fill me-1"></i>Bạn đã là thành viên cộng đồng!
        </div>
        @endguest
        <div style="margin-top:10px;border-top:1px solid rgba(0,0,0,.08);padding-top:8px;text-align:center;">
          <a href="/about" style="font-size:12px;color:var(--brand);font-weight:600;">
            <i class="bi bi-info-circle me-1"></i>Xem quyền lợi đóng góp
          </a>
        </div>
      </div>

      {{-- Newsletter --}}
      <div class="sidebar-widget" style="background:var(--brand-light);border-top-color:var(--brand);">
        <div class="widget-title"><i class="bi bi-envelope me-1"></i>Nhận Tin Qua Email</div>
        <p style="font-size:13px;color:#555;margin-bottom:10px;">
          Đăng ký để nhận bản tin tóm tắt hàng ngày.
        </p>
        <form>
          <div class="vnkr-field mb-2" data-size="small">
            <div class="vnkr-input-wrap">
              <span class="vnkr-input-icon vnkr-input-icon--left"><i class="bi bi-envelope"></i></span>
              <input type="email" class="vnkr-input" placeholder="Email của bạn..." autocomplete="email">
            </div>
          </div>
          <button type="submit" class="vnkr-btn vnkr-btn--brand vnkr-btn--full vnkr-btn--sm">
            <i class="bi bi-send me-1"></i>Đăng ký ngay
          </button>
        </form>
      </div>

      {{-- Categories list --}}
      <div class="sidebar-widget">
        <div class="widget-title"><i class="bi bi-grid me-1"></i>Chuyên Mục</div>
        <ul class="list-unstyled mb-0">
          @foreach($navCategories as $sc)
          <li class="border-bottom py-2 d-flex justify-content-between align-items-center">
            <a href="{{ $sc->slug ? route('category.slug', $sc->slug) : route('result', $sc->id) }}" style="font-size:13.5px;font-weight:500;">
              <i class="bi bi-chevron-right me-1 brand-color"></i>{{ $sc->name }}
            </a>
            <span class="badge" style="background:var(--brand-light);color:var(--brand);font-size:11px;">
              {{ $sc->products_count }}
            </span>
          </li>
          @endforeach
        </ul>
      </div>

    </aside>

  </div>{{-- /main-wrapper --}}
</div>{{-- /container --}}
@endsection
