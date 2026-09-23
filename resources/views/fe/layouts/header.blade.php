@php
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
$navCategories  = \App\Models\Category::where('status',1)->orderBy('id')->get();
$now            = \Carbon\Carbon::now('Asia/Ho_Chi_Minh');
$days           = ['Chủ nhật','Thứ hai','Thứ ba','Thứ tư','Thứ năm','Thứ sáu','Thứ bảy'];
$dayName        = $days[$now->dayOfWeek];
$breakingItems  = Cache::remember('breaking_news', 120, fn() =>
    \App\Models\BreakingNews::active()->orderByDesc('created_at')->limit(5)->get()
);
@endphp

{{-- TOP BAR --}}
<div class="top-bar">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
      <div class="top-links">
        <a href="{{ route('index') }}"><i class="bi bi-house me-1"></i>Trang chủ</a>
        <span>|</span>
        <a href="{{ route('search') }}?s="><i class="bi bi-newspaper me-1"></i>Tin mới</a>
        <span>|</span>
        <a href="/about"><i class="bi bi-info-circle me-1"></i>Giới thiệu</a>
        <span>|</span>
        <a href="{{ route('contact.show') }}"><i class="bi bi-headset me-1"></i>Liên hệ</a>
      </div>
      <div class="d-flex align-items-center gap-3">
        <span style="opacity:.8;"><i class="bi bi-calendar3 me-1"></i>{{ $dayName }}, {{ $now->format('d/m/Y') }}</span>

        {{-- Dark mode toggle — Sprint 4 §5 --}}
        <button class="vnkr-theme-toggle" id="vnkr-theme-toggle"
                aria-label="Chuyển chế độ sáng/tối"
                title="Chuyển chế độ sáng/tối">
          <i class="bi bi-sun-fill" id="vnkr-theme-icon"></i>
        </button>

        @guest
          <a href="{{ route('login') }}"><i class="bi bi-person-circle me-1"></i>Đăng nhập</a>
          <a href="{{ route('register') }}" class="vnkr-btn vnkr-btn--primary vnkr-btn--sm">Đăng ký</a>
        @else
          <div class="dropdown">
            <a class="dropdown-toggle d-flex align-items-center gap-1" href="#"
               data-bs-toggle="dropdown" style="color:rgba(255,255,255,.9);">
              <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li>
                <a class="dropdown-item fw-bold" href="{{ route('profile.show') }}">
                  <i class="bi bi-person-circle me-2" style="color:var(--brand);"></i>Hồ sơ của tôi
                </a>
              </li>
              <li><hr class="dropdown-divider my-1"></li>
              <li>
                <a class="dropdown-item" href="{{ route('bookmarks.index') }}">
                  <i class="bi bi-bookmark-fill me-2 text-warning"></i>Bài đã lưu
                </a>
              </li>
              <li>
                <a class="dropdown-item" href="{{ route('history.index') }}">
                  <i class="bi bi-clock-history me-2"></i>Lịch sử đọc
                </a>
              </li>
              @if(Auth::user()->role === 'admin')
              <li>
                <a class="dropdown-item" href="{{ route('admin.index') }}">
                  <i class="bi bi-speedometer2 me-2 text-primary"></i>Quản trị
                </a>
              </li>
              @endif
              <li><hr class="dropdown-divider"></li>
              <li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                  @csrf
                  <button type="submit" class="dropdown-item">
                    <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
                  </button>
                </form>
              </li>
            </ul>
          </div>
        @endguest
      </div>
    </div>
  </div>
</div>

{{-- MAIN HEADER --}}
<header class="site-header">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">

      {{-- LOGO --}}
      <a href="{{ route('index') }}" class="site-logo">
        <div class="logo-mark">VNKR</div>
        <div class="logo-text">
          Cộng đồng phục vụ cộng đồng<br>
          <strong>Miễn phí — TheKingBao</strong>
        </div>
      </a>

      {{-- SEARCH with autocomplete (github/docs pattern) --}}
      <form class="header-search d-flex" action="{{ route('search') }}" method="GET"
            style="max-width:320px;width:100%;position:relative;" id="header-search-form">
        <div style="position:relative;flex:1;">
          <input name="s" id="header-search-input" class="vnkr-input" type="search"
                 placeholder="Tìm kiếm tin tức..."
                 value="{{ request('s') }}" autocomplete="off"
                 aria-label="Tìm kiếm" aria-autocomplete="list" aria-controls="search-autocomplete-list">
          <ul id="search-autocomplete-list"
              style="display:none;position:absolute;top:100%;left:0;right:0;z-index:999;
                     background:#fff;border:1px solid #ddd;border-top:none;border-radius:0 0 6px 6px;
                     list-style:none;margin:0;padding:0;box-shadow:0 4px 12px rgba(0,0,0,.12);max-height:280px;overflow-y:auto;"
              role="listbox">
          </ul>
        </div>
        <button class="btn-search" type="submit"><i class="bi bi-search"></i></button>
      </form>
      <script>
      (function () {
        var input    = document.getElementById('header-search-input');
        var list     = document.getElementById('search-autocomplete-list');
        var timer    = null;
        var apiUrl   = '{{ route('api.search.autocomplete') }}';

        if (!input || !list) return;

        function closeList() {
          list.style.display = 'none';
          list.innerHTML = '';
        }

        function addItem(text, url, category, isArticle) {
          var li = document.createElement('li');
          li.setAttribute('role', 'option');
          li.style.cssText = 'padding:8px 14px;cursor:pointer;font-size:13.5px;border-bottom:1px solid #f0f0f0;display:flex;align-items:center;gap:8px;';
          var icon = isArticle ? 'bi-file-earmark-text' : 'bi-search';
          li.innerHTML = '<i class="bi ' + icon + '" style="color:#aaa;font-size:11px;flex-shrink:0;"></i>'
                       + '<span class="ac-label" style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' + text + '</span>'
                       + (category ? '<span style="font-size:11px;color:#888;white-space:nowrap;">' + category + '</span>' : '');
          li.addEventListener('mousedown', function (e) {
            e.preventDefault();
            if (url) { window.location.href = url; }
            else { input.value = text; closeList(); document.getElementById('header-search-form').submit(); }
          });
          li.addEventListener('mouseover', function () { this.style.background = '#f5f7fa'; });
          li.addEventListener('mouseout',  function () { this.style.background = ''; });
          list.appendChild(li);
        }

        function renderData(data) {
          list.innerHTML = '';
          var suggestions = data.suggestions || [];
          var articles    = data.articles    || [];
          if (!suggestions.length && !articles.length) { closeList(); return; }

          // Popular keyword suggestions
          suggestions.forEach(function (kw) { addItem(kw, null, null, false); });

          // Separator if both sections present
          if (suggestions.length && articles.length) {
            var sep = document.createElement('li');
            sep.style.cssText = 'padding:4px 14px;font-size:10.5px;color:#aaa;letter-spacing:.05em;text-transform:uppercase;background:#fafafa;pointer-events:none;';
            sep.textContent = 'Bài viết';
            list.appendChild(sep);
          }

          // Article instant results — clicking navigates directly
          articles.forEach(function (a) { addItem(a.title, a.url, a.category, true); });

          list.style.display = 'block';
        }

        input.addEventListener('input', function () {
          clearTimeout(timer);
          var q = this.value.trim();
          if (q.length < 2) { closeList(); return; }
          timer = setTimeout(function () {
            fetch(apiUrl + '?q=' + encodeURIComponent(q))
              .then(function (r) { return r.ok ? r.json() : {}; })
              .then(function (data) { renderData(data); })
              .catch(function () { closeList(); });
          }, 180);
        });

        // Keyboard navigation — skip separator <li> (no pointer-events)
        input.addEventListener('keydown', function (e) {
          var items = Array.from(list.querySelectorAll('li')).filter(function(li){ return li.style.pointerEvents !== 'none'; });
          var active = list.querySelector('li.ac-active');
          var idx    = items.indexOf(active);
          if (e.key === 'ArrowDown') {
            e.preventDefault();
            if (active) { active.classList.remove('ac-active'); active.style.background = ''; }
            var next = items[idx + 1] || items[0];
            if (next) { next.classList.add('ac-active'); next.style.background = '#eef2ff'; input.value = next.querySelector('.ac-label').textContent; }
          } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (active) { active.classList.remove('ac-active'); active.style.background = ''; }
            var prev = items[idx - 1] || items[items.length - 1];
            if (prev) { prev.classList.add('ac-active'); prev.style.background = '#eef2ff'; input.value = prev.querySelector('.ac-label').textContent; }
          } else if (e.key === 'Escape') {
            closeList();
          }
        });

        document.addEventListener('click', function (e) {
          if (!document.getElementById('header-search-form').contains(e.target)) closeList();
        });
      })();
      </script>

    </div>
  </div>
</header>

{{-- STICKY NAV --}}
<nav class="main-nav">
  <div class="container">
    <div class="d-flex align-items-center justify-content-between">
      <button class="d-lg-none btn btn-sm border-0 p-2" type="button"
              data-bs-toggle="collapse" data-bs-target="#mainNavCollapse">
        <i class="bi bi-list fs-5"></i>
      </button>
      <div class="collapse navbar-collapse d-lg-block" id="mainNavCollapse">
        <ul class="nav-list">
          <li class="{{ request()->routeIs('index') ? 'active' : '' }}">
            <a href="{{ route('index') }}"><i class="bi bi-house-fill me-1"></i>Trang Chủ</a>
          </li>
          @foreach($navCategories as $cat)
          @php
            $specialNavClass = match($cat->slug ?? '') {
                'thao-luan'        => 'nav-community',
                'tai-tro-dong-gop' => 'nav-sponsor',
                'web3-crypto'      => 'nav-web3',
                default            => '',
            };
            $specialNavIcon = match($cat->slug ?? '') {
                'thao-luan'        => 'bi-chat-dots-fill',
                'tai-tro-dong-gop' => 'bi-heart-fill',
                'web3-crypto'      => 'bi-currency-bitcoin',
                default            => '',
            };
          @endphp
          <li class="{{ request()->is('chuyen-muc/'.$cat->slug) || request()->is('result/'.$cat->id) ? 'active' : '' }} {{ $specialNavClass }}">
            <a href="{{ $cat->slug ? route('category.slug', $cat->slug) : route('result', $cat->id) }}">
              @if($specialNavIcon)<i class="bi {{ $specialNavIcon }} me-1"></i>@endif{{ $cat->name }}
            </a>
          </li>
          @endforeach
          <li class="ptb">
            <a href="{{ route('search') }}?s=goc-nhin">Góc Nhìn PTB</a>
          </li>
          <li>
            <a href="{{ route('contact.show') }}">Liên Hệ</a>
          </li>
        </ul>
      </div>
      <div class="d-none d-lg-flex align-items-center gap-2 pe-2" style="font-size:12px;white-space:nowrap;">
        <span style="background:var(--accent);color:#fff;padding:3px 8px;border-radius:3px;font-weight:800;font-size:11px;animation:blink-live 1s infinite;">
          <i class="bi bi-broadcast me-1"></i>LIVE
        </span>
        <span class="text-muted">{{ $now->format('H:i') }}</span>
      </div>
    </div>
  </div>
</nav>

{{-- BREAKING NEWS TICKER --}}
@if($breakingItems->count())
<div style="background:var(--accent);color:#fff;font-size:13px;padding:6px 0;overflow:hidden;">
  <div class="container d-flex align-items-center gap-2">
    <span style="background:rgba(0,0,0,.25);padding:2px 10px;border-radius:3px;font-weight:800;white-space:nowrap;font-size:12px;flex-shrink:0;">
      <i class="bi bi-lightning-charge-fill me-1"></i>NÓNG
    </span>
    <div style="overflow:hidden;flex:1;position:relative;">
      <div class="breaking-ticker" style="display:flex;gap:40px;animation:ticker-scroll 30s linear infinite;width:max-content;">
        @foreach($breakingItems as $bn)
          @if($bn->url)
            <a href="{{ $bn->url }}" style="color:#fff;white-space:nowrap;font-weight:600;text-decoration:underline;text-underline-offset:2px;">
              {{ $bn->title }}
            </a>
          @else
            <span style="white-space:nowrap;font-weight:600;">{{ $bn->title }}</span>
          @endif
          <span style="opacity:.4;">•</span>
        @endforeach
        {{-- Lặp lại để ticker liên tục --}}
        @foreach($breakingItems as $bn)
          @if($bn->url)
            <a href="{{ $bn->url }}" style="color:#fff;white-space:nowrap;font-weight:600;text-decoration:underline;text-underline-offset:2px;">
              {{ $bn->title }}
            </a>
          @else
            <span style="white-space:nowrap;font-weight:600;">{{ $bn->title }}</span>
          @endif
          <span style="opacity:.4;">•</span>
        @endforeach
      </div>
    </div>
  </div>
</div>
@endif
