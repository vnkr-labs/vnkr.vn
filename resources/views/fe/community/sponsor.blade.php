@extends('fe.index')
@section('title', 'Tài Trợ & Đóng Góp — Cộng Đồng VNKR · TheKingBao')
@section('meta_description', 'VNKR hoàn toàn miễn phí — Tìm hiểu cách đóng góp xây dựng nền tảng cộng đồng và nhận quyền lợi xứng đáng. Không có phí bắt buộc.')
@section('canonical', url('/chuyen-muc/tai-tro-dong-gop'))

@section('main')
<div class="container" style="padding-top:20px;">

  {{-- Hero --}}
  <div class="cat-block mb-3" style="border-top:4px solid #F0A500;background:linear-gradient(135deg,#fffef5 0%,#f5fff8 100%);">
    <div class="d-flex align-items-center gap-3 flex-wrap p-1">
      <div style="font-size:40px;line-height:1;">🤝</div>
      <div style="flex:1;">
        <h1 style="font-size:22px;font-weight:900;color:#b8860b;margin:0 0 4px;">TÀI TRỢ & ĐÓNG GÓP</h1>
        <p style="font-size:14px;color:#555;margin:0;">
          VNKR <strong>không thu phí tham gia</strong>. Những ai tự nguyện đóng góp
          sẽ là những người đầu tiên được hưởng lợi ích từ những gì họ xây dựng.
        </p>
      </div>
      <div style="text-align:center;background:#fff9e6;border:2px solid #F0A500;border-radius:8px;padding:10px 16px;flex-shrink:0;">
        <div style="font-size:11px;font-weight:700;color:#888;text-transform:uppercase;letter-spacing:.5px;">Mô hình</div>
        <div style="font-size:18px;font-weight:900;color:#b8860b;">Tự Nguyện</div>
        <div style="font-size:11px;color:#888;">100% không bắt buộc</div>
      </div>
    </div>
  </div>

  {{-- Cam kết nổi bật --}}
  <div style="background:#fff;border:2px solid #27ae60;border-radius:8px;padding:16px 20px;margin-bottom:20px;display:flex;align-items:flex-start;gap:14px;">
    <i class="bi bi-shield-fill-check" style="font-size:28px;color:#27ae60;flex-shrink:0;margin-top:2px;"></i>
    <div>
      <div style="font-size:15px;font-weight:800;color:#218838;margin-bottom:6px;">Cam kết của VNKR với mọi người</div>
      <div style="font-size:13.5px;color:#444;line-height:1.85;">
        <span style="margin-right:16px;"><i class="bi bi-check-lg me-1" style="color:#27ae60;"></i>Không bao giờ thu phí bắt buộc</span>
        <span style="margin-right:16px;"><i class="bi bi-check-lg me-1" style="color:#27ae60;"></i>Không có nội dung ẩn paywall</span>
        <span><i class="bi bi-check-lg me-1" style="color:#27ae60;"></i>Không phân biệt free vs premium</span>
      </div>
    </div>
  </div>

  <div class="main-wrapper">
    <div>

      {{-- Cách đóng góp --}}
      <div class="cat-block mb-4" style="border-top-color:#F0A500;">
        <div class="cat-block-head" style="border-bottom-color:#F0A500;"><h3 style="color:#b8860b;">CÁCH ĐÓNG GÓP CHO CỘNG ĐỒNG</h3></div>
        <div class="row g-3 mt-1">

          <div class="col-md-6">
            <div style="padding:16px;border:2px solid #d4edda;border-radius:8px;background:#f5fff8;height:100%;">
              <div style="font-size:24px;margin-bottom:8px;">✍️</div>
              <div style="font-weight:800;color:#218838;font-size:15px;margin-bottom:6px;">Đóng góp nội dung</div>
              <div style="font-size:13px;color:#555;line-height:1.75;margin-bottom:10px;">
                Viết bài, chia sẻ góc nhìn, biên tập tin tức — nội dung của bạn
                sẽ được xuất bản và gắn tên tác giả rõ ràng trên VNKR.
              </div>
              <div style="font-size:12.5px;color:#218838;font-weight:700;margin-bottom:10px;">
                <i class="bi bi-gift me-1"></i>Nhận: Badge CTV · Profile tác giả · Tiếng nói cộng đồng
              </div>
              <a href="{{ route('contact.show') }}" class="vnkr-btn vnkr-btn--sm" style="background:#218838;color:#fff;">
                <i class="bi bi-pencil me-1"></i>Gửi bài
              </a>
            </div>
          </div>

          <div class="col-md-6">
            <div style="padding:16px;border:2px solid #c8e6f5;border-radius:8px;background:#f5fbff;height:100%;">
              <div style="font-size:24px;margin-bottom:8px;">💻</div>
              <div style="font-weight:800;color:var(--brand);font-size:15px;margin-bottom:6px;">Đóng góp mã nguồn</div>
              <div style="font-size:13px;color:#555;line-height:1.75;margin-bottom:10px;">
                VNKR là dự án mã nguồn mở. Báo lỗi, đề xuất tính năng, gửi Pull Request
                — mỗi dòng code của bạn đều được ghi nhận.
              </div>
              <div style="font-size:12.5px;color:var(--brand);font-weight:700;margin-bottom:10px;">
                <i class="bi bi-gift me-1"></i>Nhận: Badge Contributor · Ghi tên vĩnh viễn
              </div>
              <a href="{{ route('contribute') }}" class="vnkr-btn vnkr-btn--brand vnkr-btn--sm">
                <i class="bi bi-github me-1"></i>GitHub
              </a>
            </div>
          </div>

          <div class="col-md-6">
            <div style="padding:16px;border:2px solid #e2d5f5;border-radius:8px;background:#fdf8ff;height:100%;">
              <div style="font-size:24px;margin-bottom:8px;">💬</div>
              <div style="font-weight:800;color:#6f42c1;font-size:15px;margin-bottom:6px;">Đóng góp bình luận & thảo luận</div>
              <div style="font-size:13px;color:#555;line-height:1.75;margin-bottom:10px;">
                Bình luận có chất lượng, tham gia thảo luận thường xuyên —
                đây là xương sống của cộng đồng VNKR.
              </div>
              <div style="font-size:12.5px;color:#6f42c1;font-weight:700;margin-bottom:10px;">
                <i class="bi bi-gift me-1"></i>Nhận: Badge tích cực · Được mention hàng tuần
              </div>
              <a href="/chuyen-muc/thao-luan" class="vnkr-btn vnkr-btn--sm" style="background:#6f42c1;color:#fff;">
                <i class="bi bi-chat-dots me-1"></i>Tham gia thảo luận
              </a>
            </div>
          </div>

          <div class="col-md-6">
            <div style="padding:16px;border:2px solid #f0c040;border-radius:8px;background:#fffef5;height:100%;">
              <div style="font-size:24px;margin-bottom:8px;">💛</div>
              <div style="font-weight:800;color:#b8860b;font-size:15px;margin-bottom:6px;">Ủng hộ tự nguyện</div>
              <div style="font-size:13px;color:#555;line-height:1.75;margin-bottom:10px;">
                Nếu VNKR mang lại giá trị cho bạn và bạn muốn hỗ trợ chi phí vận hành
                — có thể ủng hộ qua Ko-fi hoặc MoMo. <strong>Hoàn toàn không bắt buộc.</strong>
              </div>
              <div style="font-size:12.5px;color:#b8860b;font-weight:700;margin-bottom:10px;">
                <i class="bi bi-gift me-1"></i>Nhận: Lời cảm ơn thật sự từ PTB + Badge ủng hộ viên
              </div>
              <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('contact.show') }}" class="vnkr-btn vnkr-btn--sm" style="background:#F0A500;color:#222;">
                  <i class="bi bi-envelope me-1"></i>Liên hệ PTB
                </a>
              </div>
            </div>
          </div>

        </div>
      </div>

      {{-- Bài viết trong danh mục --}}
      @if($products->count() > 0)
      <div class="cat-block">
        <div class="cat-block-head"><h3>TIN TỨC & CẬP NHẬT</h3></div>
        @foreach($products as $item)
        <div class="news-list-item align-items-start" style="gap:14px;padding:12px 0;border-bottom:1px solid var(--border);">
          <a href="{{ route('detail', $item->slug) }}" style="flex-shrink:0;">
            <img src="{{ asset('storage/images/'.$item->image) }}" alt="{{ $item->name }}"
                 loading="lazy" decoding="async"
                 style="width:100px;height:70px;object-fit:cover;border-radius:4px;">
          </a>
          <div class="info" style="flex:1;">
            <h5 style="font-size:14.5px;"><a href="{{ route('detail', $item->slug) }}">{{ $item->name }}</a></h5>
            <p class="text-muted mb-1" style="font-size:13px;">{{ Str::limit($item->tomtat, 100) }}</p>
            <div class="meta" style="font-size:12px;">
              <i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}
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
        <div style="font-size:36px;margin-bottom:10px;">🤝</div>
        <div style="font-size:15px;font-weight:700;color:var(--brand);margin-bottom:6px;">Chưa có bài viết nào</div>
        <p style="color:#777;font-size:13.5px;">Các cập nhật về tài trợ và đóng góp sẽ được đăng tại đây.</p>
      </div>
      @endif

    </div>

    {{-- SIDEBAR --}}
    <aside class="sidebar">

      {{-- Quyền lợi nhà đóng góp --}}
      <div class="sidebar-widget" style="border-top:3px solid #F0A500;">
        <div class="widget-title" style="color:#b8860b;"><i class="bi bi-award-fill me-1" style="color:#F0A500;"></i>Quyền Lợi Đóng Góp</div>
        <div style="font-size:13px;color:#333;line-height:1.9;">
          <div style="padding:8px 0;border-bottom:1px solid var(--border);">
            <div style="font-weight:700;color:#218838;"><i class="bi bi-person-fill me-1"></i>Thành viên tích cực</div>
            <div style="font-size:12px;color:#666;margin-top:2px;">Badge · Mention hàng tuần · Đề xuất chủ đề</div>
          </div>
          <div style="padding:8px 0;border-bottom:1px solid var(--border);">
            <div style="font-weight:700;color:var(--brand);"><i class="bi bi-pen-fill me-1"></i>Cộng tác viên</div>
            <div style="font-size:12px;color:#666;margin-top:2px;">Đăng bài · Profile tác giả · Tiếng nói định hướng</div>
          </div>
          <div style="padding:8px 0;">
            <div style="font-weight:700;color:#6f42c1;"><i class="bi bi-github me-1"></i>Contributor code</div>
            <div style="font-size:12px;color:#666;margin-top:2px;">Badge Contributor · Ghi nhận vĩnh viễn</div>
          </div>
        </div>
        <a href="/about" class="vnkr-btn vnkr-btn--sm vnkr-btn--full mt-2" style="background:#F0A500;color:#222;">
          Xem đầy đủ quyền lợi →
        </a>
      </div>

      {{-- Liên kết nhanh --}}
      <div class="sidebar-widget">
        <div class="widget-title"><i class="bi bi-lightning me-1"></i>Hành Động Ngay</div>
        <div class="d-flex flex-column gap-2">
          <a href="{{ route('contact.show') }}" class="vnkr-btn vnkr-btn--ghost vnkr-btn--sm vnkr-btn--full">
            <i class="bi bi-pencil-fill"></i>Gửi bài viết đóng góp
          </a>
          <a href="{{ route('contribute') }}" class="vnkr-btn vnkr-btn--sm vnkr-btn--full" style="background:#f4f6f8;color:#24292e;">
            <i class="bi bi-github"></i>Đóng góp mã nguồn
          </a>
          <a href="/chuyen-muc/thao-luan" class="vnkr-btn vnkr-btn--sm vnkr-btn--full" style="background:#fff9e6;color:#b8860b;">
            <i class="bi bi-chat-dots-fill"></i>Tham gia thảo luận
          </a>
          @guest
          <a href="{{ route('register') }}" class="vnkr-btn vnkr-btn--primary vnkr-btn--sm">
            <i class="bi bi-person-plus-fill"></i>Đăng ký miễn phí
          </a>
          @endguest
        </div>
      </div>

    </aside>
  </div>
</div>
@endsection
