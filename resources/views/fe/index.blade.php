<!DOCTYPE html>
<html lang="vi">
<head>
  <!-- Google Tag Manager -->
  <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
  new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
  j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
  'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
  })(window,document,'script','dataLayer','GTM-59BJV9S4');</script>
  <!-- End Google Tag Manager -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">

  {{-- ===== SEO CORE ===== --}}
  <title>@yield('title', 'VNKR — Tin Tức Cộng Đồng TheKingBao')</title>
  <meta name="description" content="@yield('meta_description', 'VNKR — Tin tức được biên tập bởi Phạm Thế Bảo, dành cho cộng đồng TheKingBao. Đọc ít hơn, hiểu nhiều hơn.')">
  <meta name="robots" content="index, follow">
  <meta name="author" content="Phạm Thế Bảo / VNKR">
  <link rel="canonical" href="@yield('canonical', url()->current())">

  {{-- ===== OPEN GRAPH ===== --}}
  <meta property="og:site_name" content="VNKR">
  <meta property="og:type" content="@yield('og_type', 'website')">
  <meta property="og:title" content="@yield('title', 'VNKR — Tin Tức Cộng Đồng TheKingBao')">
  <meta property="og:description" content="@yield('meta_description', 'VNKR — Tin tức được biên tập bởi Phạm Thế Bảo, dành cho cộng đồng TheKingBao.')">
  <meta property="og:url" content="@yield('canonical', url()->current())">
  <meta property="og:image" content="@yield('og_image', asset('client/images/favicon.png'))">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  <meta property="og:locale" content="vi_VN">

  {{-- ===== TWITTER CARD ===== --}}
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:site" content="@thekingbao">
  <meta name="twitter:title" content="@yield('title', 'VNKR')">
  <meta name="twitter:description" content="@yield('meta_description', 'VNKR — Tin tức cộng đồng TheKingBao')">
  <meta name="twitter:image" content="@yield('og_image', asset('client/images/favicon.png'))">

  {{-- ===== RSS AUTODISCOVERY ===== --}}
  <link rel="alternate" type="application/rss+xml" title="VNKR RSS Feed" href="{{ url('/feed') }}">

  {{-- ===== PWA ===== --}}
  <link rel="manifest" href="/manifest.json">
  <meta name="theme-color" content="#0A3D62">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="default">
  <meta name="apple-mobile-web-app-title" content="VNKR">
  <link rel="apple-touch-icon" href="/client/images/favicon.png">

  @include('fe.layouts.css')

  {{-- ===== SPRINT 7: FONT PRELOAD HINTS =====
       Preload Work Sans 600 (headings) + Be Vietnam Pro 400/600 (body)
       trước khi render — giảm FOUT (Flash of Unstyled Text).
       subset=vietnamese đảm bảo dấu tiếng Việt không bị thiếu.
  ===== --}}
  <link rel="preload" as="style"
        href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Be+Vietnam+Pro:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&subset=vietnamese&display=swap">

  {{-- ===== SPRINT 7: CRITICAL CSS (above-the-fold inline) =====
       Inlined tokens + minimal layout để trang render đúng ngay lập tức
       mà không phải chờ vnkr-tokens.css / vnkr-fe.css tải xong.
       Chỉ bao gồm: màu nền, font, top-bar, header, nav — không hơn.
  ===== --}}
  <style>
    /* Critical: base body */
    html { scroll-behavior: smooth; }
    body { margin: 0; font-family: 'Be Vietnam Pro', Arial, sans-serif;
           background: #f4f6f8; color: #1a1a1a; font-size: 15px; line-height: 1.6; }
    *, *::before, *::after { box-sizing: border-box; }
    img { display: block; max-width: 100%; }
    a { text-decoration: none; color: inherit; }

    /* Critical: top-bar */
    .top-bar { background: #0A3D62; color: #fff; font-size: 12.5px; padding: 5px 0; }

    /* Critical: site-header */
    .site-header { background: #fff; border-bottom: 2px solid #0A3D62; padding: 12px 0; }

    /* Critical: sticky nav */
    .main-nav { background: #fff; border-bottom: 3px solid #E84118;
                position: sticky; top: 0; z-index: 999; }

    /* Critical: container */
    .container { max-width: 1200px; margin: 0 auto; padding: 0 16px; }

    /* Critical: prevent layout shift — logo area */
    .logo-mark { display: inline-flex; align-items: center; justify-content: center;
                 background: #0A3D62; color: #fff;
                 font-size: 24px; font-weight: 900; letter-spacing: -1px;
                 padding: 4px 12px; border-radius: 4px;
                 border-bottom: 3px solid #E84118; }
  </style>

  @stack('jsonld')
</head>
<body>
  <!-- Google Tag Manager (noscript) -->
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-59BJV9S4"
  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  <!-- End Google Tag Manager (noscript) -->
  @include('fe.layouts.header')
  <main>@yield('main')</main>
  @include('fe.layouts.footer')
  @include('fe.layouts.js')
  {{-- ===== SERVICE WORKER REGISTRATION ===== --}}
  <script>
  if ('serviceWorker' in navigator) {
    window.addEventListener('load', function () {
      navigator.serviceWorker.register('/sw.js', { scope: '/' })
        .then(function (reg) {
          // SW registered — check for updates every 60s
          setInterval(function () { reg.update(); }, 60000);
        })
        .catch(function (err) {
          console.warn('SW registration failed:', err);
        });
    });
  }
  </script>
</body>
</html>
