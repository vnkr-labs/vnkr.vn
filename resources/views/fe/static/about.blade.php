@extends('fe.index')
@section('title', 'Giới Thiệu VNKR — Cộng Đồng Phục Vụ Cộng Đồng')
@section('main')
<div class="container" style="padding-top:24px;max-width:860px;">

  {{-- Hero --}}
  <div class="cat-block" style="border-top:4px solid var(--accent);text-align:center;padding:32px 24px;">
    <div style="font-size:48px;font-weight:900;color:var(--brand);letter-spacing:-2px;border-bottom:4px solid var(--accent);display:inline-block;padding:6px 20px;border-radius:6px;margin-bottom:12px;">VNKR</div>
    <h1 style="font-size:22px;font-weight:800;color:var(--text);margin-bottom:8px;">Cộng Đồng Phục Vụ Cộng Đồng</h1>
    <p style="font-size:15px;color:var(--text-muted);max-width:600px;margin:0 auto;">
      Nền tảng thông tin <strong>hoàn toàn miễn phí</strong> — do cộng đồng xây dựng, dành cho cộng đồng —
      không thu bất kỳ khoản phí nào từ người tham gia.
    </p>
  </div>

  {{-- Cam kết cốt lõi --}}
  <div class="cat-block mt-3">
    <div class="cat-block-head"><h3>CAM KẾT CỐT LÕI</h3></div>
    <div class="row g-3 mt-1">
      <div class="col-md-4">
        <div class="fe-feature-card fe-feature-card--brand">
          <i class="bi bi-gift-fill fe-feature-card-icon"></i>
          <div class="fe-feature-card-title" style="color:var(--brand);">Hoàn Toàn Miễn Phí</div>
          <div class="fe-feature-card-desc">Đọc tin, bình luận, đăng ký tài khoản — tất cả đều <strong>không mất một đồng nào</strong>. Mãi mãi miễn phí.</div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="fe-feature-card fe-feature-card--gold">
          <i class="bi bi-people-fill fe-feature-card-icon" style="color:var(--gold);"></i>
          <div class="fe-feature-card-title" style="color:#b8860b;">Cộng Đồng Là Chủ</div>
          <div class="fe-feature-card-desc">VNKR được xây dựng <em>bởi</em> cộng đồng và <em>dành cho</em> cộng đồng. Mỗi người đóng góp đều là một phần chủ nhân thực sự.</div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="fe-feature-card fe-feature-card--accent">
          <i class="bi bi-award-fill fe-feature-card-icon" style="color:var(--accent);"></i>
          <div class="fe-feature-card-title" style="color:var(--accent);">Đóng Góp → Hưởng Lợi</div>
          <div class="fe-feature-card-desc">Nhà đóng góp tích cực được hưởng quyền lợi thực sự: danh hiệu, ưu tiên, tiếng nói trong cộng đồng.</div>
        </div>
      </div>
    </div>
  </div>

  {{-- VNKR là gì --}}
  <div class="cat-block mt-3">
    <div class="cat-block-head"><h3>VNKR LÀ GÌ?</h3></div>
    <p><strong>VNKR</strong> (vnkr.vn) là <strong>trang thông tin điện tử tổng hợp phi lợi nhuận</strong> do cộng đồng
    <strong>TheKingBao</strong> cùng nhau xây dựng, với người sáng lập là <strong>Phạm Thế Bảo</strong>.</p>
    <p>VNKR <strong>KHÔNG phải là cơ quan báo chí</strong> và <strong>KHÔNG thu bất kỳ khoản phí tham gia nào</strong>
    từ người dùng, người đọc hay nhà đóng góp. Đây là nền tảng cộng đồng mở, nơi mọi người
    cùng chọn lọc, biên tập và chia sẻ thông tin hữu ích.</p>

    <div class="vnkr-callout vnkr-callout--info" style="margin-top:16px;">
      <div class="vnkr-callout-title"><i class="bi bi-quote me-2"></i>Tuyên ngôn VNKR</div>
      <p style="font-size:15px;color:#333;line-height:1.8;margin:0;font-style:italic;">
        "VNKR được trao cho cộng đồng, phục vụ cho cộng đồng. Mọi người đều có quyền đọc, tham gia
        và đóng góp mà không cần trả bất kỳ khoản phí nào. Những ai đóng góp xây dựng VNKR
        sẽ là người đầu tiên được hưởng lợi ích từ những gì họ tạo ra."
      </p>
      <div style="margin-top:8px;font-size:13px;color:var(--text-muted);">— Phạm Thế Bảo, Người sáng lập VNKR</div>
    </div>
  </div>

  {{-- Quyền lợi nhà đóng góp --}}
  <div class="cat-block mt-3" style="border-top-color:var(--gold);">
    <div class="cat-block-head" style="border-bottom-color:var(--gold);"><h3 style="color:#b8860b;">QUYỀN LỢI NHÀ ĐÓNG GÓP</h3></div>
    <p style="color:#555;">Khi bạn tham gia đóng góp cho cộng đồng VNKR — dù là viết bài, bình luận có giá trị,
    hay chia sẻ nội dung — bạn sẽ nhận được những quyền lợi thực sự:</p>

    <div class="row g-3 mt-1">
      <div class="col-md-6">
        <div style="padding:14px 16px;border:1px solid #f0c040;border-radius:6px;background:#fffef5;">
          <div style="font-weight:700;color:#b8860b;margin-bottom:6px;"><i class="bi bi-star-fill me-2" style="color:#F0A500;"></i>Danh Hiệu Cộng Đồng</div>
          <ul style="font-size:13px;color:#555;line-height:1.9;margin:0;padding-left:18px;">
            <li>Badge <strong>"Thành viên tích cực"</strong> hiển thị bên tên</li>
            <li>Badge <strong>"Cộng tác viên VNKR"</strong> khi gửi bài được đăng</li>
            <li>Badge <strong>"Nhà đóng góp xuất sắc"</strong> xét theo tháng</li>
          </ul>
        </div>
      </div>
      <div class="col-md-6">
        <div style="padding:14px 16px;border:1px solid #c8e6f5;border-radius:6px;background:#f5fbff;">
          <div style="font-weight:700;color:var(--brand);margin-bottom:6px;"><i class="bi bi-megaphone-fill me-2"></i>Tiếng Nói & Ảnh Hưởng</div>
          <ul style="font-size:13px;color:#555;line-height:1.9;margin:0;padding-left:18px;">
            <li>Được mention trong bài "<em>Cộng đồng nói gì</em>"</li>
            <li>Quyền đề xuất chủ đề cho PTB viết bài</li>
            <li>Ưu tiên trả lời & tương tác từ biên tập viên</li>
          </ul>
        </div>
      </div>
      <div class="col-md-6">
        <div style="padding:14px 16px;border:1px solid #d4edda;border-radius:6px;background:#f5fff8;">
          <div style="font-weight:700;color:#218838;margin-bottom:6px;"><i class="bi bi-pen-fill me-2"></i>Quyền Xuất Bản</div>
          <ul style="font-size:13px;color:#555;line-height:1.9;margin:0;padding-left:18px;">
            <li>Cộng tác viên được gửi bài xét đăng trên VNKR</li>
            <li>Tên tác giả hiển thị rõ ràng trên bài viết</li>
            <li>Profile tác giả riêng trên trang web</li>
          </ul>
        </div>
      </div>
      <div class="col-md-6">
        <div style="padding:14px 16px;border:1px solid #e2d5f5;border-radius:6px;background:#fdf8ff;">
          <div style="font-weight:700;color:#6f42c1;margin-bottom:6px;"><i class="bi bi-lightning-charge-fill me-2"></i>Ưu Tiên Đặc Biệt</div>
          <ul style="font-size:13px;color:#555;line-height:1.9;margin:0;padding-left:18px;">
            <li>Nhận bản tin nội bộ trước khi công bố</li>
            <li>Tham gia các cuộc thảo luận riêng của cộng đồng</li>
            <li>Ưu tiên khi VNKR mở rộng thêm tính năng mới</li>
          </ul>
        </div>
      </div>
    </div>

    <div style="margin-top:16px;text-align:center;">
      <a href="{{ route('register') }}" class="vnkr-btn vnkr-btn--brand vnkr-btn--sm me-2">
        <i class="bi bi-person-plus me-1"></i>Tham gia ngay — Miễn phí
      </a>
      <a href="{{ route('contact.show') }}" class="vnkr-btn vnkr-btn--ghost vnkr-btn--sm">
        <i class="bi bi-envelope me-1"></i>Liên hệ đóng góp bài
      </a>
    </div>
  </div>

  {{-- Về biên tập viên --}}
  <div class="cat-block mt-3">
    <div class="cat-block-head"><h3>VỀ NGƯỜI SÁNG LẬP</h3></div>
    <div class="d-flex gap-3 align-items-start flex-wrap">
      <div class="ptb-avatar" style="width:64px;height:64px;font-size:24px;flex-shrink:0;">P</div>
      <div>
        <h2 style="font-size:20px;font-weight:800;margin:0 0 4px;">Phạm Thế Bảo</h2>
        <div class="d-flex gap-2 flex-wrap mb-3">
          <span class="cat-badge">Người sáng lập VNKR</span>
          <span class="cat-badge gold">TheKingBao</span>
        </div>
        <p style="font-size:14.5px;color:#444;line-height:1.75;">
          Phạm Thế Bảo là người sáng lập VNKR với phương châm: <em>"Trao cho cộng đồng,
          phục vụ cho cộng đồng."</em> VNKR được xây dựng không vì lợi nhuận cá nhân,
          mà để tạo ra một không gian thông tin lành mạnh, minh bạch và bình đẳng
          cho tất cả mọi người.
        </p>
        <a href="{{ route('contact.show') }}" class="vnkr-btn vnkr-btn--brand vnkr-btn--sm">
          <i class="bi bi-envelope me-1"></i>Liên hệ Phạm Thế Bảo
        </a>
      </div>
    </div>
  </div>

  {{-- Đóng góp mã nguồn --}}
  <div class="cat-block mt-3" style="border-top-color:#24292e;">
    <div class="cat-block-head" style="border-bottom-color:#24292e;">
      <h3 style="color:#24292e;"><i class="bi bi-github me-2"></i>ĐÓNG GÓP MÃ NGUỒN</h3>
    </div>
    <div class="d-flex gap-4 align-items-center flex-wrap">
      <div style="flex:1;min-width:240px;">
        <p style="font-size:14.5px;color:#444;line-height:1.75;margin-bottom:10px;">
          VNKR là dự án <strong>mã nguồn mở</strong> — bạn có thể đóng góp code, báo lỗi,
          đề xuất tính năng để cùng xây dựng nền tảng cộng đồng tốt hơn.
          Nhà đóng góp code sẽ nhận badge <strong style="color:#F0A500;">Contributor VNKR</strong>
          và được ghi nhận công khai.
        </p>
        <div class="d-flex gap-2 flex-wrap">
          <a href="{{ route('contribute') }}" class="vnkr-btn vnkr-btn--brand vnkr-btn--sm">
            <i class="bi bi-github me-1"></i>Xem hướng dẫn đóng góp
          </a>
          <a href="https://github.com/thekingbao/vnkr.vn" target="_blank" rel="noopener" class="vnkr-btn vnkr-btn--ghost vnkr-btn--sm">
            <i class="bi bi-code-slash me-1"></i>GitHub
          </a>
        </div>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;min-width:200px;">
        <div style="text-align:center;padding:12px;background:#f4f6f8;border-radius:6px;">
          <div style="font-size:22px;">🐛</div>
          <div style="font-size:12px;font-weight:700;color:#333;margin-top:4px;">Báo lỗi</div>
        </div>
        <div style="text-align:center;padding:12px;background:#f4f6f8;border-radius:6px;">
          <div style="font-size:22px;">✨</div>
          <div style="font-size:12px;font-weight:700;color:#333;margin-top:4px;">Tính năng</div>
        </div>
        <div style="text-align:center;padding:12px;background:#f4f6f8;border-radius:6px;">
          <div style="font-size:22px;">📝</div>
          <div style="font-size:12px;font-weight:700;color:#333;margin-top:4px;">Tài liệu</div>
        </div>
        <div style="text-align:center;padding:12px;background:#f4f6f8;border-radius:6px;">
          <div style="font-size:22px;">🎨</div>
          <div style="font-size:12px;font-weight:700;color:#333;margin-top:4px;">UI/UX</div>
        </div>
      </div>
    </div>
  </div>

  {{-- Tuyên bố pháp lý --}}
  <div class="cat-block mt-3" style="background:#f8fafe;border-top-color:var(--accent);">
    <div class="cat-block-head"><h3>TUYÊN BỐ PHÁP LÝ</h3></div>
    <div class="vnkr-callout vnkr-callout--info">
      <p><strong>VNKR (vnkr.vn)</strong> là trang thông tin điện tử tổng hợp phi lợi nhuận, do cá nhân
      <strong>Phạm Thế Bảo</strong> vận hành, <strong>KHÔNG phải cơ quan báo chí</strong>
      theo quy định của Luật Báo chí Việt Nam 2016.</p>
      <p>VNKR cam kết:</p>
      <ul style="margin:8px 0 8px 20px;">
        <li><strong>Không thu bất kỳ khoản phí nào</strong> từ người dùng, người đọc hay người đóng góp</li>
        <li>Toàn bộ nội dung là bản biên tập, tóm tắt từ nguồn tin tức công khai, hợp pháp, có trích dẫn</li>
        <li>Phục vụ mục đích thông tin cộng đồng, không nhằm mục đích thương mại trực tiếp</li>
        <li>Mọi người đều được quyền tham gia và đóng góp bình đẳng</li>
      </ul>
      <p style="margin-bottom:0;"><strong>Liên hệ:</strong> phamthebao@vnkr.vn</p>
    </div>
  </div>

</div>
@endsection
