/**
 * VNKR Service Worker — PWA offline support
 * ==========================================
 * Phiên bản: v2 (Sprint 7 — Design System cache)
 * Chiến lược:
 *   - Cache-First  → assets tĩnh (CSS, JS, images, fonts)
 *   - Network-First → trang HTML (luôn lấy mới nhất)
 *   - Stale-While-Revalidate → Google Fonts (đọc cache, update ngầm)
 *
 * Thay đổi phiên bản cache (CACHE_VERSION) khi cần force-invalidate toàn bộ.
 */
const CACHE_VERSION  = 'vnkr-v2';   /* ← bump ở đây khi deploy CSS/JS mới */
const CACHE_STATIC   = CACHE_VERSION + '-static';
const CACHE_PAGES    = CACHE_VERSION + '-pages';
const CACHE_FONTS    = CACHE_VERSION + '-fonts';

// ─── Tài nguyên precache khi install ───────────────────────────────────
const PRECACHE_URLS = [
  // App shell
  '/',
  '/offline',
  '/client/images/favicon.png',

  // Bootstrap (dùng trên toàn site + admin)
  '/client/plugins/bootstrap/bootstrap.min.css',

  // VNKR Design System (Sprint 1–6)
  '/assets/css/vnkr-tokens.css',
  '/assets/css/vnkr-ui.css',
  '/assets/css/vnkr-fe.css',
  '/assets/js/vnkr-ui.js',

  // Bootstrap Icons (offline)
  '/assets/fonts/bootstrap/bootstrap-icons.css',
  '/assets/fonts/bootstrap/bootstrap-icons.woff2',
];

// ───────── INSTALL ─────────
self.addEventListener('install', event => {
  self.skipWaiting();
  event.waitUntil(
    caches.open(CACHE_STATIC).then(cache => {
      return cache.addAll(PRECACHE_URLS.map(url => new Request(url, { cache: 'reload' })))
        .catch(() => { /* nếu 1 resource fail, không chặn install */ });
    })
  );
});

// ───────── ACTIVATE ─────────
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(keys =>
      Promise.all(
        keys
          .filter(k => k.startsWith('vnkr-') && k !== CACHE_STATIC && k !== CACHE_PAGES)
          .map(k => caches.delete(k))
      )
    ).then(() => self.clients.claim())
  );
});

// ───────── FETCH ─────────
self.addEventListener('fetch', event => {
  const req = event.request;
  const url = new URL(req.url);

  // Chỉ xử lý same-origin GET requests
  if (req.method !== 'GET' || url.origin !== self.location.origin) return;

  // Bỏ qua: admin, api, horizon, livewire, _debugbar
  if (/^\/(admin|api|horizon|livewire|_debugbar)/.test(url.pathname)) return;

  // Google Fonts — Stale-While-Revalidate (đọc từ cache, update ngầm)
  if (url.hostname === 'fonts.googleapis.com' || url.hostname === 'fonts.gstatic.com') {
    event.respondWith(staleWhileRevalidate(req, CACHE_FONTS));
    return;
  }

  // Static assets (css/js/images/fonts/woff) — Cache First
  if (/\.(css|js|png|jpg|jpeg|webp|gif|svg|woff2?|ico|ttf)$/.test(url.pathname)) {
    event.respondWith(cacheFirst(req, CACHE_STATIC));
    return;
  }

  // HTML pages — Network First, fallback to cache, then offline page
  event.respondWith(networkFirst(req));
});

async function cacheFirst(req, cacheName) {
  const cached = await caches.match(req);
  if (cached) return cached;
  try {
    const resp = await fetch(req);
    if (resp.ok) {
      const cache = await caches.open(cacheName);
      cache.put(req, resp.clone());
    }
    return resp;
  } catch {
    return new Response('', { status: 503 });
  }
}

async function networkFirst(req) {
  const cache = await caches.open(CACHE_PAGES);
  try {
    const resp = await fetch(req);
    if (resp.ok) cache.put(req, resp.clone());
    return resp;
  } catch {
    const cached = await cache.match(req);
    if (cached) return cached;
    // Fallback offline page
    const offline = await caches.match('/offline');
    if (offline) return offline;

    // Last-resort inline fallback (khi /offline chưa cache)
    return new Response(
      '<!DOCTYPE html><html lang="vi"><head><meta charset="utf-8">' +
      '<meta name="viewport" content="width=device-width,initial-scale=1">' +
      '<title>VNKR — Không có kết nối</title>' +
      '<style>' +
        'body{margin:0;font-family:"Be Vietnam Pro",Arial,sans-serif;' +
          'background:#0A3D62;color:#fff;display:flex;align-items:center;' +
          'justify-content:center;min-height:100vh;text-align:center;padding:24px;}' +
        '.card{background:#fff;color:#1a1a1a;border-radius:16px;padding:40px 32px;' +
          'max-width:400px;box-shadow:0 8px 32px rgba(0,0,0,.25);}' +
        'h1{font-size:1.5rem;font-weight:700;color:#0A3D62;margin:16px 0 8px;}' +
        'p{font-size:14px;color:#5a6a7a;line-height:1.65;margin:0 0 20px;}' +
        'a{display:inline-block;background:#E84118;color:#fff;padding:10px 28px;' +
          'border-radius:50px;font-weight:600;font-size:14px;text-decoration:none;}' +
        'a:hover{background:#c73510;}' +
        '.icon{font-size:48px;line-height:1;}' +
      '</style></head>' +
      '<body><div class="card">' +
        '<div class="icon">📡</div>' +
        '<h1>Không có kết nối</h1>' +
        '<p>VNKR cần internet để tải tin tức mới.<br>Vui lòng kiểm tra kết nối và thử lại.</p>' +
        '<a href="/">↩ Quay lại trang chủ</a>' +
      '</div></body></html>',
      { headers: { 'Content-Type': 'text/html; charset=utf-8' }, status: 503 }
    );
  }
}

// ─── Stale-While-Revalidate — cho Google Fonts ──────────────────────────────
async function staleWhileRevalidate(req, cacheName) {
  const cache  = await caches.open(cacheName);
  const cached = await cache.match(req);
  const fetchPromise = fetch(req).then(resp => {
    if (resp.ok) cache.put(req, resp.clone());
    return resp;
  }).catch(() => cached);
  return cached || fetchPromise;
}
