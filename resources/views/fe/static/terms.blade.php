@extends('fe.index')
@section('title', 'Điều Khoản Sử Dụng — VNKR')
@section('main')
<div class="container" style="padding-top:24px;max-width:820px;">
  <div class="cat-block">
    <div class="cat-block-head"><h3>ĐIỀU KHOẢN SỬ DỤNG</h3></div>
    <p style="font-size:13px;color:var(--text-muted);margin-bottom:20px;"><i class="bi bi-calendar me-1"></i>Cập nhật lần cuối: {{ date('d/m/Y') }} | Áp dụng cho: VNKR (vnkr.vn)</p>

    {{-- Cam kết nổi bật --}}
    <div style="background:linear-gradient(135deg,#f0f7ff 0%,#fff9e6 100%);border:2px solid var(--brand);border-radius:8px;padding:20px 24px;margin-bottom:24px;">
      <div style="font-size:18px;font-weight:900;color:var(--brand);margin-bottom:10px;"><i class="bi bi-shield-check-fill me-2" style="color:var(--accent);"></i>Cam Kết Cốt Lõi Của VNKR</div>
      <div class="row g-2">
        <div class="col-md-6">
          <div style="font-size:14px;color:#333;line-height:1.9;">
            <div><i class="bi bi-check-circle-fill me-2" style="color:#27ae60;"></i><strong>Hoàn toàn miễn phí</strong> — không thu phí tham gia</div>
            <div><i class="bi bi-check-circle-fill me-2" style="color:#27ae60;"></i><strong>Không thu phí đọc bài</strong>, bình luận, đăng ký</div>
            <div><i class="bi bi-check-circle-fill me-2" style="color:#27ae60;"></i><strong>Không thu phí đóng góp</strong> bài viết, nội dung</div>
          </div>
        </div>
        <div class="col-md-6">
          <div style="font-size:14px;color:#333;line-height:1.9;">
            <div><i class="bi bi-check-circle-fill me-2" style="color:#27ae60;"></i>Cộng đồng xây dựng, cộng đồng hưởng lợi</div>
            <div><i class="bi bi-check-circle-fill me-2" style="color:#27ae60;"></i>Nhà đóng góp nhận quyền lợi xứng đáng</div>
            <div><i class="bi bi-check-circle-fill me-2" style="color:#27ae60;"></i>Minh bạch hoàn toàn về hoạt động</div>
          </div>
        </div>
      </div>
    </div>

    <h3 style="font-size:17px;font-weight:800;color:var(--brand);margin-top:20px;">1. Chấp Nhận Điều Khoản</h3>
    <p>Khi truy cập và sử dụng VNKR (vnkr.vn), bạn đồng ý với toàn bộ các điều khoản và điều kiện được nêu trong tài liệu này. Việc sử dụng VNKR là hoàn toàn miễn phí và tự nguyện.</p>

    <h3 style="font-size:17px;font-weight:800;color:var(--brand);margin-top:20px;">2. Tính Chất Của VNKR</h3>
    <p>VNKR là <strong>trang thông tin điện tử tổng hợp phi lợi nhuận</strong>, KHÔNG phải cơ quan báo chí.
    VNKR hoạt động theo tinh thần <strong>"cộng đồng phục vụ cộng đồng"</strong> — được xây dựng bởi cộng đồng,
    dành cho cộng đồng, và <strong>không thu bất kỳ khoản phí nào</strong> từ người tham gia.</p>

    <h3 style="font-size:17px;font-weight:800;color:var(--brand);margin-top:20px;">3. Quyền Lợi Người Dùng</h3>
    <p>Mọi người dùng VNKR đều được hưởng các quyền lợi sau <strong>hoàn toàn miễn phí</strong>:</p>
    <ul style="line-height:1.9;color:#444;">
      <li>Đọc toàn bộ nội dung trên VNKR không giới hạn</li>
      <li>Đăng ký tài khoản và tham gia bình luận</li>
      <li>Nhận bản tin email hàng tuần</li>
      <li>Lưu bài viết yêu thích và xem lịch sử đọc</li>
      <li>Đề xuất chủ đề và phản hồi nội dung</li>
    </ul>

    <h3 style="font-size:17px;font-weight:800;color:var(--brand);margin-top:20px;">4. Quyền Lợi Nhà Đóng Góp</h3>
    <p>Những người tích cực đóng góp xây dựng cộng đồng VNKR sẽ được hưởng quyền lợi đặc biệt:</p>
    <div class="vnkr-callout vnkr-callout--gold">
      <div class="vnkr-callout-title"><i class="bi bi-star-fill me-1"></i>Thành viên tích cực (Earned — không trả tiền):</div>
      <ul style="line-height:1.9;color:#444;margin:0;padding-left:20px;">
        <li>Badge "Thành viên tích cực TheKingBao" hiển thị trên profile</li>
        <li>Được mention trong bài "Cộng đồng nói gì" hàng tuần</li>
        <li>Quyền đề xuất chủ đề trực tiếp cho biên tập viên PTB</li>
        <li>Nhận bản tin nội bộ trước khi công bố rộng rãi</li>
      </ul>
    </div>
    <div class="vnkr-callout vnkr-callout--success">
      <div class="vnkr-callout-title"><i class="bi bi-pen-fill me-1"></i>Cộng tác viên (Được mời đóng góp bài):</div>
      <ul style="line-height:1.9;color:#444;margin:0;padding-left:20px;">
        <li>Gửi bài viết để xét đăng trên VNKR</li>
        <li>Tên tác giả và profile riêng trên trang web</li>
        <li>Ưu tiên tham gia các chương trình phát triển VNKR</li>
        <li>Tiếng nói quan trọng trong định hướng nội dung</li>
      </ul>
    </div>

    <h3 style="font-size:17px;font-weight:800;color:var(--brand);margin-top:20px;">5. Bản Quyền Nội Dung</h3>
    <ul style="line-height:1.9;color:#444;">
      <li>Nội dung gốc (từ nguồn báo chí) thuộc bản quyền của cơ quan báo chí tương ứng.</li>
      <li>Phần biên tập, bình luận, góc nhìn cá nhân thuộc quyền sở hữu của Phạm Thế Bảo / VNKR.</li>
      <li>Bài của cộng tác viên thuộc quyền tác giả gốc, VNKR được phép đăng tải.</li>
      <li>Trích dẫn ngắn (dưới 100 từ) kèm link về VNKR được cho phép.</li>
    </ul>

    <h3 style="font-size:17px;font-weight:800;color:var(--brand);margin-top:20px;">6. Quy Tắc Bình Luận</h3>
    <ul style="line-height:1.9;color:#444;">
      <li>Nghiêm cấm bình luận xúc phạm, kỳ thị, kích động bạo lực.</li>
      <li>Không đăng thông tin sai lệch, tin giả.</li>
      <li>Không quảng cáo, spam trong phần bình luận.</li>
      <li>Mọi bình luận có thể bị kiểm duyệt và xóa nếu vi phạm.</li>
      <li>Biên tập viên VNKR có quyền xóa, chỉnh sửa hoặc khoá tài khoản vi phạm.</li>
    </ul>

    <h3 style="font-size:17px;font-weight:800;color:var(--brand);margin-top:20px;">7. Giới Hạn Trách Nhiệm</h3>
    <p>VNKR không chịu trách nhiệm về tính chính xác tuyệt đối của thông tin tổng hợp. Mọi quyết định dựa trên thông tin từ VNKR là trách nhiệm của người dùng.</p>

    <h3 style="font-size:17px;font-weight:800;color:var(--brand);margin-top:20px;">8. Liên Hệ</h3>
    <p>Nếu có khiếu nại về bản quyền hoặc nội dung, vui lòng liên hệ: <a href="mailto:phamthebao@vnkr.vn" class="brand-color">phamthebao@vnkr.vn</a></p>

    <div style="margin-top:20px;text-align:center;padding:16px;background:var(--brand-light);border-radius:6px;">
      <div style="font-size:14px;font-weight:700;color:var(--brand);margin-bottom:8px;">Tham gia cộng đồng VNKR ngay hôm nay — Miễn phí mãi mãi</div>
      <a href="{{ route('register') }}" class="vnkr-btn vnkr-btn--primary vnkr-btn--sm">
        <i class="bi bi-person-plus me-1"></i>Đăng ký tài khoản miễn phí
      </a>
    </div>
  </div>
</div>
@endsection
