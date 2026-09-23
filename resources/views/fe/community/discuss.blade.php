@extends('fe.index')
@section('title', 'Thảo Luận — Cộng Đồng VNKR · TheKingBao')
@section('meta_description', 'Không gian thảo luận cộng đồng VNKR — Chia sẻ góc nhìn, tranh luận lành mạnh, cùng nhau hiểu sâu hơn về các vấn đề trong cuộc sống.')
@section('canonical', url('/chuyen-muc/thao-luan'))

@section('main')
<div class="container" style="padding-top:20px;">

  {{-- Hero banner --}}
  <div class="cat-block mb-3" style="border-top:4px solid var(--accent);background:linear-gradient(135deg,#f0f7ff 0%,#fff9e6 100%);">
    <div class="d-flex align-items-center gap-3 flex-wrap p-1">
      <div style="font-size:40px;line-height:1;">💬</div>
      <div style="flex:1;">
        <h1 style="font-size:22px;font-weight:900;color:var(--brand);margin:0 0 4px;">THẢO LUẬN CỘNG ĐỒNG</h1>
        <p style="font-size:14px;color:#555;margin:0;">Không gian trao đổi mở — Mọi ý kiến đều được lắng nghe.
          <strong style="color:var(--brand);">Hoàn toàn miễn phí, không điều kiện.</strong></p>
      </div>
      @guest
      <a href="{{ route('register') }}" class="vnkr-btn vnkr-btn--brand vnkr-btn--sm" style="white-space:nowrap;">
        <i class="bi bi-person-plus me-1"></i>Tham gia thảo luận
      </a>
      @endguest
    </div>
  </div>

  {{-- Rules bar --}}
  <div style="background:#f8f9fa;border:1px solid var(--border);border-radius:6px;padding:10px 16px;margin-bottom:20px;font-size:13px;color:#555;display:flex;gap:20px;flex-wrap:wrap;align-items:center;">
    <span style="font-weight:700;color:var(--brand);"><i class="bi bi-shield-check me-1"></i>Quy tắc:</span>
    <span><i class="bi bi-check-circle-fill me-1" style="color:#27ae60;"></i>Tôn trọng nhau</span>
    <span><i class="bi bi-check-circle-fill me-1" style="color:#27ae60;"></i>Tranh luận có căn cứ</span>
    <span><i class="bi bi-check-circle-fill me-1" style="color:#27ae60;"></i>Không spam</span>
    <span><i class="bi bi-check-circle-fill me-1" style="color:#27ae60;"></i>Không tin giả</span>
    <a href="/community" style="margin-left:auto;color:var(--brand);font-size:12px;">Xem đầy đủ →</a>
  </div>

  <div class="main-wrapper">
    <div>

      {{-- Thread list --}}
      @forelse($products as $item)
      <div class="cat-block mb-3" style="padding:0;overflow:hidden;">
        <div style="display:flex;gap:0;">

          {{-- Left: vote/stats column --}}
          <div style="width:64px;flex-shrink:0;background:#f4f6f8;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:16px 8px;gap:8px;border-right:1px solid var(--border);">
            <div style="text-align:center;">
              <div style="font-size:18px;font-weight:900;color:var(--brand);">{{ $item->like_count ?? 0 }}</div>
              <div style="font-size:10px;color:#888;">vote</div>
            </div>
            <div style="text-align:center;">
              <div style="font-size:16px;font-weight:700;color:#555;">{{ $item->comments_count ?? 0 }}</div>
              <div style="font-size:10px;color:#888;">bình luận</div>
            </div>
            <div style="text-align:center;">
              <div style="font-size:14px;font-weight:600;color:#27ae60;">{{ number_format($item->view_count ?? 0) }}</div>
              <div style="font-size:10px;color:#888;">xem</div>
            </div>
          </div>

          {{-- Right: content --}}
          <div style="flex:1;padding:14px 16px;">
            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
              <span class="cat-badge">Thảo Luận</span>
              @foreach($item->tags->take(3) as $tag)
                <span style="background:#f0f7ff;color:var(--brand);padding:2px 8px;border-radius:3px;font-size:11px;font-weight:600;">{{ $tag->name }}</span>
              @endforeach
            </div>
            <h3 style="font-size:16px;font-weight:800;margin:4px 0 6px;line-height:1.4;">
              <a href="{{ route('detail', $item->slug) }}" style="color:var(--text);">{{ $item->name }}</a>
            </h3>
            <p style="font-size:13.5px;color:#666;margin:0 0 8px;line-height:1.6;">{{ Str::limit($item->tomtat, 120) }}</p>
            <div style="font-size:12px;color:#999;display:flex;gap:16px;flex-wrap:wrap;align-items:center;">
              <span><i class="bi bi-person me-1"></i>{{ $item->author->name ?? 'PTB' }}</span>
              <span><i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</span>
              <a href="{{ route('detail', $item->slug) }}" style="margin-left:auto;color:var(--brand);font-size:12px;font-weight:700;">
                Tham gia thảo luận <i class="bi bi-arrow-right"></i>
              </a>
            </div>
          </div>

        </div>
      </div>
      @empty
      <div class="cat-block text-center py-5">
        <div style="font-size:40px;margin-bottom:12px;">💬</div>
        <div style="font-size:16px;font-weight:700;color:var(--brand);margin-bottom:8px;">Chưa có chủ đề nào</div>
        <p style="font-size:14px;color:#777;">Hãy là người đầu tiên khởi xướng thảo luận trong cộng đồng VNKR!</p>
        <a href="{{ route('contact.show') }}" class="vnkr-btn vnkr-btn--brand vnkr-btn--sm">
          <i class="bi bi-pencil me-1"></i>Gửi chủ đề thảo luận
        </a>
      </div>
      @endforelse

      {{-- Pagination --}}
      <div class="mt-3 d-flex justify-content-center">
        {{ $products->links('vendor.pagination.custom-pagination') }}
      </div>
    </div>

    {{-- SIDEBAR --}}
    <aside class="sidebar">
      {{-- Tham gia --}}
      <div class="sidebar-widget" style="background:linear-gradient(135deg,#f0f7ff,#fff9e6);border-top:3px solid var(--accent);">
        <div class="widget-title" style="color:var(--brand);"><i class="bi bi-people-fill me-1"></i>Cộng Đồng Thảo Luận</div>
        <div style="font-size:13px;color:#333;line-height:1.85;margin-bottom:12px;">
          <div><i class="bi bi-check-circle-fill me-1" style="color:#27ae60;"></i>Đặt câu hỏi tự do</div>
          <div><i class="bi bi-check-circle-fill me-1" style="color:#27ae60;"></i>Chia sẻ quan điểm cá nhân</div>
          <div><i class="bi bi-check-circle-fill me-1" style="color:#27ae60;"></i>Tranh luận lành mạnh</div>
          <div><i class="bi bi-star-fill me-1" style="color:#F0A500;"></i>Bình luận hay → Badge tích cực</div>
        </div>
        @guest
        <a href="{{ route('register') }}" class="vnkr-btn vnkr-btn--brand vnkr-btn--full vnkr-btn--sm">
          <i class="bi bi-person-plus me-1"></i>Đăng ký — Miễn phí
        </a>
        @else
        <div style="text-align:center;font-size:13px;color:#27ae60;font-weight:700;padding:4px 0;">
          <i class="bi bi-check-circle-fill me-1"></i>Bạn đã là thành viên!
        </div>
        @endguest
      </div>

      {{-- Quy tắc --}}
      <div class="sidebar-widget">
        <div class="widget-title"><i class="bi bi-shield-check me-1"></i>Quy Tắc Thảo Luận</div>
        <div style="font-size:13px;color:#444;line-height:1.9;">
          <div><i class="bi bi-1-circle me-2 brand-color"></i>Tôn trọng mọi người</div>
          <div><i class="bi bi-2-circle me-2 brand-color"></i>Tranh luận dựa trên sự kiện</div>
          <div><i class="bi bi-3-circle me-2 brand-color"></i>Không quảng cáo, spam</div>
          <div><i class="bi bi-4-circle me-2 brand-color"></i>Không kích động, chia rẽ</div>
        </div>
        <a href="/community" style="font-size:12px;color:var(--brand);margin-top:8px;display:block;">Xem quy chế đầy đủ →</a>
      </div>

      {{-- Chuyên mục khác --}}
      <div class="sidebar-widget">
        <div class="widget-title"><i class="bi bi-grid me-1"></i>Khám Phá Thêm</div>
        <ul class="list-unstyled mb-0">
          <li class="border-bottom py-2"><a href="/chuyen-muc/tai-tro-dong-gop" style="font-size:13.5px;color:#b8860b;font-weight:700;"><i class="bi bi-heart-fill me-1" style="color:#F0A500;"></i>Tài Trợ & Đóng Góp</a></li>
          <li class="border-bottom py-2"><a href="/chuyen-muc/web3-crypto" style="font-size:13.5px;color:#6f42c1;font-weight:700;"><i class="bi bi-currency-bitcoin me-1"></i>Web3 & Crypto</a></li>
          <li class="py-2"><a href="/about" style="font-size:13.5px;"><i class="bi bi-info-circle me-1 brand-color"></i>Về VNKR</a></li>
        </ul>
      </div>
    </aside>
  </div>
</div>
@endsection
