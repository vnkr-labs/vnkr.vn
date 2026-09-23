@extends('fe.index')
@section('title', 'Web3 & Crypto — Cộng Đồng VNKR · TheKingBao')
@section('meta_description', 'Tin tức Web3, Blockchain và Crypto tại VNKR — Được biên tập bởi cộng đồng TheKingBao. Thông tin minh bạch, không thu phí.')
@section('canonical', url('/chuyen-muc/web3-crypto'))

@section('main')
<div class="container" style="padding-top:20px;">

  {{-- Hero --}}
  <div class="cat-block mb-3" style="border-top:4px solid #6f42c1;background:linear-gradient(135deg,#fdf8ff 0%,#f0f7ff 100%);">
    <div class="d-flex align-items-center gap-3 flex-wrap p-1">
      <div style="font-size:40px;line-height:1;">⛓️</div>
      <div style="flex:1;">
        <h1 style="font-size:22px;font-weight:900;color:#6f42c1;margin:0 0 4px;">WEB3 & CRYPTO</h1>
        <p style="font-size:14px;color:#555;margin:0;">
          Tin tức blockchain, tiền mã hoá, NFT, DeFi và hệ sinh thái Web3
          được biên tập minh bạch — <strong style="color:#6f42c1;">không pump, không shill</strong>.
        </p>
      </div>
      <div style="text-align:center;background:#fdf8ff;border:2px solid #6f42c1;border-radius:8px;padding:10px 16px;flex-shrink:0;">
        <div style="font-size:11px;font-weight:700;color:#888;text-transform:uppercase;">Quan điểm</div>
        <div style="font-size:16px;font-weight:900;color:#6f42c1;">Trung Lập</div>
        <div style="font-size:11px;color:#888;">Không khuyến nghị đầu tư</div>
      </div>
    </div>
  </div>

  {{-- Tuyên bố miễn trách --}}
  <div style="background:#fff8e1;border:1px solid #ffe082;border-left:4px solid #F0A500;border-radius:4px;padding:10px 16px;margin-bottom:20px;font-size:13px;color:#7a5c00;">
    <i class="bi bi-exclamation-triangle-fill me-2" style="color:#F0A500;"></i>
    <strong>Lưu ý quan trọng:</strong> Nội dung trên VNKR chỉ mang tính thông tin, không phải lời khuyên tài chính hay đầu tư.
    Mọi quyết định đầu tư tiền mã hoá là trách nhiệm cá nhân của bạn. DYOR (Do Your Own Research).
  </div>

  <div class="main-wrapper">
    <div>

      {{-- Dashboard giá thị trường --}}
      <div class="cat-block mb-4" style="border-top-color:#6f42c1;">
        <div class="cat-block-head" style="border-bottom-color:#6f42c1;">
          <h3 style="color:#6f42c1;"><i class="bi bi-graph-up-arrow me-1"></i>GIÁ THỊ TRƯỜNG (Tham khảo)</h3>
          <span style="font-size:11px;color:#999;font-weight:400;"><i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::now('Asia/Ho_Chi_Minh')->format('H:i d/m/Y') }}</span>
        </div>
        @php
        $cryptoData = [
            ['name'=>'Bitcoin',  'sym'=>'BTC', 'icon'=>'₿', 'price'=>'$67,240', 'change'=>'+2.4%',  'up'=>true,  'color'=>'#F7931A'],
            ['name'=>'Ethereum', 'sym'=>'ETH', 'icon'=>'Ξ', 'price'=>'$3,510',  'change'=>'+1.8%',  'up'=>true,  'color'=>'#627EEA'],
            ['name'=>'BNB',      'sym'=>'BNB', 'icon'=>'●', 'price'=>'$580',    'change'=>'-0.6%',  'up'=>false, 'color'=>'#F3BA2F'],
            ['name'=>'Solana',   'sym'=>'SOL', 'icon'=>'◎', 'price'=>'$178',    'change'=>'+3.2%',  'up'=>true,  'color'=>'#9945FF'],
            ['name'=>'XRP',      'sym'=>'XRP', 'icon'=>'✕', 'price'=>'$0.59',   'change'=>'-1.1%',  'up'=>false, 'color'=>'#00AAE4'],
            ['name'=>'USDT',     'sym'=>'USDT','icon'=>'₮', 'price'=>'$1.00',   'change'=>'0.0%',   'up'=>true,  'color'=>'#26A17B'],
        ];
        @endphp
        <div class="row g-2 mt-1">
          @foreach($cryptoData as $c)
          <div class="col-6 col-md-4">
            <div style="padding:12px 14px;border:1px solid var(--border);border-radius:6px;border-left:4px solid {{ $c['color'] }};display:flex;align-items:center;gap:10px;">
              <div style="font-size:20px;font-weight:900;color:{{ $c['color'] }};width:28px;text-align:center;flex-shrink:0;">{{ $c['icon'] }}</div>
              <div style="flex:1;min-width:0;">
                <div style="font-weight:800;font-size:13px;color:#333;">{{ $c['sym'] }}</div>
                <div style="font-size:11px;color:#999;">{{ $c['name'] }}</div>
              </div>
              <div style="text-align:right;">
                <div style="font-size:13px;font-weight:700;color:#333;">{{ $c['price'] }}</div>
                <div style="font-size:12px;font-weight:700;color:{{ $c['up'] ? '#27ae60' : '#dc3545' }};">{{ $c['change'] }}</div>
              </div>
            </div>
          </div>
          @endforeach
        </div>
        <div style="font-size:11px;color:#aaa;margin-top:8px;text-align:right;">
          * Dữ liệu mang tính tham khảo. Không phải lời khuyên đầu tư.
        </div>
      </div>

      {{-- Chủ đề Web3 --}}
      <div class="cat-block mb-4" style="border-top-color:#6f42c1;">
        <div class="cat-block-head" style="border-bottom-color:#6f42c1;">
          <h3 style="color:#6f42c1;">CHỦ ĐỀ WEB3</h3>
        </div>
        @php
        $web3Topics = [
            ['icon'=>'₿', 'title'=>'Bitcoin & Layer 1',  'desc'=>'BTC, ETH, SOL — các blockchain nền tảng', 'color'=>'#F7931A', 'tag'=>'bitcoin'],
            ['icon'=>'🔄', 'title'=>'DeFi',               'desc'=>'Tài chính phi tập trung — DEX, lending, yield', 'color'=>'#27ae60', 'tag'=>'defi'],
            ['icon'=>'🖼',  'title'=>'NFT & Metaverse',    'desc'=>'Tài sản số, thế giới ảo, gaming Web3', 'color'=>'#6f42c1', 'tag'=>'nft'],
            ['icon'=>'🏦',  'title'=>'Crypto Việt Nam',    'desc'=>'Tin tức crypto trong nước, pháp lý VN', 'color'=>'#E84118', 'tag'=>'crypto'],
            ['icon'=>'⚡',  'title'=>'Layer 2 & Scaling',  'desc'=>'Polygon, Arbitrum, Optimism, Lightning', 'color'=>'#627EEA', 'tag'=>'web3'],
            ['icon'=>'🔐',  'title'=>'Bảo Mật & Wallet',   'desc'=>'Cold wallet, hot wallet, bảo vệ tài sản', 'color'=>'#0A3D62', 'tag'=>'web3'],
        ];
        @endphp
        <div class="row g-2 mt-1">
          @foreach($web3Topics as $t)
          <div class="col-6 col-md-4">
            <a href="{{ route('search') }}?s={{ $t['tag'] }}" style="display:block;padding:12px;border:1px solid var(--border);border-radius:6px;text-decoration:none;border-top:3px solid {{ $t['color'] }};background:#fff;">
              <div style="font-size:22px;margin-bottom:6px;">{{ $t['icon'] }}</div>
              <div style="font-weight:800;font-size:13.5px;color:#333;margin-bottom:3px;">{{ $t['title'] }}</div>
              <div style="font-size:12px;color:#777;line-height:1.4;">{{ $t['desc'] }}</div>
            </a>
          </div>
          @endforeach
        </div>
      </div>

      {{-- Bài viết --}}
      @if($products->count() > 0)
      <div class="cat-block">
        <div class="cat-block-head" style="border-bottom-color:#6f42c1;">
          <h3 style="color:#6f42c1;">BÀI VIẾT MỚI NHẤT</h3>
        </div>
        @foreach($products as $item)
        <div class="news-list-item align-items-start" style="gap:14px;padding:12px 0;border-bottom:1px solid var(--border);">
          <a href="{{ route('detail', $item->slug) }}" style="flex-shrink:0;">
            <img src="{{ asset('storage/images/'.$item->image) }}" alt="{{ $item->name }}"
                 loading="lazy" decoding="async"
                 style="width:100px;height:70px;object-fit:cover;border-radius:4px;">
          </a>
          <div class="info" style="flex:1;">
            <div class="d-flex gap-1 mb-1 flex-wrap">
              <span class="cat-badge" style="background:#fdf8ff;color:#6f42c1;border-color:#e2d5f5;">Web3 & Crypto</span>
              @foreach($item->tags->take(2) as $tag)
                <span style="background:#f4f6f8;color:#555;padding:1px 6px;border-radius:3px;font-size:10.5px;">{{ $tag->name }}</span>
              @endforeach
            </div>
            <h5 style="font-size:14.5px;"><a href="{{ route('detail', $item->slug) }}">{{ $item->name }}</a></h5>
            <p class="text-muted mb-1" style="font-size:13px;">{{ Str::limit($item->tomtat, 100) }}</p>
            <div class="meta" style="font-size:12px;">
              <i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}
              <span class="ms-2"><i class="bi bi-eye me-1"></i>{{ number_format($item->view_count ?? 0) }}</span>
            </div>
          </div>
        </div>
        @endforeach
        <div class="mt-3 d-flex justify-content-center">
          {{ $products->links('vendor.pagination.custom-pagination') }}
        </div>
      </div>
      @else
      <div class="cat-block text-center py-4">
        <div style="font-size:36px;margin-bottom:10px;">⛓️</div>
        <div style="font-size:15px;font-weight:700;color:#6f42c1;margin-bottom:6px;">Chưa có bài viết nào</div>
        <p style="color:#777;font-size:13.5px;">Các bài viết về Web3 & Crypto sẽ được đăng tại đây.</p>
      </div>
      @endif

    </div>

    {{-- SIDEBAR --}}
    <aside class="sidebar">

      {{-- Ví Web3 --}}
      <div class="sidebar-widget" style="border-top:3px solid #6f42c1;">
        <div class="widget-title" style="color:#6f42c1;"><i class="bi bi-wallet2 me-1"></i>Ví Web3 Phổ Biến</div>
        @php
        $wallets = [
            ['name'=>'MetaMask',    'desc'=>'EVM chains — ETH, BNB, Polygon',   'color'=>'#E2761B', 'icon'=>'🦊'],
            ['name'=>'Phantom',     'desc'=>'Solana, Ethereum, Polygon',          'color'=>'#9945FF', 'icon'=>'👻'],
            ['name'=>'Trust Wallet','desc'=>'Multi-chain, mobile-first',          'color'=>'#3375BB', 'icon'=>'🛡'],
            ['name'=>'Ledger',      'desc'=>'Hardware wallet — bảo mật cao nhất','color'=>'#333',    'icon'=>'🔐'],
        ];
        @endphp
        @foreach($wallets as $w)
        <div style="display:flex;align-items:center;gap:10px;padding:7px 0;border-bottom:1px solid var(--border);">
          <div style="font-size:20px;width:28px;text-align:center;">{{ $w['icon'] }}</div>
          <div>
            <div style="font-size:13px;font-weight:700;color:#333;">{{ $w['name'] }}</div>
            <div style="font-size:11.5px;color:#888;">{{ $w['desc'] }}</div>
          </div>
        </div>
        @endforeach
        <div style="font-size:11px;color:#aaa;margin-top:8px;">* VNKR không phải tư vấn tài chính</div>
      </div>

      {{-- Tuyên bố --}}
      <div class="sidebar-widget" style="background:#fffef5;border-top:3px solid #F0A500;">
        <div class="widget-title" style="color:#b8860b;"><i class="bi bi-shield-fill-check me-1"></i>Quan Điểm VNKR</div>
        <div style="font-size:13px;color:#555;line-height:1.75;">
          <p>VNKR đưa tin về Web3 theo tinh thần <strong>trung lập, minh bạch</strong>.</p>
          <p>Chúng tôi <strong>không nhận tiền</strong> từ bất kỳ dự án crypto nào để đưa tin có lợi.</p>
          <p style="margin-bottom:0;"><strong>Không bao giờ pump hay shill</strong> bất kỳ token nào.</p>
        </div>
      </div>

      {{-- Chuyên mục khác --}}
      <div class="sidebar-widget">
        <div class="widget-title"><i class="bi bi-grid me-1"></i>Khám Phá Thêm</div>
        <ul class="list-unstyled mb-0">
          <li class="border-bottom py-2"><a href="/chuyen-muc/thao-luan" style="font-size:13.5px;font-weight:600;"><i class="bi bi-chat-dots me-1 brand-color"></i>Thảo Luận</a></li>
          <li class="border-bottom py-2"><a href="/chuyen-muc/tai-tro-dong-gop" style="font-size:13.5px;font-weight:600;color:#b8860b;"><i class="bi bi-heart-fill me-1" style="color:#F0A500;"></i>Tài Trợ & Đóng Góp</a></li>
          <li class="py-2"><a href="/chuyen-muc/cong-nghe" style="font-size:13.5px;"><i class="bi bi-cpu me-1 brand-color"></i>Công Nghệ</a></li>
        </ul>
      </div>

    </aside>
  </div>
</div>
@endsection
