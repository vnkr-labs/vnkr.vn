@extends('admin.master')
@section('title', 'Analytics Dashboard')

@section('main-content')
<div class="container-fluid">

{{-- ===== VIEWS CHART (14 ngày) ===== --}}
<div class="row g-3 mb-3">
  <div class="col-lg-8">
    <div class="box">
      <div class="box-header d-flex justify-content-between align-items-center">
        <h3 class="box-title"><i class="bi bi-graph-up me-1" style="color:#0A3D62;"></i>Lượt Xem & Bài Đăng — 14 Ngày Qua</h3>
      </div>
      <div class="box-body" style="padding:12px 16px;">
        <canvas id="viewsChart" height="80"></canvas>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    {{-- NEWSLETTER STATS --}}
    <div class="box h-100">
      <div class="box-header"><h3 class="box-title"><i class="bi bi-envelope-check me-1"></i>Newsletter</h3></div>
      <div class="box-body">
        @php $convRate = $newsletterTotal > 0 ? round($newsletterConfirmed / $newsletterTotal * 100) : 0; @endphp
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:12px;">
          <div style="background:#f0f9ff;border-radius:6px;padding:12px;text-align:center;">
            <div style="font-size:22px;font-weight:900;color:#0A3D62;">{{ number_format($newsletterTotal) }}</div>
            <div style="font-size:11.5px;color:#555;">Tổng đăng ký</div>
          </div>
          <div style="background:#f0fdf4;border-radius:6px;padding:12px;text-align:center;">
            <div style="font-size:22px;font-weight:900;color:#16a34a;">{{ number_format($newsletterConfirmed) }}</div>
            <div style="font-size:11.5px;color:#555;">Đã xác nhận</div>
          </div>
          <div style="background:#fff7ed;border-radius:6px;padding:12px;text-align:center;">
            <div style="font-size:22px;font-weight:900;color:#ea580c;">{{ number_format($newsletterUnsub) }}</div>
            <div style="font-size:11.5px;color:#555;">Hủy đăng ký</div>
          </div>
          <div style="background:#fefce8;border-radius:6px;padding:12px;text-align:center;">
            <div style="font-size:22px;font-weight:900;color:#ca8a04;">+{{ number_format($newsletterThisWeek) }}</div>
            <div style="font-size:11.5px;color:#555;">7 ngày qua</div>
          </div>
        </div>
        <div style="font-size:12.5px;color:#555;margin-bottom:4px;font-weight:700;">
          Tỷ lệ xác nhận: {{ $convRate }}%
        </div>
        <div style="height:8px;background:#e5e7eb;border-radius:4px;">
          <div style="height:100%;background:#16a34a;border-radius:4px;width:{{ $convRate }}%;transition:width .4s;"></div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- ===== METRIC CARDS ===== --}}
<div class="row g-3 mb-4">
  @php
  $cards = [
    ['label'=>'Bài viết',    'value'=>$productCount,    'icon'=>'bi-newspaper',     'color'=>'shade-blue',   'route'=>'product.index'],
    ['label'=>'Danh mục',    'value'=>$categoryCount,   'icon'=>'bi-grid',          'color'=>'shade-yellow', 'route'=>'category.index'],
    ['label'=>'Tài khoản',   'value'=>$userCount,       'icon'=>'bi-people-fill',   'color'=>'shade-red',    'route'=>'user.index'],
    ['label'=>'Bình luận',   'value'=>$commentCount,    'icon'=>'bi-chat-dots',     'color'=>'shade-green',  'route'=>'product.index'],
    ['label'=>'Tags',        'value'=>$tagCount,        'icon'=>'bi-tags',          'color'=>'shade-yellow', 'route'=>'product.index'],
    ['label'=>'Newsletter',  'value'=>$newsletterCount, 'icon'=>'bi-envelope-check','color'=>'shade-blue',   'route'=>'product.index'],
    ['label'=>'Liên hệ',     'value'=>$contactCount,    'icon'=>'bi-headset',       'color'=>'shade-red',    'route'=>'contact.index'],
  ];
  @endphp
  @foreach($cards as $card)
  <div class="col-xl-3 col-sm-6 col-12">
    <div class="stats-tile">
      <div class="sale-icon {{ $card['color'] }}"><i class="bi {{ $card['icon'] }}"></i></div>
      <div class="sale-details">
        <h3>{{ number_format($card['value']) }}</h3>
        <p><a href="{{ route($card['route']) }}" class="small-box-footer">{{ $card['label'] }} <i class="fa fa-arrow-circle-right"></i></a></p>
      </div>
    </div>
  </div>
  @endforeach
</div>

{{-- ===== GITHUB/DOCS FEATURES PANEL ===== --}}
<div class="row g-3 mb-4">
  {{-- Events --}}
  <div class="col-md-4">
    <div class="box" style="border-top:3px solid #3b82d4;">
      <div class="box-header">
        <h3 class="box-title"><i class="bi bi-activity me-1" style="color:#3b82d4;"></i>Events (Observability)</h3>
      </div>
      <div class="box-body" style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
        <div style="background:#eff6ff;border-radius:6px;padding:12px;text-align:center;">
          <div style="font-size:22px;font-weight:900;color:#1d4ed8;">{{ number_format($eventToday) }}</div>
          <div style="font-size:11.5px;color:#555;">Hôm nay</div>
        </div>
        <div style="background:#dbeafe;border-radius:6px;padding:12px;text-align:center;">
          <div style="font-size:22px;font-weight:900;color:#2563eb;">{{ number_format($eventThisWeek) }}</div>
          <div style="font-size:11.5px;color:#555;">7 ngày qua</div>
        </div>
      </div>
      <div class="box-footer" style="font-size:12px;color:#888;padding:6px 12px;">
        page_view + search — tự động ghi từ frontend
      </div>
    </div>
  </div>

  {{-- Journeys + Reusables + Redirects --}}
  <div class="col-md-4">
    <div class="box" style="border-top:3px solid #27ae60;">
      <div class="box-header">
        <h3 class="box-title"><i class="bi bi-puzzle me-1" style="color:#27ae60;"></i>Content Tools</h3>
      </div>
      <div class="box-body" style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px;">
        <a href="{{ route('admin.journeys.index') }}" style="text-decoration:none;">
          <div style="background:#f0fdf4;border-radius:6px;padding:10px;text-align:center;">
            <div style="font-size:20px;font-weight:900;color:#15803d;">{{ $journeyCount }}</div>
            <div style="font-size:11px;color:#555;">Journeys</div>
          </div>
        </a>
        <a href="{{ route('admin.reusables.index') }}" style="text-decoration:none;">
          <div style="background:#f5f0ff;border-radius:6px;padding:10px;text-align:center;">
            <div style="font-size:20px;font-weight:900;color:#7c3aed;">{{ $reusableCount }}</div>
            <div style="font-size:11px;color:#555;">Reusables</div>
          </div>
        </a>
        <a href="{{ route('admin.redirects.index') }}" style="text-decoration:none;">
          <div style="background:#fdf4ff;border-radius:6px;padding:10px;text-align:center;">
            <div style="font-size:20px;font-weight:900;color:#a21caf;">{{ $redirectCount }}</div>
            <div style="font-size:11px;color:#555;">Redirects</div>
          </div>
        </a>
      </div>
      <div class="box-footer" style="font-size:12px;color:#888;padding:6px 12px;">
        Tính năng lấy ý tưởng từ github/docs
      </div>
    </div>
  </div>

  {{-- Staged Publishing status --}}
  <div class="col-md-4">
    <div class="box" style="border-top:3px solid #f59e0b;">
      <div class="box-header">
        <h3 class="box-title"><i class="bi bi-layers me-1" style="color:#f59e0b;"></i>Staged Publishing</h3>
      </div>
      <div class="box-body" style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
        <a href="{{ route('product.index') }}?status=draft" style="text-decoration:none;">
          <div style="background:#f8fafc;border-radius:6px;padding:12px;text-align:center;border:1px solid #e2e8f0;">
            <div style="font-size:22px;font-weight:900;color:#64748b;">{{ $draftCount }}</div>
            <div style="font-size:11.5px;color:#555;">📝 Draft</div>
          </div>
        </a>
        <a href="{{ route('product.index') }}?status=review" style="text-decoration:none;">
          <div style="background:#fefce8;border-radius:6px;padding:12px;text-align:center;border:1px solid #fef08a;">
            <div style="font-size:22px;font-weight:900;color:#ca8a04;">{{ $reviewCount }}</div>
            <div style="font-size:11.5px;color:#555;">🔍 Review</div>
          </div>
        </a>
      </div>
      <div class="box-footer" style="font-size:12px;color:#888;padding:6px 12px;">
        @if($draftCount + $reviewCount > 0)
          <span style="color:#dc2626;font-weight:700;">⚠ {{ $draftCount + $reviewCount }} bài chờ xử lý</span>
        @else
          <span style="color:#16a34a;">✅ Không có bài đang chờ</span>
        @endif
      </div>
    </div>
  </div>
</div>

<div class="row g-3">

  {{-- ===== TOP ARTICLES TABS ===== --}}
  <div class="col-lg-8">
    <div class="box">
      <div class="box-header">
        <h3 class="box-title"><i class="bi bi-fire me-1" style="color:#E84118;"></i>Top Bài Được Đọc Nhiều Nhất</h3>
        <div class="box-tools pull-right" style="display:flex;gap:6px;">
          <button class="btn btn-xs {{ request()->is('admin') ? 'btn-primary' : 'btn-default' }}"
                  onclick="showTab('tab-24h',this)">24h</button>
          <button class="btn btn-xs btn-default" onclick="showTab('tab-7d',this)">7 ngày</button>
          <button class="btn btn-xs btn-default" onclick="showTab('tab-30d',this)">Mọi thời gian</button>
        </div>
      </div>
      <div class="box-body p-0">

        {{-- 24h --}}
        <div id="tab-24h" class="top-tab">
          @if($top24h->isEmpty())
          <div class="text-center py-4 text-muted" style="font-size:13px;">Chưa có bài viết nào trong 24h qua.</div>
          @else
          <table class="table table-hover table-sm mb-0">
            <thead><tr><th>#</th><th>Tiêu đề</th><th>Chuyên mục</th><th style="text-align:right;">Views</th><th style="text-align:right;">Likes</th></tr></thead>
            <tbody>
              @foreach($top24h as $i => $art)
              <tr>
                <td style="width:28px;font-weight:700;color:{{ $i<3?'#E84118':'#888' }}">{{ $i+1 }}</td>
                <td><a href="{{ route('detail', $art->slug) }}" target="_blank" style="font-size:13px;">{{ Str::limit($art->name,55) }}</a></td>
                <td><span style="font-size:11.5px;background:#e8f2fa;color:#0A3D62;padding:2px 6px;border-radius:3px;">{{ $art->category->name ?? '—' }}</span></td>
                <td style="text-align:right;font-weight:700;">{{ number_format($art->view_count) }}</td>
                <td style="text-align:right;">{{ number_format($art->like_count) }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
          @endif
        </div>

        {{-- 7 ngày --}}
        <div id="tab-7d" class="top-tab" style="display:none;">
          @if($top7d->isEmpty())
          <div class="text-center py-4 text-muted">Chưa có dữ liệu.</div>
          @else
          <table class="table table-hover table-sm mb-0">
            <thead><tr><th>#</th><th>Tiêu đề</th><th>Chuyên mục</th><th style="text-align:right;">Views</th><th style="text-align:right;">Likes</th></tr></thead>
            <tbody>
              @foreach($top7d as $i => $art)
              <tr>
                <td style="width:28px;font-weight:700;color:{{ $i<3?'#E84118':'#888' }}">{{ $i+1 }}</td>
                <td><a href="{{ route('detail', $art->slug) }}" target="_blank" style="font-size:13px;">{{ Str::limit($art->name,55) }}</a></td>
                <td><span style="font-size:11.5px;background:#e8f2fa;color:#0A3D62;padding:2px 6px;border-radius:3px;">{{ $art->category->name ?? '—' }}</span></td>
                <td style="text-align:right;font-weight:700;">{{ number_format($art->view_count) }}</td>
                <td style="text-align:right;">{{ number_format($art->like_count) }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
          @endif
        </div>

        {{-- Mọi thời gian --}}
        <div id="tab-30d" class="top-tab" style="display:none;">
          <table class="table table-hover table-sm mb-0">
            <thead><tr><th>#</th><th>Tiêu đề</th><th style="text-align:right;">Views</th><th style="text-align:right;">Likes</th></tr></thead>
            <tbody>
              @foreach($top30d as $i => $art)
              <tr>
                <td style="width:28px;font-weight:700;color:{{ $i<3?'#E84118':'#888' }}">{{ $i+1 }}</td>
                <td><a href="{{ route('detail', $art->slug) }}" target="_blank" style="font-size:13px;">{{ Str::limit($art->name,55) }}</a></td>
                <td style="text-align:right;font-weight:700;">{{ number_format($art->view_count) }}</td>
                <td style="text-align:right;">{{ number_format($art->like_count) }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>

      </div>
    </div>

    {{-- BÀI MỚI NHẤT --}}
    <div class="box mt-3">
      <div class="box-header">
        <h3 class="box-title"><i class="bi bi-clock me-1"></i>Bài Viết Mới Nhất</h3>
        <div class="box-tools pull-right">
          <a href="{{ route('product.create') }}" class="btn btn-xs btn-success"><i class="fa fa-plus me-1"></i>Thêm mới</a>
        </div>
      </div>
      <div class="box-body p-0">
        <table class="table table-hover table-sm mb-0">
          <thead><tr><th>Tiêu đề</th><th>Chuyên mục</th><th style="text-align:right;">Views</th><th>Ngày đăng</th></tr></thead>
          <tbody>
            @foreach($recentArticles as $art)
            <tr>
              <td><a href="{{ route('product.edit', $art->id) }}" style="font-size:13px;">{{ Str::limit($art->name, 50) }}</a></td>
              <td><span style="font-size:11.5px;background:#f4f6f8;padding:2px 6px;border-radius:3px;">{{ $art->category->name ?? '—' }}</span></td>
              <td style="text-align:right;">{{ number_format($art->view_count) }}</td>
              <td style="font-size:12px;color:#888;">{{ $art->created_at->format('d/m H:i') }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- ===== SIDEBAR ===== --}}
  <div class="col-lg-4">

    {{-- TOP CATEGORIES (tabs: số bài / lượt xem) --}}
    <div class="box">
      <div class="box-header d-flex justify-content-between align-items-center">
        <h3 class="box-title"><i class="bi bi-bar-chart me-1"></i>Chuyên Mục</h3>
        <div style="display:flex;gap:4px;">
          <button class="btn btn-xs btn-primary" onclick="showCatTab('cat-posts',this)">Bài viết</button>
          <button class="btn btn-xs btn-default" onclick="showCatTab('cat-views',this)">Lượt xem</button>
        </div>
      </div>
      <div class="box-body">
        <div id="cat-posts">
          @php $maxCount = $topCategories->max('products_count') ?: 1; @endphp
          @foreach($topCategories as $cat)
          <div class="mb-2">
            <div class="d-flex justify-content-between" style="font-size:13px;margin-bottom:3px;">
              <a href="{{ $cat->slug ? route('category.slug', $cat->slug) : route('result', $cat->id) }}" target="_blank" style="color:inherit;">{{ $cat->name }}</a>
              <strong>{{ $cat->products_count }} bài</strong>
            </div>
            <div style="height:6px;background:#eee;border-radius:3px;">
              <div style="height:100%;background:#0A3D62;border-radius:3px;width:{{ round($cat->products_count / $maxCount * 100) }}%"></div>
            </div>
          </div>
          @endforeach
        </div>
        <div id="cat-views" style="display:none;">
          @php $maxViews = $topCatsByView->max('total_views') ?: 1; @endphp
          @foreach($topCatsByView as $cat)
          <div class="mb-2">
            <div class="d-flex justify-content-between" style="font-size:13px;margin-bottom:3px;">
              <span style="color:inherit;">{{ $cat->name }}</span>
              <strong>{{ number_format($cat->total_views) }} views</strong>
            </div>
            <div style="height:6px;background:#eee;border-radius:3px;">
              <div style="height:100%;background:#E84118;border-radius:3px;width:{{ round($cat->total_views / $maxViews * 100) }}%"></div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>

    {{-- BÌNH LUẬN MỚI NHẤT --}}
    <div class="box mt-3">
      <div class="box-header"><h3 class="box-title"><i class="bi bi-chat-dots me-1"></i>Bình Luận Gần Đây</h3></div>
      <div class="box-body p-0">
        @forelse($recentComments as $cmt)
        <div style="padding:10px 14px;border-bottom:1px solid #f0f0f0;">
          <div style="font-size:12.5px;font-weight:700;">{{ $cmt->user->name ?? 'Ẩn danh' }}
            <span style="color:#aaa;font-weight:normal;font-size:11.5px;"> — {{ $cmt->created_at->diffForHumans() }}</span>
          </div>
          <div style="font-size:12.5px;color:#555;margin:2px 0;">{{ Str::limit($cmt->content, 70) }}</div>
          @if($cmt->article)
          <a href="{{ route('detail', $cmt->article->slug) }}" target="_blank"
             style="font-size:11.5px;color:#0A3D62;">
            <i class="bi bi-arrow-right me-1"></i>{{ Str::limit($cmt->article->name, 45) }}
          </a>
          @endif
        </div>
        @empty
        <div class="text-center py-3 text-muted" style="font-size:13px;">Chưa có bình luận nào.</div>
        @endforelse
      </div>
    </div>

    {{-- QUICK LINKS --}}
    <div class="box mt-3">
      <div class="box-header"><h3 class="box-title"><i class="bi bi-lightning me-1"></i>Thao Tác Nhanh</h3></div>
      <div class="box-body">
        <div class="d-grid gap-2">
          <a href="{{ route('product.create') }}" class="btn btn-sm btn-success">
            <i class="fa fa-plus me-1"></i>Thêm bài viết mới
          </a>
          <a href="{{ route('breaking.index') }}" class="btn btn-sm btn-warning">
            <i class="bi bi-lightning-charge me-1"></i>Quản lý Breaking News
          </a>
          <a href="{{ route('product.index') }}" class="btn btn-sm btn-default">
            <i class="fa fa-list me-1"></i>Danh sách bài viết
          </a>
          <a href="{{ route('contact.index') }}" class="btn btn-sm btn-default">
            <i class="bi bi-envelope me-1"></i>Liên hệ chưa xử lý ({{ $contactCount }})
          </a>
          @php $pendingCmt = \App\Models\Comment::where('is_approved',false)->whereNull('deleted_at')->count(); @endphp
          @if($pendingCmt > 0)
          <a href="{{ route('admin.comment.index') }}" class="btn btn-sm btn-warning">
            <i class="bi bi-chat-dots me-1"></i>Duyệt bình luận ({{ $pendingCmt }})
          </a>
          @endif
          <a href="{{ route('user.index') }}" class="btn btn-sm btn-default">
            <i class="bi bi-people-fill me-1"></i>Quản lý Thành viên Cộng đồng
          </a>
          <a href="{{ route('ad_slots.index') }}" class="btn btn-sm btn-default" style="opacity:.6;font-size:11.5px;">
            <i class="bi bi-megaphone me-1"></i>Vị trí quảng cáo (tùy chọn)
          </a>
        </div>
      </div>
    </div>

  </div>
</div>

{{-- ===== SEARCH KEYWORDS + TOP CATEGORIES VIEWS ===== --}}
<div class="row g-3 mt-1">
  <div class="col-lg-6">
    <div class="box">
      <div class="box-header"><h3 class="box-title"><i class="bi bi-search me-1"></i>Từ Khóa Tìm Kiếm (30 ngày)</h3></div>
      <div class="box-body p-0">
        @if($topKeywords->isEmpty())
        <div class="text-center py-3 text-muted" style="font-size:13px;">Chưa có dữ liệu tìm kiếm.</div>
        @else
        <table class="table table-hover table-sm mb-0">
          <thead><tr><th>#</th><th>Từ khóa</th><th style="text-align:right;">Lần tìm</th><th style="text-align:right;">TB kết quả</th></tr></thead>
          <tbody>
            @foreach($topKeywords as $i => $kw)
            <tr>
              <td style="width:24px;color:{{ $i<3?'#E84118':'#888' }};font-weight:700;">{{ $i+1 }}</td>
              <td>
                <a href="{{ route('search') }}?s={{ urlencode($kw->keyword) }}" target="_blank"
                   style="font-size:13px;font-weight:600;">{{ $kw->keyword }}</a>
              </td>
              <td style="text-align:right;font-weight:700;">{{ number_format($kw->count) }}</td>
              <td style="text-align:right;color:#888;font-size:12.5px;">{{ round($kw->avg_results) }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
        @endif
      </div>
    </div>
  </div>
  <div class="col-lg-6">
    {{-- TRENDING CACHED --}}
    @if($trendingCached && $trendingCached->count())
    <div class="box">
      <div class="box-header"><h3 class="box-title"><i class="bi bi-lightning-charge me-1" style="color:#E84118;"></i>Trending 24h (Cache)</h3></div>
      <div class="box-body p-0">
        <table class="table table-hover table-sm mb-0">
          <thead><tr><th>#</th><th>Bài viết</th><th style="text-align:right;">Views</th></tr></thead>
          <tbody>
            @foreach($trendingCached->take(8) as $i => $t)
            <tr>
              <td style="width:24px;color:{{ $i<3?'#E84118':'#888' }};font-weight:700;">{{ $i+1 }}</td>
              <td style="font-size:13px;">
                <a href="{{ route('detail', $t->slug) }}" target="_blank">{{ Str::limit($t->name, 60) }}</a>
              </td>
              <td style="text-align:right;font-weight:700;">{{ number_format($t->view_count) }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    @else
    <div class="box">
      <div class="box-header"><h3 class="box-title"><i class="bi bi-lightning-charge me-1"></i>Trending 24h</h3></div>
      <div class="box-body text-center text-muted py-3" style="font-size:13px;">
        <i class="bi bi-hourglass-split me-1"></i>Cache sẽ cập nhật sau khi artisan <code>vnkr:trending</code> chạy.
      </div>
    </div>
    @endif
  </div>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// ===== VIEWS CHART =====
const ctx = document.getElementById('viewsChart').getContext('2d');
new Chart(ctx, {
  type: 'line',
  data: {
    labels: {!! json_encode($chartLabels) !!},
    datasets: [
      {
        label: 'Lượt xem',
        data: {!! json_encode($chartViews) !!},
        borderColor: '#0A3D62',
        backgroundColor: 'rgba(10,61,98,0.08)',
        borderWidth: 2,
        fill: true,
        tension: 0.3,
        pointRadius: 3,
        yAxisID: 'y',
      },
      {
        label: 'Bài đăng',
        data: {!! json_encode($chartPosts) !!},
        borderColor: '#E84118',
        backgroundColor: 'rgba(232,65,24,0.07)',
        borderWidth: 2,
        fill: false,
        tension: 0.3,
        pointRadius: 3,
        borderDash: [4,3],
        yAxisID: 'y1',
      }
    ]
  },
  options: {
    responsive: true,
    interaction: { mode: 'index', intersect: false },
    plugins: { legend: { position: 'top', labels: { font: { size: 12 } } } },
    scales: {
      y:  { position: 'left',  title: { display: true, text: 'Lượt xem', font: { size: 11 } }, beginAtZero: true },
      y1: { position: 'right', title: { display: true, text: 'Bài đăng', font: { size: 11 } }, beginAtZero: true, grid: { drawOnChartArea: false } }
    }
  }
});

// ===== TAB helpers =====
function showTab(id, btn) {
  document.querySelectorAll('.top-tab').forEach(t => t.style.display = 'none');
  document.getElementById(id).style.display = 'block';
  btn.closest('.box-tools').querySelectorAll('button').forEach(b => {
    b.className = b === btn ? 'btn btn-xs btn-primary' : 'btn btn-xs btn-default';
  });
}
function showCatTab(id, btn) {
  ['cat-posts','cat-views'].forEach(t => {
    const el = document.getElementById(t);
    if (el) el.style.display = t === id ? 'block' : 'none';
  });
  btn.closest('div').querySelectorAll('button').forEach(b => {
    b.className = b === btn ? 'btn btn-xs btn-primary' : 'btn btn-xs btn-default';
  });
}
</script>
@endsection
