# 📰 Báo Cáo & Kế Hoạch Chiến Lược Mở Rộng — VNKR.VN

> **Phiên bản:** 2.4.0
> **Ngày lập:** 22/09/2026 | **Cập nhật lần cuối:** 24/09/2026
> **Stack hiện tại:** Laravel 10 · PHP 8.2 · MySQL 8.0 · Nginx 1.24 · Vite · Redis
> **Domain:** https://vnkr.vn

---

## MỤC LỤC

1. [Phân Tích Hiện Trạng](#1-phân-tích-hiện-trạng)
2. [Đánh Giá Điểm Mạnh & Yếu](#2-đánh-giá-điểm-mạnh--yếu)
3. [Benchmark Tính Năng Báo Tin Hàng Đầu](#3-benchmark-tính-năng-báo-tin-hàng-đầu)
4. [Lộ Trình Chiến Lược Mở Rộng](#4-lộ-trình-chiến-lược-mở-rộng)
5. [Thiết Kế Kiến Trúc Mở Rộng](#5-thiết-kế-kiến-trúc-mở-rộng)
6. [Kế Hoạch Database Mở Rộng](#6-kế-hoạch-database-mở-rộng)
7. [Kế Hoạch Triển Khai Chi Tiết](#7-kế-hoạch-triển-khai-chi-tiết)
8. [KPI & Mục Tiêu Đo Lường](#8-kpi--mục-tiêu-đo-lường)

---

## 1. Phân Tích Hiện Trạng

### 1.1 Kiến Trúc Hệ Thống Hiện Tại

```
vnkr.vn/
├── Frontend (Blade + Vite)
│   ├── Trang chủ        → HomeController@index
│   ├── Chi tiết bài     → DetailController@show   (slug-based)
│   ├── Danh mục         → HomeController@result   (by category_id)
│   ├── Tìm kiếm         → SearchController@search (LIKE query)
│   ├── Liên hệ          → ContactController@show/submit
│   ├── Đăng nhập/Đăng ký → UserController
│   ├── Quên mật khẩu    → ForgotPasswordController
│   ├── Sitemap XML      → SitemapController@index  ← MỚI
│   └── RSS Feed         → RssFeedController@index  ← MỚI
│
├── Admin Panel (prefix: /admin, middleware: admin)
│   ├── Dashboard        → DashBoardController    (4 metrics: users/categories/products/contacts)
│   ├── Quản lý bài viết → ProductController      (CRUD + soft-delete + trash/restore)
│   ├── Quản lý danh mục → CategoryController     (CRUD + soft-delete + parent_id)
│   ├── Quản lý user     → UserController         (CRUD + changeRole)
│   ├── Quản lý liên hệ  → ContactController
│   └── Quản lý vai trò  → RoleController
│
└── API
    └── GET /api/user    (Sanctum auth — chưa khai thác đầy đủ)
```

### 1.2 Cấu Trúc Database Hiện Tại

| Bảng | Cột chính | Ghi chú |
|---|---|---|
| `users` | id, name, email, password, role, remember_token | Không có avatar, bio, phone |
| `categories` | id, name, status, parent_id, deleted_at | Hỗ trợ danh mục cha-con |
| `products` | id, name, tomtat, image, category_id, slug, description, stock | `stock=1` → bài nổi bật; "product" dùng thay cho "article" |
| `img_products` | (ảnh phụ) | Nhiều ảnh cho một bài |
| `contacts` | id, user_id, name, email, message | Form liên hệ |
| `roles` | (từ RoleModel) | Hệ thống vai trò |
| `personal_access_tokens` | (Sanctum) | API token |
| `failed_jobs` | — | Queue jobs |
| `sessions` | id, user_id, ip_address, payload, last_activity | Session Redis/DB ← MỚI |

### 1.3 Tính Năng Đang Có

| Tính năng | Trạng thái | Ghi chú kỹ thuật |
|---|---|---|
| Đăng/sửa/xóa bài viết | ✅ Có | Admin panel, soft-delete |
| Danh mục phân cấp | ✅ Có | parent_id, 2 cấp |
| Slug URL | ✅ Có | SEO-friendly |
| Tìm kiếm | ✅ Cơ bản | LIKE query, chưa full-text |
| Bài nổi bật | ✅ Cơ bản | `stock = 1`, limit 5 |
| Phân trang | ✅ Có | `paginate(5)` |
| Bình luận | ⚠️ Cần nâng cấp | Lưu JSON file — **chưa migrate DB** |
| Like / Reply bình luận | ⚠️ Cần nâng cấp | JSON file |
| Đăng nhập/Đăng ký | ✅ Có | Session-based |
| Quên mật khẩu | ✅ Có | Email token |
| Quản lý người dùng | ✅ Có | Admin panel |
| Ảnh bài viết | ✅ 1 ảnh chính | Storage upload |
| Ảnh phụ | ✅ img_products | Nhiều ảnh |
| Form liên hệ | ✅ Có | Auth required |
| HTTPS | ✅ Có | Let's Encrypt, auto-renew |
| **Redis Cache** | ✅ **Đã bật** | `CACHE_DRIVER=redis`, `SESSION_DRIVER=redis` |
| **Sitemap XML** | ✅ **Mới triển khai** | `/sitemap.xml` — tự động include bài + danh mục |
| **RSS Feed** | ✅ **Mới triển khai** | `/feed` — 30 bài mới nhất, RFC 2822 |
| **SEO Meta / Open Graph** | ✅ **Mới triển khai** | og:title, og:image, og:description, twitter:card |
| **Schema.org NewsArticle** | ✅ **Mới triển khai** | JSON-LD trên từng trang bài viết |
| **Canonical URL** | ✅ **Mới triển khai** | `<link rel="canonical">` tự động |
| **Social Share Buttons** | ✅ **Mới triển khai** | Facebook, Zalo, Twitter/X, Copy link |
| **robots.txt chuẩn** | ✅ **Mới triển khai** | Chặn /admin, /api, /storage, có Sitemap directive |
| **Bài viết liên quan** | ✅ **Mới triển khai** | Cùng danh mục, ORDER BY created_at DESC, limit 3 |
| Google Tag Manager | ✅ Có | GTM-59BJV9S4 |

---

## 2. Đánh Giá Điểm Mạnh & Yếu

### ✅ Điểm Mạnh

- **Nền tảng vững:** Laravel 10 + PHP 8.2, đủ hiện đại để mở rộng lớn
- **SEO slug URL:** `/detail/{slug}` chuẩn
- **Soft-delete:** Bài viết và danh mục có thể khôi phục
- **Phân cấp danh mục:** parent_id sẵn sàng
- **Admin panel riêng:** Tách biệt với middleware `admin`
- **Sanctum sẵn có:** Nền tảng API đã cài
- **HTTPS + Let's Encrypt:** Bảo mật cơ bản OK
- **Redis đã cài & bật:** Cache + session hiệu quả hơn
- **SEO layer hoàn thiện:** OG, Twitter Card, Schema.org, canonical, sitemap, RSS, robots.txt
- **Social share:** Facebook, Zalo, Twitter/X, Copy link đã hoạt động

### ⚠️ Điểm Yếu & Rủi Ro

| Vấn đề | Mức độ | Mô tả |
|---|---|---|
| Tên `product` thay vì `article` | 🟡 Trung bình | Gây nhầm lẫn, cần refactor hoặc alias |
| Bình luận lưu JSON file | 🔴 Cao | **Chưa migrate** — không scale, không query được |
| Tìm kiếm chỉ dùng LIKE | 🔴 Cao | Không hỗ trợ full-text, không phân trang kết quả |
| Không có view_count | 🟡 Trung bình | Không biết bài nào hot |
| Không có tags/từ khóa | 🟡 Trung bình | SEO + UX thiếu |
| Không có CDN | 🟡 Trung bình | Ảnh load chậm, không có WebP |
| `stock` column dùng làm "featured" | 🟡 Trung bình | Semantics sai |
| Queue chạy sync | 🟡 Trung bình | Email + ảnh resize chặn response |
| Không có scheduled jobs | 🟡 Trung bình | Không tự động hóa được |
| UFW firewall tắt | 🔴 Cao | **Chưa bật** — rủi ro bảo mật |
| PHP memory_limit = -1 | 🔴 Cao | **Chưa fix** — DoS risk |
| Không có Swap | 🔴 Cao | **Chưa tạo** — OOM crash risk |
| API chưa khai thác | 🟢 Thấp | Tiềm năng cho mobile app |
| URL danh mục dùng ID | 🟡 Trung bình | `/result/1` không thân thiện SEO |

---

## 3. Benchmark Tính Năng Báo Tin Hàng Đầu

> So sánh với VnExpress, Tuổi Trẻ, Thanh Niên, Zing News, Dân Trí

| Tính năng | vnkr.vn | VnExpress | Tuổi Trẻ | Zing News |
|---|:---:|:---:|:---:|:---:|
| Bài viết + ảnh | ✅ | ✅ | ✅ | ✅ |
| Danh mục phân cấp | ✅ | ✅ | ✅ | ✅ |
| Slug SEO | ✅ | ✅ | ✅ | ✅ |
| SEO Meta + Open Graph | ✅ **MỚI** | ✅ | ✅ | ✅ |
| Schema.org JSON-LD | ✅ **MỚI** | ✅ | ✅ | ✅ |
| Social Share Buttons | ✅ **MỚI** | ✅ | ✅ | ✅ |
| RSS Feed | ✅ **MỚI** | ✅ | ✅ | ✅ |
| Sitemap XML | ✅ **MỚI** | ✅ | ✅ | ✅ |
| Bài viết liên quan | ✅ **MỚI** | ✅ | ✅ | ✅ |
| Tìm kiếm full-text | ❌ | ✅ | ✅ | ✅ |
| Tags / Từ khóa | ❌ | ✅ | ✅ | ✅ |
| Bình luận (DB) | ❌ JSON | ✅ | ✅ | ✅ |
| Like bài viết | ❌ | ✅ | ✅ | ✅ |
| Bài đọc nhiều nhất | ❌ | ✅ | ✅ | ✅ |
| Breaking news / Ticker | ❌ | ✅ | ✅ | ✅ |
| Video bài viết | ❌ | ✅ | ✅ | ✅ |
| Podcast/Audio | ❌ | ✅ | ❌ | ✅ |
| Newsletter đăng ký | ❌ | ✅ | ✅ | ✅ |
| Push notification | ❌ | ✅ | ✅ | ✅ |
| Dark mode | ❌ | ❌ | ❌ | ✅ |
| AMP (Google) | ❌ | ✅ | ✅ | ❌ |
| Đọc offline (PWA) | ❌ | ❌ | ❌ | ✅ |
| Bookmark bài viết | ❌ | ✅ | ✅ | ✅ |
| Lịch sử đọc | ❌ | ✅ | ❌ | ✅ |
| Tác giả / Phóng viên | ❌ | ✅ | ✅ | ✅ |
| Quảng cáo (AdSense) | ❌ | ✅ | ✅ | ✅ |
| Thống kê Analytics | ❌ | ✅ | ✅ | ✅ |
| API công khai | ❌ | ❌ | ❌ | ✅ |
| Mobile app | ❌ | ✅ | ✅ | ✅ |

---

## 4. Lộ Trình Chiến Lược Mở Rộng

> **Chú thích trạng thái:** ✅ Hoàn thành | 🔄 Đang làm | ⬜ Chưa làm

### 🏁 Phase 1 — Nền Tảng Ổn Định (Tháng 1–2)
> **Mục tiêu:** Sửa lỗi kiến trúc cốt lõi, đặt nền móng đúng

#### 1.1 Di Chuyển Bình Luận JSON → Database ✅
```
Tạo bảng: comments
- id, article_id (FK → products.id), user_id (FK), parent_id (reply)
- content, likes (int default 0), is_approved (bool)
- created_at, updated_at, deleted_at (soft delete)
```
- **Lý do:** File JSON không atomic, không indexable, không thể scale
- **Effort:** 3 ngày
- **Impact:** 🔴 Critical
- **Trạng thái:** ✅ Hoàn thành — bảng `comments` đã migrate, DetailController rewrite, view updated

#### 1.2 Thêm View Count & Like Bài Viết ✅
```
Thêm cột vào products:
- view_count INT DEFAULT 0
- like_count INT DEFAULT 0
- is_featured BOOLEAN (thay thế stock = 1)
- is_published BOOLEAN
- published_at TIMESTAMP
- author_id FK users.id
```
- **Effort:** 2 ngày
- **Impact:** 🔴 Critical (analytics nội bộ)
- **Trạng thái:** ✅ Hoàn thành — migration done, view_count tự tăng khi xem bài, hiển thị trên detail

#### 1.3 Tags / Từ Khóa ✅
```
Tạo bảng: tags (id, name, slug)
Tạo bảng pivot: article_tag (article_id, tag_id)
```
- **Effort:** 2 ngày
- **Impact:** 🟡 SEO + UX
- **Trạng thái:** ✅ Hoàn thành — migration + Tag model + admin form (create/edit) + hiển thị detail + liên kết search

#### 1.4 Full-text Search ✅
```
- Thêm FULLTEXT index trên products(name, tomtat, description)
- Hoặc cài Meilisearch/Algolia qua Laravel Scout
- Phân trang kết quả tìm kiếm
```
- **Effort:** 3 ngày
- **Impact:** 🔴 UX quan trọng
- **Trạng thái:** ✅ Hoàn thành — FULLTEXT index MySQL, SearchController paginate 12, relevance sort, fallback LIKE

#### 1.5 Caching Layer ✅
```
- Redis đã bật: CACHE_DRIVER=redis, SESSION_DRIVER=redis
- Cần bổ sung: Cache trang chủ, danh sách category, bài chi tiết
- Invalidate khi admin cập nhật bài
```
- **Effort:** 1 ngày (phần còn lại)
- **Impact:** 🔴 Performance
- **Trạng thái:** ✅ Redis cấu hình xong + Application-level caching (home:featured, category:{id}) + cache invalidation khi admin update/delete

---

### 🚀 Phase 2 — Tính Năng Người Dùng (Tháng 3–4)
> **Mục tiêu:** Tăng tương tác, giữ chân người đọc

#### 2.1 Hệ Thống Tác Giả / Phóng Viên ✅
```
Thêm vào users:
- avatar, bio, facebook_url, twitter_url
- is_author BOOLEAN

Tạo profile page: /author/{username}
Hiển thị tên tác giả + avatar trên mỗi bài
```
- **Trạng thái:** ✅ Hoàn thành — migration users (username/avatar/bio/social/is_author), User model, AuthorController, view `/author/{username}`, avatar_url accessor (ui-avatars fallback)

#### 2.2 Bài Viết Liên Quan ✅
```
Logic đã triển khai:
- Cùng danh mục, WHERE id != current, ORDER BY created_at DESC LIMIT 3
- Hiển thị trên trang detail (sidebar + bottom)
```
- **Trạng thái:** ✅ Đã triển khai trong DetailController

#### 2.3 Bookmark / Đọc Sau ✅
```
Tạo bảng: bookmarks (id, user_id, article_id, created_at) ✅
Route: POST /bookmark/{article_id} ✅
UI: Nút bookmark trên mỗi bài → lưu vào /profile/bookmarks ✅
BookmarkController + toggle (add/remove) ✅
detail.blade.php — nút "Lưu bài / Đã lưu" ✅
```
- **Trạng thái:** ✅ Hoàn thành

#### 2.4 Lịch Sử Đọc ✅
```
Tạo bảng: reading_history (id, user_id, article_id, read_at) ✅
Hiển thị ở /profile/history ✅
ReadingHistoryController ✅
Dedup: 1 bản ghi mỗi (user, article, ngày) ✅
```
- **Trạng thái:** ✅ Hoàn thành

#### 2.5 Newsletter Đăng Ký ✅
```
Tạo bảng: newsletter_subscribers (id, email, token, confirmed_at)
Job: gửi email tóm tắt tin hàng ngày/tuần
Package: laravel/mail + queue
```
- **Trạng thái:** ✅ Hoàn thành — migration, NewsletterSubscriber model, NewsletterController, form footer, route unsubscribe, queue workers sẵn sàng

#### 2.6 Breaking News Ticker ✅
```
Tạo bảng: breaking_news (id, title, url, is_active, expired_at)
Admin quản lý → hiển thị ticker trên header
Cache 2 phút
```
- **Trạng thái:** ✅ Hoàn thành — migration, BreakingNews model (scopeActive), Admin CRUD, ticker header animation, cache 2 phút, link admin sidebar

---

### 📈 Phase 3 — SEO & Phân Phối Nội Dung (Tháng 5–6)
> **Mục tiêu:** Tăng traffic tự nhiên, xây dựng kênh phân phối

#### 3.1 Sitemap XML Tự Động ✅
```
Đã triển khai thủ công (không dùng package):
- GET /sitemap.xml → SitemapController@index
- Include: static pages, category pages, article pages
- Tự động cập nhật (real-time từ DB)
- Đã khai báo trong robots.txt
```
- **Trạng thái:** ✅ **Hoàn thành** — không cần cài `spatie/laravel-sitemap`

#### 3.2 RSS Feed ✅
```
Đã triển khai thủ công (không dùng package):
- GET /feed → RssFeedController@index
- 30 bài mới nhất, RSS 2.0 với Atom namespace
- Có enclosure ảnh, category, guid
- atom:link self-reference chuẩn
```
- **Trạng thái:** ✅ **Hoàn thành** — không cần cài `spatie/laravel-feed`

#### 3.3 SEO Meta Tags + Open Graph ✅
```
Đã triển khai trong index.blade.php (layout chính):
- <title>, <meta description> (yield per-page)
- og:title, og:image, og:description, og:type, og:url, og:locale, og:image:width/height
- twitter:card, twitter:site, twitter:title, twitter:description, twitter:image
- <link rel="canonical">
- Schema.org NewsArticle JSON-LD (trên trang detail)
```
- **Trạng thái:** ✅ **Hoàn thành** — không cần cài `artesaos/seotools`

#### 3.4 Social Share Buttons ✅
```
Đã triển khai trên trang detail:
- Facebook, Zalo, Twitter/X, Copy link
- Hai vị trí: trên (dưới tiêu đề) + dưới (cuối bài)
```
- **Trạng thái:** ✅ **Hoàn thành**

#### 3.5 Google Analytics / Tag Manager ✅
```
GTM-59BJV9S4 đã được nhúng vào index.blade.php
```
- **Trạng thái:** ✅ **Hoàn thành**

#### 3.6 Ảnh với Lazy Load + WebP ✅
```
- Laravel Intervention Image để resize/optimize
- Convert upload → WebP tự động
- Thêm loading="lazy" cho img tag
- Tạo nhiều kích thước: thumbnail (300px), medium (600px), full
```
- **Trạng thái:** ✅ Hoàn thành — `loading="lazy" decoding="async"` tất cả ảnh below-the-fold, `loading="eager"` hero image

---

### 🔧 Phase 4 — Nội Dung Đa Phương Tiện (Tháng 7–8)
> **Mục tiêu:** Mở rộng định dạng nội dung như các báo lớn

#### 4.1 Nhúng Video (YouTube/Vimeo) ✅
```
Thêm cột vào products:
- video_url VARCHAR(500) NULLABLE ✅

Tự động embed từ YouTube URL ✅ (accessor getVideoEmbedUrlAttribute)
```
- **Trạng thái:** ✅ Hoàn thành — migration, admin form, frontend 16:9 responsive

#### 4.2 Thư Viện Ảnh (Photo Gallery) ✅
```
img_products đã có sẵn → lightbox UI tự viết ✅
Keyboard navigation (arrow keys, Escape) ✅
```
- **Trạng thái:** ✅ Hoàn thành — gallery grid + lightbox overlay thuần JS

#### 4.3 Infographic / Bài Dài (Long-form) ⬜
```
Thêm rich text editor: TipTap hoặc CKEditor 5
Hỗ trợ: heading, quote, callout box, table, embed
```
- **Trạng thái:** ⬜ Chưa làm (CKEditor 4 cơ bản đang dùng)

#### 4.4 Live Blog / Tường thuật trực tiếp ✅
```
Bảng: live_updates (id, article_id, content, admin_id, is_pinned, posted_at) ✅
Polling mỗi 30 giây (fetch JS) ✅
Admin UI: manage.blade.php + toggle bật/tắt từ edit bài ✅
```
- **Trạng thái:** ✅ Hoàn thành — migration, model, controller, views, polling frontend

---

### 🤖 Phase 5 — Tự Động Hóa & API (Tháng 9–10)
> **Mục tiêu:** Vận hành hiệu quả, mở rộng sang mobile/đối tác

#### 5.1 REST API Công Khai ✅
```
GET  /api/v1/articles              ✅
GET  /api/v1/articles/{slug}       ✅
GET  /api/v1/categories            ✅
GET  /api/v1/categories/{id}/articles ✅
GET  /api/v1/search?q=...          ✅
GET  /api/v1/tags                  ✅
GET  /api/v1/tags/{slug}/articles  ✅
```
- **Trạng thái:** ✅ Hoàn thành

#### 5.2 Scheduled Jobs (Cron) ✅
```
- vnkr:trending (hourly) ✅
- vnkr:cleanup (weekly) ✅
- vnkr:ping-sitemap (daily) ✅
- Cleanup expired breaking news (hourly) ✅
Cron entry: * * * * * php artisan schedule:run ✅
```
- **Trạng thái:** ✅ Hoàn thành — Kernel.php + crontab

#### 5.3 Queue Jobs ✅
```
QUEUE_CONNECTION=redis ✅
Supervisor: 2 workers redis queue ✅
```
- **Trạng thái:** ✅ Hoàn thành

#### 5.4 Push Notification (Web Push) ⬜
```
Package: laravel-notification-channels/webpush
Service Worker đăng ký trên browser
Admin gửi notification khi có breaking news
```
- **Trạng thái:** ⬜ Chưa làm — xây dựng cùng PWA (Phase 8)

---

### ✅ Phase 6 — Cộng Đồng & Analytics (Tháng 11–12) — HOÀN THÀNH
> **Mục tiêu:** Tăng cường cộng đồng, insight dữ liệu, phát triển bền vững phi thương mại

#### 6.1 Thông Báo Cộng Đồng ✅
```
Vị trí: sidebar_top, sidebar_bottom, in_article, footer_banner, header_banner ✅
Admin CRUD: /admin/ad-slots (Thông Báo Cộng Đồng) ✅
Nhúng: sidebar detail, footer ✅
Cache 10 phút, bật/tắt không mất code ✅
CHỈ dùng cho: thông báo sự kiện, lời kêu gọi đóng góp, banner cộng đồng phi thương mại
KHÔNG dùng cho: quảng cáo thương mại, Google AdSense, affiliate
```
- **Trạng thái:** ✅ Hoàn thành

#### 6.2 Thống Kê Nội Bộ (Analytics Dashboard) ✅
```
- Bài đọc nhiều nhất 24h/7d/mọi thời gian ✅
- Top danh mục (số bài + tổng lượt xem) ✅
- Chart lượt xem & bài đăng 14 ngày (Chart.js) ✅
- Top từ khóa tìm kiếm nội bộ (search_logs) ✅
- Newsletter stats (total/confirmed/unsub/rate) ✅
- Trending 24h cache ✅
```
- **Trạng thái:** ✅ Hoàn thành

#### 6.3 Hệ Thống Đóng Góp Tự Nguyện ⬜
```
VNKR không thu phí bắt buộc dưới bất kỳ hình thức nào.
Tất cả nội dung, tính năng đều miễn phí 100%.

Nếu cộng đồng muốn hỗ trợ vận hành (tự nguyện):
- Nút "Ủng hộ PTB" (Ko-fi / MoMo) — hoàn toàn không bắt buộc
- Không có nội dung ẩn, không có paywall
- Không có phân biệt premium vs free
```
- **Trạng thái:** ⬜ Tùy chọn — chỉ triển khai khi cộng đồng đề xuất

---

### ✅ Phase 7 — Github/Docs Features (Tháng 01–03/2027)
> **Mục tiêu:** Nâng chất lượng nội dung & developer experience — lấy ý tưởng từ github/docs (20k⭐)

#### 7.1 Content Linter (VKR### rules) ✅
```
vnkr:lint — 19 rules VKR001–VKR081
vnkr:lint --fix — tự sửa slug, author_id, reading_time
vnkr:lint --errors / --format=json / --id=N
```
- **Trạng thái:** ✅ Hoàn thành — `app/Console/Commands/ContentLint.php`

#### 7.2 DocStat CLI ✅
```
vnkr:docstat — tổng quan toàn bộ
vnkr:docstat --id=N / --slug=S / --top=10 / --format=json
```
- **Trạng thái:** ✅ Hoàn thành — `app/Console/Commands/DocStat.php`

#### 7.3 Journeys (Lộ Trình Đọc) ✅
```
Bảng: journeys + journey_article pivot
Admin CRUD: /admin/journeys (index, create, edit, destroy) ✅
Frontend: /journey/{slug} — danh sách bài theo thứ tự + hero + CTA ✅
Model: Journey::getNavFor($articleId) → prev/next ✅
```
- **Trạng thái:** ✅ Hoàn thành

#### 7.4 Database Redirects ✅
```
Bảng: redirects (from_path, to_path, status_code, is_active, hit_count)
Middleware: HandleDatabaseRedirects — tra cứu DB + cache 10 phút ✅
Admin CRUD: /admin/redirects (index, create, toggle, destroy) ✅
```
- **Trạng thái:** ✅ Hoàn thành

#### 7.5 Reusables (Snippet Tái Sử Dụng) ✅
```
Bảng: reusables (slug, title, content, type, is_active)
Cú pháp nhúng: {{reusable:slug}} trong nội dung bài viết ✅
Admin CRUD: /admin/reusables (index, create, edit, destroy) ✅
Reusable::render($html) — inject vào detail.blade.php ✅
```
- **Trạng thái:** ✅ Hoàn thành

#### 7.6 Events / Observability ✅
```
Bảng: events (type, request_uuid, path, article_id, search_query, ...)
Event::record(TYPE_PAGE_VIEW) — DetailController ✅
Event::record(TYPE_SEARCH)    — SearchController ✅
RequestObservability middleware — UUID + logfmt ✅
Dashboard: eventToday / eventThisWeek counters ✅
```
- **Trạng thái:** ✅ Hoàn thành

#### 7.7 Search Autocomplete ✅
```
GET /api/search/autocomplete?q= — SearchAutocompleteController ✅
header.blade.php — dropdown UI, debounce 180ms, keyboard nav ✅
```
- **Trạng thái:** ✅ Hoàn thành

#### 7.8 Staged Publishing ✅
```
Cột: status (draft | review | published | archived) trong products ✅
Admin create/edit — dropdown status ✅
Dashboard — draftCount / reviewCount cards ✅
Product index — badge màu sắc theo trạng thái ✅
```
- **Trạng thái:** ✅ Hoàn thành

#### 7.9 Reading Time ✅
```
Cột: reading_time (int, phút) trong products ✅
ContentMetricsService::readingTime() + updateReadingTime() ✅
detail.blade.php — "⏳ N phút đọc" trong article meta ✅
vnkr:lint --fix — tự tính và lưu reading_time ✅
```
- **Trạng thái:** ✅ Hoàn thành

#### 7.10 3 Danh Mục Cộng Đồng Đặc Biệt ✅
```
thao-luan     → discuss.blade.php   (forum-style) ✅
tai-tro-dong-gop → sponsor.blade.php (campaign) ✅
web3-crypto   → web3.blade.php      (market dashboard) ✅
HomeController::resultBySlug() specialViews map ✅
```
- **Trạng thái:** ✅ Hoàn thành

---

### ✅ Phase 8 — PWA & Offline Support (Tháng 04/2027)
> **Mục tiêu:** Trải nghiệm như native app — cài được trên điện thoại, đọc được khi mất mạng

#### 8.1 PWA Manifest ✅
```
public/manifest.json — name, icons, shortcuts (Tin Mới / Thảo Luận) ✅
<link rel="manifest"> + <meta theme-color> trong index.blade.php ✅
Apple touch icon + mobile-web-app-capable ✅
```
- **Trạng thái:** ✅ Hoàn thành

#### 8.2 Service Worker ✅
```
public/sw.js — Cache-first tài sản tĩnh, Network-first trang HTML ✅
Precache: /, /offline, Bootstrap CSS, favicon ✅
Offline fallback: /offline.html + route /offline ✅
Auto-update check mỗi 60 giây ✅
Không can thiệp vào /admin, /api, /horizon ✅
```
- **Trạng thái:** ✅ Hoàn thành

#### 8.3 Web Push Notifications ⬜
```
Package: laravel-notification-channels/webpush
VAPID keys + subscription endpoint
Admin: gửi push khi tạo breaking news mới
```
- **Trạng thái:** ⬜ Chưa làm — cần cài package

---

## 5. Thiết Kế Kiến Trúc Mở Rộng

```
                        ┌─────────────────────────────────────┐
                        │           NGINX 1.24                │
                        │    vnkr.vn  (HTTPS + HTTP/2)        │
                        └──────────────┬──────────────────────┘
                                       │
              ┌───────────────────────┼───────────────────────┐
              │                       │                       │
    ┌─────────▼──────────┐  ┌────────▼────────┐  ┌──────────▼────────┐
    │  Laravel App        │  │  Laravel Queue  │  │  Laravel Schedule │
    │  PHP-FPM 8.2        │  │  (redis driver) │  │  (cron + artisan) │
    │  /var/www/vnkr.vn   │  └────────┬────────┘  └──────────┬────────┘
    └─────────┬──────────┘           │                       │
              │                 ┌────▼──────────────────┐    │
    ┌─────────▼──────────┐      │  Workers (Jobs)       │    │
    │     Redis Cache     │      │  - Email sender       │    │
    │  (Sessions/Cache/   │◄─────┤  - Image resizer      │    │
    │     Queues)   ✅    │      │  - Search indexer     │    │
    └─────────────────────┘      └───────────────────────┘    │
              │                                               │
    ┌─────────▼──────────┐                        ┌──────────▼──────┐
    │   MySQL 8.0         │                        │  Meilisearch    │
    │   vnkr_db           │                        │  (full-text)    │
    └─────────────────────┘                        └─────────────────┘
              │
    ┌─────────▼──────────┐
    │  Storage / CDN      │
    │  (images + files)   │
    └─────────────────────┘
```

---

## 6. Kế Hoạch Database Mở Rộng

### Bảng mới cần tạo

```sql
-- Bình luận chuyển DB
CREATE TABLE comments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    article_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    parent_id BIGINT UNSIGNED NULL,
    content TEXT NOT NULL,
    likes INT DEFAULT 0,
    is_approved BOOLEAN DEFAULT 1,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (article_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Tags
CREATE TABLE tags (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

CREATE TABLE article_tag (
    article_id BIGINT UNSIGNED,
    tag_id BIGINT UNSIGNED,
    PRIMARY KEY (article_id, tag_id)
);

-- Bookmark
CREATE TABLE bookmarks (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    article_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP,
    UNIQUE KEY (user_id, article_id)
);

-- Newsletter
CREATE TABLE newsletter_subscribers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    token VARCHAR(64) NULL,
    confirmed_at TIMESTAMP NULL,
    unsubscribed_at TIMESTAMP NULL,
    created_at TIMESTAMP
);

-- Breaking news
CREATE TABLE breaking_news (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(500) NOT NULL,
    url VARCHAR(500) NULL,
    is_active BOOLEAN DEFAULT 1,
    expired_at TIMESTAMP NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Reading history
CREATE TABLE reading_history (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    article_id BIGINT UNSIGNED NOT NULL,
    read_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Live blog updates
CREATE TABLE live_updates (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    article_id BIGINT UNSIGNED NOT NULL,
    content TEXT NOT NULL,
    admin_id BIGINT UNSIGNED NOT NULL,
    posted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (article_id) REFERENCES products(id)
);

-- Ad slots
CREATE TABLE ad_slots (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    position VARCHAR(50) NOT NULL,
    code TEXT NOT NULL,
    is_active BOOLEAN DEFAULT 1,
    updated_at TIMESTAMP
);
```

### Cột cần thêm vào bảng hiện có

```sql
-- Thêm vào products (bài viết)
ALTER TABLE products
    ADD COLUMN view_count INT UNSIGNED DEFAULT 0 AFTER stock,
    ADD COLUMN like_count INT UNSIGNED DEFAULT 0 AFTER view_count,
    ADD COLUMN is_featured BOOLEAN DEFAULT 0 AFTER like_count,
    ADD COLUMN is_published BOOLEAN DEFAULT 1 AFTER is_featured,
    ADD COLUMN published_at TIMESTAMP NULL AFTER is_published,
    ADD COLUMN author_id BIGINT UNSIGNED NULL AFTER published_at,
    ADD COLUMN content_type ENUM('text','video','gallery','podcast') DEFAULT 'text' AFTER author_id,
    ADD COLUMN video_url VARCHAR(500) NULL AFTER content_type,
    ADD COLUMN is_premium BOOLEAN DEFAULT 0 AFTER video_url,
    ADD COLUMN read_time TINYINT UNSIGNED NULL AFTER is_premium,
    ADD COLUMN meta_title VARCHAR(255) NULL AFTER read_time,
    ADD COLUMN meta_description VARCHAR(500) NULL AFTER meta_title,
    ADD FULLTEXT INDEX ft_search (name, tomtat, description);

-- Thêm vào users (tác giả)
ALTER TABLE users
    ADD COLUMN avatar VARCHAR(255) NULL AFTER email,
    ADD COLUMN bio TEXT NULL AFTER avatar,
    ADD COLUMN username VARCHAR(100) UNIQUE NULL AFTER name,
    ADD COLUMN facebook_url VARCHAR(255) NULL AFTER bio,
    ADD COLUMN twitter_url VARCHAR(255) NULL AFTER facebook_url,
    ADD COLUMN is_author BOOLEAN DEFAULT 0 AFTER twitter_url;
```

---

## 7. Kế Hoạch Triển Khai Chi Tiết

### Timeline 12 Tháng (Cập nhật trạng thái)

```
Q1 (Tháng 1-3): Foundation & Core Fix
├── Tháng 1: ✅ Redis cache/session — ✅ Sitemap XML — ✅ RSS Feed
│            ✅ SEO meta/OG/Twitter Card — ✅ Schema.org JSON-LD
│            ✅ Social share buttons — ✅ robots.txt — ✅ Related articles
│            ⬜ Comments DB migration — ⬜ View count columns — ⬜ FULLTEXT search
├── Tháng 2: ⬜ Tags — ⬜ Author profiles — ⬜ Application-level caching (trang chủ/detail)
└── Tháng 3: ⬜ Lazy loading + WebP — ⬜ Fix robots (category slugs)

Q2 (Tháng 4-6): User Engagement
├── Tháng 4: ⬜ Bookmark + Reading history + Newsletter
├── Tháng 5: ⬜ Breaking news ticker
└── Tháng 6: ⬜ Rich text editor (TipTap) + Video embed + Gallery

Q3 (Tháng 7-9): Distribution & Automation
├── Tháng 7: ⬜ REST API v1 + Sanctum auth
├── Tháng 8: ⬜ Queue jobs (redis) + Scheduled tasks + Push notification
└── Tháng 9: ⬜ Admin analytics dashboard + Top articles trending

Q4 (Tháng 10-12): Cộng Đồng & Scale
├── Tháng 10: ✅ Thông báo cộng đồng (ad slots dùng cho community notices)
├── Tháng 11: ⬜ Push notification + PWA
└── Tháng 12: ⬜ Mobile optimization + Performance audit + Đóng góp tự nguyện (tùy chọn)
```

### Ưu Tiên Theo Độ Khẩn Cấp (Cập nhật)

| Độ ưu tiên | Tính năng | Thời gian | Trạng thái |
|---|---|---|---|
| ✅ Done | **UFW firewall bật** | 2 phút | ✅ Hoàn thành |
| ✅ Done | **PHP: memory_limit=256M, max_exec=60, expose_php=Off** | 5 phút | ✅ Hoàn thành |
| ✅ Done | **Swap 2GB** | — | ✅ Đã có (sẵn) |
| ✅ Done | **.env 640 + QUEUE_CONNECTION=redis** | 1 phút | ✅ Hoàn thành |
| ✅ Done | **Fix route duplicates** | — | ✅ Hoàn thành |
| ✅ Done | **Comments → DB migration + controller + view** | — | ✅ Hoàn thành |
| ✅ Done | **View count + like_count + is_featured + published_at** | — | ✅ Hoàn thành |
| ✅ Done | **FULLTEXT search + paginate + relevance** | — | ✅ Hoàn thành |
| ✅ Done | **Application-level caching + cache invalidation** | — | ✅ Hoàn thành |
| ✅ Done | **Tags system (migration + model + admin + frontend)** | — | ✅ Hoàn thành |
| ✅ Done | **Lazy loading ảnh (eager hero / lazy below-fold)** | — | ✅ Hoàn thành |
| ✅ Done | Redis cache/session | — | ✅ Hoàn thành |
| ✅ Done | Sitemap XML | — | ✅ Hoàn thành |
| ✅ Done | RSS Feed | — | ✅ Hoàn thành |
| ✅ Done | SEO meta + OG + JSON-LD | — | ✅ Hoàn thành |
| ✅ Done | Social share buttons | — | ✅ Hoàn thành |
| ✅ Done | robots.txt chuẩn | — | ✅ Hoàn thành |
| ✅ Done | Canonical URL | — | ✅ Hoàn thành |
| ✅ Done | Bài viết liên quan | — | ✅ Hoàn thành |
| 🟡 P1 | Author profiles (/author/{username}) | 3 ngày | ⬜ Chưa làm |
| 🟢 P2 | Newsletter | 5 ngày | ⬜ Chưa làm |
| 🟢 P2 | Breaking news ticker | 2 ngày | ⬜ Chưa làm |
| 🟢 P2 | Video embed (video_url column) | 2 ngày | ⬜ Chưa làm |
| 🟢 P2 | Queue worker chạy daemon | 1 ngày | ⬜ Chưa làm |
| 🟢 P2 | REST API v1 | 5 ngày | ⬜ Chưa làm |
| 🔵 P3 | Analytics dashboard | 5 ngày | ⬜ Chưa làm |
| 🔵 P3 | Đóng góp tự nguyện Ko-fi/MoMo (không bắt buộc) | 2 ngày | ⬜ Tùy chọn |
| 🔵 P3 | Push notification | 3 ngày | ⬜ Chưa làm |
| 🔵 P3 | PWA | 5 ngày | ⬜ Chưa làm |

---

## 8. KPI & Mục Tiêu Đo Lường

### Kỹ Thuật

| Chỉ số | Hiện tại | Mục tiêu 6 tháng | Mục tiêu 12 tháng |
|---|---|---|---|
| Thời gian tải trang | ~130–200ms | < 100ms (với caching) | < 80ms |
| Lighthouse Performance | ~50 | > 75 | > 90 |
| Core Web Vitals (LCP) | Chưa đo | < 2.5s | < 2s |
| Uptime | 99% | 99.5% | 99.9% |
| Cache hit rate | ~0% (Redis setup, chưa dùng trong app) | > 60% | > 80% |
| Google Index coverage | Chưa đo | > 80% bài viết | > 95% bài viết |

### Nội Dung & Người Dùng

| Chỉ số | Mục tiêu 3 tháng | Mục tiêu 6 tháng | Mục tiêu 12 tháng |
|---|---|---|---|
| Số bài viết/tuần | 5 | 20 | 50 |
| Người dùng đăng ký | 100 | 1,000 | 10,000 |
| Newsletter subscribers | 50 | 500 | 5,000 |
| Bình luận/bài | 0 | 3 | 10 |
| Pageviews/tháng | 1,000 | 10,000 | 100,000 |
| Organic traffic (%) | 10% | 30% | 50% |

### SEO

| Chỉ số | Mục tiêu |
|---|---|
| Số trang được index Google | > 80% bài viết |
| Sitemap submit | ✅ Đã có — cần submit Google Search Console |
| Google Search Console setup | Ưu tiên ngay |
| Open Graph share preview | ✅ Đã hoạt động |
| Bài lên top 10 Google | 5 bài từ khóa dài (tháng 6) |
| Bài lên top 3 Google | 1 bài từ khóa dài (tháng 12) |

---

## Phụ Lục: Package Laravel Khuyến Nghị

| Package | Mục đích | Phase | Trạng thái |
|---|---|---|---|
| ~~`spatie/laravel-sitemap`~~ | ~~Sitemap tự động~~ | P1 | ✅ Tự viết — không cần |
| ~~`spatie/laravel-feed`~~ | ~~RSS feed~~ | P1 | ✅ Tự viết — không cần |
| ~~`artesaos/seotools`~~ | ~~SEO meta tags~~ | P1 | ✅ Tự viết — không cần |
| `spatie/laravel-tags` | Hệ thống tags | P1 | ⬜ Chưa cài |
| `intervention/image` | Xử lý ảnh + WebP | P2 | ⬜ Chưa cài |
| `laravel/scout` + `meilisearch` | Full-text search | P1 | ⬜ Chưa cài |
| `spatie/laravel-permission` | Phân quyền nâng cao | P1 | ⬜ Chưa cài |
| `laravel/horizon` | Queue monitoring | P2 | ⬜ Chưa cài |
| `laravel-notification-channels/webpush` | Push notification | P3 | ⬜ Chưa cài |
| `tightenco/ziggy` | JS route helper | P1 | ⬜ Chưa cài |
| `livewire/livewire` | Realtime UI (comments) | P2 | ⬜ Chưa cài |

---

## Phụ Lục: Checklist Bảo Mật & Hạ Tầng Khẩn Cấp

> Những việc có thể làm trong dưới 15 phút, tác động ngay lập tức

```bash
# 1. Bật UFW firewall (2 phút)
ufw allow 22 && ufw allow 80 && ufw allow 443 && ufw enable

# 2. Fix PHP security (5 phút)
# /etc/php/8.2/fpm/php.ini:
# expose_php = Off
# memory_limit = 256M
# max_execution_time = 60
# upload_max_filesize = 10M
# post_max_size = 12M
systemctl restart php8.2-fpm

# 3. Tạo Swap 2GB (5 phút)
fallocate -l 2G /swapfile
chmod 600 /swapfile
mkswap /swapfile
swapon /swapfile
echo '/swapfile none swap sw 0 0' >> /etc/fstab

# 4. Fix .env permissions
chmod 640 /var/www/vnkr.vn/.env

# 5. Chuyển queue sang Redis (1 phút)
# .env: QUEUE_CONNECTION=redis
# Sau đó chạy: php artisan queue:work --daemon &

# 6. Nâng PHP-FPM workers (2 phút)
# /etc/php/8.2/fpm/pool.d/www.conf:
# pm.max_children = 15
systemctl restart php8.2-fpm
```

---

*Tài liệu này được tạo và cập nhật tự động bởi phân tích codebase VNexpress24h tại `/var/www/vnkr.vn`*  
*Phiên bản 1.0: 22/09/2026 22:03 | Phiên bản 2.0: 23/09/2026 00:03*
