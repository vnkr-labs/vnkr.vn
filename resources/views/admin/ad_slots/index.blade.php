@extends('admin.master')
@section('title', 'Thông Báo Cộng Đồng')
@section('title-page', 'Quản Lý Thông Báo Cộng Đồng')

@section('main-content')
<section class="container mt-4">

  @if(session('success'))
  <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
  @endif

  {{-- Nhắc nhở phi thương mại --}}
  <div class="alert" style="background:#fff8e1;border:1px solid #ffe082;border-left:4px solid #F0A500;border-radius:4px;font-size:13px;margin-bottom:16px;">
    <i class="bi bi-people-fill me-2" style="color:#F0A500;"></i>
    <strong>Lưu ý:</strong> Các vị trí này <strong>chỉ dùng cho thông báo cộng đồng</strong> — thông báo sự kiện,
    banner phi lợi nhuận, lời kêu gọi đóng góp, v.v. <strong>Không dùng cho quảng cáo thương mại.</strong>
    VNKR là nền tảng hoàn toàn phi thương mại.
  </div>

  <div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h3 class="card-title mb-0"><i class="bi bi-megaphone me-1"></i>Vị Trí Thông Báo Cộng Đồng</h3>
      <span class="badge bg-light text-dark">{{ $slots->where('is_active', true)->count() }}/{{ $slots->count() }} đang bật</span>
    </div>
    <div class="card-body p-0">
      <table class="table table-hover mb-0">
        <thead class="table-light">
          <tr>
            <th style="width:180px;">Vị trí</th>
            <th>Tên hiển thị</th>
            <th style="width:120px;">Trạng thái</th>
            <th style="width:100px;">Code</th>
            <th style="width:140px;">Thao tác</th>
          </tr>
        </thead>
        <tbody>
          @foreach($slots as $slot)
          <tr>
            <td><code style="font-size:12px;color:#0A3D62;">{{ $slot->position }}</code></td>
            <td style="font-size:13.5px;">{{ $slot->label }}</td>
            <td>
              @if($slot->is_active)
                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Đang bật</span>
              @else
                <span class="badge bg-secondary">Tắt</span>
              @endif
            </td>
            <td>
              @if($slot->code)
                <span class="badge bg-info text-dark"><i class="bi bi-code me-1"></i>Có code</span>
              @else
                <span class="text-muted" style="font-size:12px;">Trống</span>
              @endif
            </td>
            <td>
              <a href="{{ route('ad_slots.edit', $slot) }}" class="btn btn-xs btn-primary">
                <i class="bi bi-pencil"></i> Sửa
              </a>
              <form method="POST" action="{{ route('ad_slots.toggle', $slot) }}" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-xs {{ $slot->is_active ? 'btn-warning' : 'btn-success' }}">
                  {{ $slot->is_active ? 'Tắt' : 'Bật' }}
                </button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <div class="alert alert-info mt-3" style="font-size:13px;">
    <i class="bi bi-info-circle me-1"></i>
    <strong>Hướng dẫn:</strong> Dán banner HTML thông báo cộng đồng — ví dụ: lời kêu gọi tham gia,
    thông báo sự kiện, banner cảm ơn nhà đóng góp, v.v. Bật/tắt từng vị trí mà không cần xóa code.
    Code được cache 10 phút. <strong>Tuyệt đối không dán mã quảng cáo thương mại (AdSense, v.v.)</strong>
  </div>

</section>
@endsection
