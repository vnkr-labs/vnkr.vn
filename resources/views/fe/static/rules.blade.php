@extends('fe.index')
@section('title', 'Quy Chế Hoạt Động — VNKR')
@section('main')
<div class="container" style="padding-top:24px;max-width:820px;">
  <div class="cat-block">
    <div class="cat-block-head"><h3>QUY CHẾ HOẠT ĐỘNG CỘNG ĐỒNG VNKR</h3></div>
    <p style="font-size:13px;color:var(--text-muted);margin-bottom:20px;">Dành cho cộng đồng TheKingBao | Cập nhật: {{ date('d/m/Y') }}</p>

    <div class="article-lead">VNKR hoạt động với tinh thần: <strong>Cộng đồng phục vụ cộng đồng — Hoàn toàn miễn phí — Đóng góp được hưởng lợi.</strong></div>

    {{-- Triết lý cốt lõi --}}
    <div class="vnkr-callout vnkr-callout--info" style="margin:20px 0;">
      <div class="vnkr-callout-title"><i class="bi bi-heart-fill me-2" style="color:var(--accent);"></i>Triết Lý Hoạt Động</div>
      <p style="font-size:14px;color:#333;line-height:1.8;margin:0;">
        VNKR được xây dựng dựa trên niềm tin rằng thông tin chất lượng phải được chia sẻ tự do.
        Chúng tôi <strong>không bao giờ thu phí</strong> từ người đọc hay người đóng góp.
        Thay vào đó, những ai góp sức xây dựng cộng đồng sẽ là những người đầu tiên được hưởng
        lợi ích từ những gì họ tạo ra.
      </p>
    </div>

    <h3 style="font-size:17px;font-weight:800;color:var(--brand);margin-top:24px;">Nguyên Tắc Biên Tập</h3>
    <ul style="line-height:2;color:#444;">
      <li>Mọi bài viết phải ghi rõ nguồn trích dẫn</li>
      <li>Không đăng thông tin chưa được xác minh</li>
      <li>Phân biệt rõ "tin tức" và "góc nhìn cá nhân"</li>
      <li>Tôn trọng bản quyền của nguồn gốc</li>
      <li>Không đăng nội dung vi phạm pháp luật Việt Nam</li>
    </ul>

    <h3 style="font-size:17px;font-weight:800;color:var(--brand);margin-top:24px;">Quy Tắc Cộng Đồng</h3>
    <ul style="line-height:2;color:#444;">
      <li>Tôn trọng lẫn nhau, không tấn công cá nhân</li>
      <li>Bình luận có căn cứ, không phát tán tin giả</li>
      <li>Tranh luận dựa trên sự kiện, không cảm tính</li>
      <li>Ủng hộ tinh thần xây dựng cộng đồng lành mạnh</li>
      <li>Khuyến khích đóng góp nội dung có giá trị thực</li>
    </ul>

    {{-- Hệ thống cấp độ thành viên --}}
    <h3 style="font-size:17px;font-weight:800;color:var(--brand);margin-top:24px;">Hệ Thống Thành Viên & Quyền Lợi</h3>
    <p style="color:#555;margin-bottom:14px;">VNKR có 3 cấp độ thành viên — <strong>tất cả đều miễn phí</strong>, quyền lợi đạt được bằng đóng góp, không phải bằng tiền:</p>

    <div style="border:1px solid var(--border);border-radius:8px;overflow:hidden;margin-bottom:16px;">
      {{-- Cấp 1 --}}
      <div style="padding:16px 20px;border-bottom:1px solid var(--border);background:#fafbfc;">
        <div class="d-flex align-items-center gap-3 flex-wrap">
          <div style="background:var(--brand-light);border:2px solid var(--brand);border-radius:50%;width:44px;height:44px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="bi bi-person-fill" style="color:var(--brand);font-size:20px;"></i>
          </div>
          <div style="flex:1;">
            <div style="font-weight:800;color:var(--brand);font-size:15px;">Thành viên cơ bản <span style="font-size:12px;font-weight:600;background:var(--brand-light);color:var(--brand);padding:2px 8px;border-radius:3px;margin-left:4px;">Miễn phí — Đăng ký là có</span></div>
            <div style="font-size:13px;color:#555;margin-top:4px;line-height:1.7;">
              Đọc toàn bộ nội dung · Bình luận và tương tác · Nhận newsletter hàng tuần · Lưu bài yêu thích
            </div>
          </div>
        </div>
      </div>
      {{-- Cấp 2 --}}
      <div style="padding:16px 20px;border-bottom:1px solid var(--border);background:#fffef5;">
        <div class="d-flex align-items-center gap-3 flex-wrap">
          <div style="background:#fff9e6;border:2px solid #F0A500;border-radius:50%;width:44px;height:44px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="bi bi-star-fill" style="color:#F0A500;font-size:20px;"></i>
          </div>
          <div style="flex:1;">
            <div style="font-weight:800;color:#b8860b;font-size:15px;">Thành viên tích cực <span style="font-size:12px;font-weight:600;background:#fff9e6;color:#b8860b;padding:2px 8px;border-radius:3px;margin-left:4px;">Đạt được bằng đóng góp</span></div>
            <div style="font-size:13px;color:#666;margin-top:2px;margin-bottom:6px;">Điều kiện: Bình luận có giá trị >20 lần + được cộng đồng công nhận</div>
            <div style="font-size:13px;color:#555;line-height:1.8;">
              ✦ Badge "Thành viên tích cực" trên profile &nbsp;·&nbsp;
              ✦ Được mention trong bài "Cộng đồng nói gì" &nbsp;·&nbsp;
              ✦ Quyền đề xuất chủ đề cho PTB &nbsp;·&nbsp;
              ✦ Nhận bản tin nội bộ trước khi công bố
            </div>
          </div>
        </div>
      </div>
      {{-- Cấp 3 --}}
      <div style="padding:16px 20px;background:#f5fff8;">
        <div class="d-flex align-items-center gap-3 flex-wrap">
          <div style="background:#d4edda;border:2px solid #218838;border-radius:50%;width:44px;height:44px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="bi bi-pen-fill" style="color:#218838;font-size:20px;"></i>
          </div>
          <div style="flex:1;">
            <div style="font-weight:800;color:#218838;font-size:15px;">Cộng tác viên VNKR <span style="font-size:12px;font-weight:600;background:#d4edda;color:#218838;padding:2px 8px;border-radius:3px;margin-left:4px;">Được biên tập viên mời</span></div>
            <div style="font-size:13px;color:#666;margin-top:2px;margin-bottom:6px;">Điều kiện: Được Phạm Thế Bảo mời dựa trên chất lượng đóng góp</div>
            <div style="font-size:13px;color:#555;line-height:1.8;">
              ✦ Gửi bài viết để xét đăng chính thức &nbsp;·&nbsp;
              ✦ Tên tác giả & profile riêng trên VNKR &nbsp;·&nbsp;
              ✦ Tiếng nói quan trọng trong định hướng cộng đồng &nbsp;·&nbsp;
              ✦ Ưu tiên trong mọi chương trình mở rộng
            </div>
          </div>
        </div>
      </div>
    </div>

    <h3 style="font-size:17px;font-weight:800;color:var(--brand);margin-top:24px;">Xử Lý Vi Phạm</h3>
    <ul style="line-height:2;color:#444;">
      <li>Cảnh báo lần 1: Nhắc nhở</li>
      <li>Vi phạm lần 2: Xóa nội dung</li>
      <li>Vi phạm nghiêm trọng: Khoá tài khoản vĩnh viễn</li>
      <li>Vi phạm pháp luật: Báo cáo cơ quan chức năng</li>
    </ul>

    <div style="margin-top:24px;text-align:center;padding:20px;background:var(--brand-light);border-radius:6px;">
      <div style="font-size:15px;font-weight:800;color:var(--brand);margin-bottom:6px;">Tham gia VNKR — Hoàn toàn miễn phí</div>
      <div style="font-size:13px;color:#555;margin-bottom:14px;">Đóng góp xây dựng cộng đồng và nhận quyền lợi xứng đáng.</div>
      <a href="{{ route('register') }}" class="vnkr-btn vnkr-btn--brand vnkr-btn--sm me-2">
        <i class="bi bi-person-plus me-1"></i>Đăng ký ngay
      </a>
      <a href="{{ route('contact.show') }}" class="vnkr-btn vnkr-btn--ghost vnkr-btn--sm">
        <i class="bi bi-envelope me-1"></i>Liên hệ đóng góp
      </a>
    </div>
  </div>
</div>
@endsection
