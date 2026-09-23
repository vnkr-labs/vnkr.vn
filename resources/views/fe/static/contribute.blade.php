@extends('fe.index')
@section('title', 'Đóng Góp Mã Nguồn — VNKR Cộng Đồng')
@section('meta_description', 'VNKR là dự án mã nguồn mở cộng đồng. Hãy tham gia đóng góp code, báo lỗi, đề xuất tính năng để cùng xây dựng nền tảng thông tin tốt hơn cho mọi người.')
@section('canonical', url('/contribute'))

@section('main')
<div class="container" style="padding-top:24px;max-width:900px;">

  {{-- Hero --}}
  <div class="cat-block" style="border-top:4px solid var(--accent);text-align:center;padding:32px 24px;">
    <div style="font-size:48px;font-weight:900;color:var(--brand);letter-spacing:-2px;border-bottom:4px solid var(--accent);display:inline-block;padding:6px 20px;border-radius:6px;margin-bottom:14px;">VNKR</div>
    <h1 style="font-size:22px;font-weight:800;color:var(--text);margin-bottom:10px;">
      Đóng Góp Mã Nguồn Cho Cộng Đồng
    </h1>
    <p style="font-size:15px;color:var(--text-muted);max-width:620px;margin:0 auto 16px;">
      VNKR được xây dựng với tinh thần <strong>mã nguồn mở</strong> — mọi người đều có thể
      đóng góp để làm cho nền tảng này tốt hơn, dành cho cộng đồng.
    </p>
    <div class="d-flex gap-2 justify-content-center flex-wrap">
      <a href="https://github.com/thekingbao/vnkr.vn" target="_blank" rel="noopener" class="vnkr-btn vnkr-btn--brand vnkr-btn--sm" style="background:#24292e;color:#fff;">
        <i class="bi bi-github"></i> GitHub Repository
      </a>
      <a href="{{ route('contact.show') }}" class="vnkr-btn vnkr-btn--brand vnkr-btn--sm">
        <i class="bi bi-envelope"></i> Liên hệ trực tiếp
      </a>
    </div>
  </div>

  {{-- Tại sao đóng góp --}}
  <div class="cat-block mt-3">
    <div class="cat-block-head"><h3>TẠI SAO ĐÓNG GÓP?</h3></div>
    <div class="row g-3 mt-1">
      <div class="col-md-4">
        <div class="fe-feature-card fe-feature-card--brand">
          <i class="bi bi-people-fill fe-feature-card-icon"></i>
          <div class="fe-feature-card-title" style="color:var(--brand);">Phục vụ cộng đồng</div>
          <div class="fe-feature-card-desc">Code bạn đóng góp sẽ trực tiếp cải thiện trải nghiệm của <strong>hàng nghìn</strong> người đọc tin tức mỗi ngày.</div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="fe-feature-card fe-feature-card--gold">
          <i class="bi bi-award-fill fe-feature-card-icon" style="color:var(--gold);"></i>
          <div class="fe-feature-card-title" style="color:#b8860b;">Được ghi nhận</div>
          <div class="fe-feature-card-desc">Tên bạn sẽ được ghi vào danh sách <strong>Contributors</strong> và hiển thị công khai trên trang này.</div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="fe-feature-card fe-feature-card--accent">
          <i class="bi bi-code-slash fe-feature-card-icon" style="color:var(--accent);"></i>
          <div class="fe-feature-card-title" style="color:var(--accent);">Học & thực chiến</div>
          <div class="fe-feature-card-desc">Làm việc với Laravel, PHP, JavaScript thực tế — portfolio xịn cho CV của bạn.</div>
        </div>
      </div>
    </div>
  </div>

  {{-- Tech stack --}}
  <div class="cat-block mt-3">
    <div class="cat-block-head"><h3>CÔNG NGHỆ SỬ DỤNG</h3></div>
    <div class="row g-2 mt-1">
      @php
      $stack = [
        ['name'=>'Laravel 10',      'icon'=>'bi-box-seam',        'color'=>'#FF2D20', 'desc'=>'PHP Framework chính'],
        ['name'=>'PHP 8.2',         'icon'=>'bi-filetype-php',    'color'=>'#777BB4', 'desc'=>'Backend language'],
        ['name'=>'MySQL',           'icon'=>'bi-database-fill',   'color'=>'#00618A', 'desc'=>'Cơ sở dữ liệu'],
        ['name'=>'Blade Templates', 'icon'=>'bi-file-code',       'color'=>'#0A3D62', 'desc'=>'Template engine'],
        ['name'=>'Bootstrap 5',     'icon'=>'bi-bootstrap-fill',  'color'=>'#7952B3', 'desc'=>'CSS Framework'],
        ['name'=>'Vite + JS',       'icon'=>'bi-lightning-fill',  'color'=>'#F0A500', 'desc'=>'Asset bundler'],
        ['name'=>'Redis',           'icon'=>'bi-cpu-fill',        'color'=>'#DC382D', 'desc'=>'Caching layer'],
        ['name'=>'REST API',        'icon'=>'bi-braces',          'color'=>'#27ae60', 'desc'=>'API /api/v1/'],
      ];
      @endphp
      @foreach($stack as $tech)
      <div class="col-6 col-md-3">
        <div style="padding:12px 14px;border:1px solid var(--border);border-radius:6px;border-left:4px solid {{ $tech['color'] }};">
          <div style="font-weight:700;font-size:13.5px;color:{{ $tech['color'] }};">
            <i class="bi {{ $tech['icon'] }} me-1"></i>{{ $tech['name'] }}
          </div>
          <div style="font-size:12px;color:#777;margin-top:2px;">{{ $tech['desc'] }}</div>
        </div>
      </div>
      @endforeach
    </div>
  </div>

  {{-- Cách đóng góp --}}
  <div class="cat-block mt-3" style="border-top-color:var(--gold);">
    <div class="cat-block-head" style="border-bottom-color:var(--gold);">
      <h3 style="color:#b8860b;">CÁCH THAM GIA ĐÓNG GÓP</h3>
    </div>

    {{-- Bước 1 --}}
    <div class="fe-step-item">
      <div class="fe-step-num">1</div>
      <div>
        <div class="fe-step-title">Fork & Clone Repository</div>
        <div class="fe-step-code">
          git clone https://github.com/thekingbao/vnkr.vn.git<br>
          cd vnkr.vn<br>
          cp .env.example .env<br>
          composer install &amp;&amp; npm install
        </div>
      </div>
    </div>

    {{-- Bước 2 --}}
    <div class="fe-step-item">
      <div class="fe-step-num">2</div>
      <div>
        <div class="fe-step-title">Tạo Branch Mới</div>
        <div class="fe-step-code">
          git checkout -b feature/ten-tinh-nang<br>
          <span style="color:#888;"># hoặc: bugfix/mo-ta-loi</span>
        </div>
      </div>
    </div>

    {{-- Bước 3 --}}
    <div class="fe-step-item">
      <div class="fe-step-num">3</div>
      <div>
        <div class="fe-step-title">Viết Code & Commit</div>
        <div class="fe-step-code">
          git add .<br>
          git commit -m "feat: mô tả ngắn gọn thay đổi"<br>
          <span style="color:#888;"># Dùng Conventional Commits: feat/fix/docs/refactor</span>
        </div>
      </div>
    </div>

    {{-- Bước 4 --}}
    <div class="fe-step-item">
      <div class="fe-step-num">4</div>
      <div>
        <div class="fe-step-title">Tạo Pull Request</div>
        <div style="font-size:13.5px;color:#444;line-height:1.7;">
          Push branch lên GitHub và tạo Pull Request về nhánh <code style="background:#f4f6f8;padding:1px 5px;border-radius:3px;">main</code>.
          Mô tả rõ những gì bạn thay đổi và lý do. PTB sẽ review và merge trong vòng <strong>72 giờ</strong>.
        </div>
      </div>
    </div>
  </div>

  {{-- Những gì cần đóng góp --}}
  <div class="cat-block mt-3">
    <div class="cat-block-head"><h3>NHỮNG GÌ ĐANG CẦN ĐÓNG GÓP</h3></div>
    <div class="row g-3 mt-1">
      <div class="col-md-6">
        <div class="vnkr-callout vnkr-callout--success">
          <div class="vnkr-callout-title"><i class="bi bi-bug-fill me-2"></i>Báo lỗi (Bug Reports)</div>
          <ul style="font-size:13px;color:#444;line-height:1.9;margin:0;padding-left:18px;">
            <li>Lỗi hiển thị trên mobile</li>
            <li>Lỗi logic xử lý bình luận</li>
            <li>Vấn đề SEO meta tags</li>
            <li>Bất kỳ lỗi nào bạn gặp</li>
          </ul>
        </div>
      </div>
      <div class="col-md-6">
        <div class="vnkr-callout vnkr-callout--info">
          <div class="vnkr-callout-title"><i class="bi bi-stars me-2"></i>Tính năng mới (Features)</div>
          <ul style="font-size:13px;color:#444;line-height:1.9;margin:0;padding-left:18px;">
            <li>Hệ thống thông báo (notifications)</li>
            <li>Dark mode</li>
            <li>Tính năng tìm kiếm nâng cao</li>
            <li>PWA / offline support</li>
          </ul>
        </div>
      </div>
      <div class="col-md-6">
        <div class="vnkr-callout vnkr-callout--warn">
          <div class="vnkr-callout-title"><i class="bi bi-file-text me-2"></i>Tài liệu (Documentation)</div>
          <ul style="font-size:13px;color:#444;line-height:1.9;margin:0;padding-left:18px;">
            <li>Viết hướng dẫn cài đặt</li>
            <li>Dịch tài liệu kỹ thuật</li>
            <li>Viết wiki cho developer</li>
            <li>Code comments & docblocks</li>
          </ul>
        </div>
      </div>
      <div class="col-md-6">
        <div class="vnkr-callout vnkr-callout--gold">
          <div class="vnkr-callout-title"><i class="bi bi-palette me-2"></i>Giao diện (UI/UX)</div>
          <ul style="font-size:13px;color:#444;line-height:1.9;margin:0;padding-left:18px;">
            <li>Cải thiện responsive design</li>
            <li>Tối ưu Core Web Vitals</li>
            <li>Thiết kế component mới</li>
            <li>Accessibility (a11y)</li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  {{-- Quy tắc đóng góp --}}
  <div class="cat-block mt-3" style="background:#f8fafe;">
    <div class="cat-block-head"><h3>QUY TẮC ĐÓNG GÓP</h3></div>
    <div class="row g-3 mt-1">
      <div class="col-md-6">
        <div style="font-size:13.5px;color:#333;line-height:1.9;">
          <div class="mb-1"><i class="bi bi-check-circle-fill me-2" style="color:#27ae60;"></i>Viết code sạch, có comment rõ ràng</div>
          <div class="mb-1"><i class="bi bi-check-circle-fill me-2" style="color:#27ae60;"></i>Tuân theo PSR-12 (PHP) và codebase hiện tại</div>
          <div class="mb-1"><i class="bi bi-check-circle-fill me-2" style="color:#27ae60;"></i>Một PR giải quyết một vấn đề duy nhất</div>
          <div class="mb-1"><i class="bi bi-check-circle-fill me-2" style="color:#27ae60;"></i>Test thủ công trước khi tạo PR</div>
        </div>
      </div>
      <div class="col-md-6">
        <div style="font-size:13.5px;color:#333;line-height:1.9;">
          <div class="mb-1"><i class="bi bi-x-circle-fill me-2" style="color:#dc3545;"></i>Không break tính năng đang hoạt động</div>
          <div class="mb-1"><i class="bi bi-x-circle-fill me-2" style="color:#dc3545;"></i>Không commit file .env hoặc credentials</div>
          <div class="mb-1"><i class="bi bi-x-circle-fill me-2" style="color:#dc3545;"></i>Không đưa vào ads, tracking, spyware</div>
          <div class="mb-1"><i class="bi bi-x-circle-fill me-2" style="color:#dc3545;"></i>Không thêm dependency không cần thiết</div>
        </div>
      </div>
    </div>
  </div>

  {{-- CTA cuối --}}
  <div class="cat-block mt-3" style="text-align:center;background:linear-gradient(135deg,var(--brand-light),#fff9e6);">
    <div style="font-size:20px;font-weight:900;color:var(--brand);margin-bottom:8px;">
      <i class="bi bi-github me-2"></i>Sẵn sàng đóng góp?
    </div>
    <p style="font-size:14px;color:#555;margin-bottom:16px;max-width:500px;margin-left:auto;margin-right:auto;">
      Mỗi dòng code bạn đóng góp là một viên gạch xây dựng cộng đồng VNKR tốt hơn.
      Cùng nhau tạo ra nền tảng thông tin mà mọi người đều xứng đáng có.
    </p>
    <div class="d-flex gap-2 justify-content-center flex-wrap">
      <a href="https://github.com/thekingbao/vnkr.vn" target="_blank" rel="noopener" class="vnkr-btn vnkr-btn--sm" style="background:#24292e;color:#fff;">
        <i class="bi bi-github me-1"></i>Xem trên GitHub
      </a>
      <a href="{{ route('contact.show') }}" class="vnkr-btn vnkr-btn--ghost vnkr-btn--sm">
        <i class="bi bi-envelope me-1"></i>Liên hệ PTB
      </a>
    </div>
    <div style="margin-top:14px;font-size:12px;color:#888;">
      <i class="bi bi-star-fill me-1" style="color:#F0A500;"></i>
      Nhà đóng góp code sẽ nhận badge <strong>"Contributor VNKR"</strong> và được ghi nhận vĩnh viễn.
    </div>
  </div>

</div>
@endsection
