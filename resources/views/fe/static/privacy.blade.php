@extends('fe.index')
@section('title', 'Chính Sách Bảo Mật — VNKR')
@section('main')
<div class="container" style="padding-top:24px;max-width:820px;">
  <div class="cat-block">
    <div class="cat-block-head"><h3>CHÍNH SÁCH BẢO MẬT</h3></div>
    <p style="font-size:13px;color:var(--text-muted);margin-bottom:20px;"><i class="bi bi-calendar me-1"></i>Cập nhật: {{ date('d/m/Y') }}</p>

    <div class="vnkr-callout vnkr-callout--info">
      <i class="bi bi-shield-check-fill me-2"></i>
      <strong>Cam kết:</strong> VNKR hoạt động phi lợi nhuận, <strong>không thu phí</strong> từ người dùng
      và <strong>không bán dữ liệu cá nhân</strong> cho bất kỳ bên nào.
    </div>

    <h3 style="font-size:17px;font-weight:800;color:var(--brand);margin-top:20px;">1. Thông Tin Thu Thập</h3>
    <p>VNKR chỉ thu thập thông tin tối thiểu cần thiết để cung cấp dịch vụ cộng đồng miễn phí:</p>
    <ul style="line-height:1.9;color:#444;">
      <li><strong>Tài khoản:</strong> Tên, email khi đăng ký</li>
      <li><strong>Bình luận:</strong> Nội dung bình luận, thời gian</li>
      <li><strong>Liên hệ:</strong> Email, nội dung tin nhắn từ form liên hệ</li>
      <li><strong>Analytics:</strong> Dữ liệu truy cập ẩn danh qua Google Analytics</li>
    </ul>

    <h3 style="font-size:17px;font-weight:800;color:var(--brand);margin-top:20px;">2. Mục Đích Sử Dụng</h3>
    <ul style="line-height:1.9;color:#444;">
      <li>Cung cấp dịch vụ bình luận, tương tác cộng đồng</li>
      <li>Gửi bản tin (nếu đăng ký newsletter)</li>
      <li>Cải thiện trải nghiệm người dùng</li>
      <li>Phòng chống spam và vi phạm</li>
    </ul>

    <h3 style="font-size:17px;font-weight:800;color:var(--brand);margin-top:20px;">3. Bảo Vệ Dữ Liệu</h3>
    <ul style="line-height:1.9;color:#444;">
      <li>Dữ liệu được mã hóa và bảo vệ qua HTTPS</li>
      <li><strong>Không bán, không chia sẻ</strong> dữ liệu cá nhân cho bất kỳ bên thứ ba nào</li>
      <li>Không dùng dữ liệu người dùng cho mục đích thương mại</li>
      <li>Người dùng có quyền yêu cầu xóa tài khoản và toàn bộ dữ liệu bất kỳ lúc nào</li>
    </ul>

    <h3 style="font-size:17px;font-weight:800;color:var(--brand);margin-top:20px;">4. Cookie</h3>
    <p>VNKR sử dụng cookie để duy trì phiên đăng nhập và phân tích lưu lượng (Google Analytics). Bạn có thể tắt cookie trong trình duyệt nhưng một số tính năng có thể bị ảnh hưởng.</p>

    <h3 style="font-size:17px;font-weight:800;color:var(--brand);margin-top:20px;">5. Liên Hệ</h3>
    <p>Để yêu cầu xóa dữ liệu hoặc thắc mắc về bảo mật: <a href="mailto:phamthebao@vnkr.vn" class="brand-color">phamthebao@vnkr.vn</a></p>
  </div>
</div>
@endsection
