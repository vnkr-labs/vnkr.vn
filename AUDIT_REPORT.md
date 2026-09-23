# 🔍 BÁO CÁO KIỂM TOÁN TOÀN DIỆN — VNKR.VN
## Audit Report v1.0

> **Ngày kiểm tra:** 22/09/2026  
> **Phạm vi:** Server · Laravel App · Security · Performance · SEO · Content · Legal  
> **Kết quả tổng:** ⚠️ **CẦN CẢI THIỆN** — 6 lỗi nghiêm trọng, 11 cảnh báo, 14 điểm tốt

---

## BẢNG ĐIỂM TỔNG QUAN

| Hạng mục | Điểm | Đánh giá |
|---|---|---|
| 🔒 Bảo mật | 52/100 | ⚠️ Trung bình — cần vá ngay |
| ⚡ Hiệu năng | 61/100 | ⚠️ Khá — còn nhiều dư địa |
| 🔎 SEO | 45/100 | 🔴 Yếu — thiếu nhiều yếu tố cốt lõi |
| 🏗️ Cơ sở hạ tầng | 74/100 | ✅ Tốt — ổn định |
| 📦 Laravel App | 66/100 | ⚠️ Khá — có lỗ hổng kỹ thuật |
| 📝 Nội dung | 40/100 | 🔴 Yếu — mới ở mức seed data |
| ⚖️ Pháp lý | 80/100 | ✅ Tốt — đã có khung cơ bản |
| **TỔNG** | **60/100** | **⚠️ CẦN CẢI THIỆN** |

---

## 1. 🔒 BẢO MẬT — 52/100

### 🔴 Lỗi Nghiêm Trọng (Cần vá trong 24h)

#### SEC-01: CVE-2026-45065 — Symfony UrlGenerator Vulnerability
- **Mức độ:** CRITICAL
- **Gói bị ảnh hưởng:** symfony/routing (dùng bởi Laravel Framework 10.x)
- **Mô tả:** Lỗ hổng cho phép bypass route requirement regex → URL injection
- **Fix:** `composer update laravel/framework`

#### SEC-02: PHP expose_php = On
- **Mức độ:** HIGH
- **Mô tả:** PHP đang tiết lộ phiên bản qua HTTP header `X-Powered-By: PHP/8.2.33`
- **Fix:** Set `expose_php = Off` trong `/etc/php/8.2/fpm/php.ini`

#### SEC-03: PHP memory_limit = -1 (Vô giới hạn)
- **Mức độ:** HIGH
- **Mô tả:** Không giới hạn RAM cho mỗi PHP process → DoS risk, 1 request có thể ăn hết 3.8GB RAM
- **Fix:** Set `memory_limit = 256M`

#### SEC-04: PHP max_execution_time = 0 (Vô giới hạn)
- **Mức độ:** HIGH
- **Mô tả:** Request không bao giờ timeout → server treo vô thời hạn
- **Fix:** Set `max_execution_time = 60`

#### SEC-05: Firewall = INACTIVE
- **Mức độ:** HIGH
- **Mô tả:** UFW đang tắt, không có tường lửa bảo vệ cổng
- **Fix:** `ufw allow 22 && ufw allow 80 && ufw allow 443 && ufw enable`

#### SEC-06: .env file permissions = 755 (executable)
- **Mức độ:** MEDIUM
- **Mô tả:** File .env có thể thực thi — không cần thiết, nên là 640
- **Fix:** `chmod 640 /var/www/vnkr.vn/.env`

### ⚠️ Cảnh Báo Bảo Mật

#### SEC-07: 274 lần bot scan trong log
- Bot đang liên tục probe: `.env`, `wp-admin`, `phpMyAdmin`, `.git`
- Nginx đang chặn đúng (403) nhưng tốn tài nguyên xử lý
- **Gợi ý:** Cài Fail2ban để block IP tấn công

#### SEC-08: Thiếu Content-Security-Policy header
- Chưa có CSP header → XSS risk
- **Gợi ý:** Thêm vào Nginx config

#### SEC-09: Thiếu HSTS header
- Chưa có `Strict-Transport-Security` → downgrade attack risk
- **Gợi ý:** Thêm vào Nginx config

#### SEC-10: Không có Swap memory
- RAM 3.8GB, Swap = 0 → Nếu hết RAM, server crash ngay
- **Gợi ý:** Tạo 2GB swap file

#### SEC-11: Bình luận lưu JSON file (không phải DB)
- File JSON trong storage có thể bị path traversal nếu slug không được sanitize đúng
- **Gợi ý:** Di chuyển sang Database MySQL

### ✅ Điểm Tốt Bảo Mật
- HTTPS với Let's Encrypt ✅ (hết hạn 21/12/2026)
- Certbot auto-renew đã cài ✅
- MySQL lắng nghe 127.0.0.1 (không public) ✅
- .env không accessible từ web ✅
- Hidden files blocked bởi Nginx ✅

---

## 2. ⚡ HIỆU NĂNG — 61/100

### 🔴 Vấn Đề Hiệu Năng Nghiêm Trọng

#### PERF-01: CACHE_DRIVER = file (không phải Redis)
- **Mức độ:** HIGH
- Redis đã được cài (`php8.2-redis` ✅) nhưng Laravel đang dùng file cache
- Mọi request đều đọc/ghi file thay vì RAM → chậm hơn 5–10x
- **Fix:** Set `CACHE_DRIVER=redis` trong `.env`

#### PERF-02: SESSION_DRIVER = file
- Session được lưu file → I/O disk mỗi request
- **Fix:** Set `SESSION_DRIVER=redis`

#### PERF-03: Không có Swap memory
- RAM chỉ còn 493MB free
- **Fix:** Tạo 2GB swapfile

### ⚠️ Cảnh Báo Hiệu Năng

#### PERF-04: PHP-FPM pm.max_children = 5 (quá thấp)
- Chỉ xử lý được 5 request PHP đồng thời
- Server có 2 CPU + 3.8GB RAM → có thể nâng lên 15–20
- **Gợi ý:** Tăng `pm.max_children = 15`

#### PERF-05: Response time ~130–200ms (có thể tốt hơn)
- Hiện tại: 130–200ms TTFB
- Sau khi bật Redis cache: kỳ vọng < 50ms cho cached pages

#### PERF-06: OPcache không hoạt động trên CLI mode
- `opcache.enable_cli = Off` → artisan commands chậm
- Không ảnh hưởng web request (opcache.enable = On ✅)

#### PERF-07: upload_max_filesize = 2M (quá nhỏ)
- Admin upload ảnh bài viết sẽ bị giới hạn 2MB
- **Gợi ý:** Tăng lên 10M

### ✅ Điểm Tốt Hiệu Năng
- OPcache bật ✅
- Static files cache 30 ngày (Nginx) ✅
- HTTPS/2 qua Cloudflare ✅
- Disk còn 64GB / 67GB (5% used) ✅
- Response time < 200ms (chấp nhận được) ✅

---

## 3. 🔎 SEO — 45/100

### 🔴 Vấn Đề SEO Nghiêm Trọng

#### SEO-01: Không có Sitemap XML
- **Mức độ:** CRITICAL cho SEO
- Google chưa biết cấu trúc site → index chậm
- **Fix:** Cài `spatie/laravel-sitemap`, tạo `/sitemap.xml`

#### SEO-02: robots.txt trống (Disallow: rỗng)
- Hiện tại cho crawl TẤT CẢ kể cả `/admin`, `/api`
- **Fix:** Chặn `/admin`, `/api`, thêm `Sitemap:` directive

#### SEO-03: Không có Structured Data (Schema.org)
- Bài viết không có `NewsArticle` JSON-LD
- Google không nhận dạng được loại content
- **Fix:** Thêm JSON-LD vào detail.blade.php

#### SEO-04: Open Graph image chưa tự động
- `og:image` chưa được set với ảnh bài viết thực tế
- Share lên Facebook không có preview ảnh

### ⚠️ Cảnh Báo SEO

#### SEO-05: Meta description chưa tối ưu
- Dùng `@yield('meta_description')` nhưng các trang không set giá trị

#### SEO-06: Tìm kiếm dùng LIKE query
- `LIKE '%keyword%'` không dùng index → chậm khi có nhiều bài
- **Gợi ý:** MySQL FULLTEXT index hoặc Meilisearch

#### SEO-07: URL chuyên mục dùng ID (không phải slug)
- `/result/1` → không thân thiện SEO
- Nên là `/chuyen-muc/thoi-su`

#### SEO-08: Thiếu canonical URL tag

#### SEO-09: Google Search Console chưa được verify (chưa biết từ audit)

### ✅ Điểm Tốt SEO
- HTTPS ✅
- Slug URL thân thiện cho bài viết ✅
- Mobile responsive ✅
- Be Vietnam Pro font load từ Google Fonts ✅
- GTM-59BJV9S4 đã cài ✅

---

## 4. 🏗️ CƠ SỞ HẠ TẦNG — 74/100

### Thông Số Server

| Thành phần | Giá trị | Đánh giá |
|---|---|---|
| OS | Ubuntu 24.04 LTS | ✅ Modern, LTS đến 2029 |
| CPU | 2x Intel Xeon 2.5GHz | ✅ Đủ cho giai đoạn đầu |
| RAM | 3.8GB (1.4GB used, 493MB free) | ⚠️ Cần thêm swap |
| Disk | 67GB (5% used = 3.2GB) | ✅ Rất thoải mái |
| Swap | 0B | 🔴 Không có — rủi ro crash |
| Nginx | 1.24.0 | ✅ |
| PHP-FPM | 8.2.33 | ✅ Latest stable |
| MySQL | 8.0.46 | ✅ Latest 8.0 |
| SSL | Let's Encrypt (hết hạn 21/12/2026) | ✅ Auto-renew |

### ✅ Điểm Tốt Infrastructure
- Backup MySQL hàng ngày lúc 2:00 AM ✅
- Tất cả services auto-start (systemctl enabled) ✅
- Nginx config valid ✅
- HTTP → HTTPS redirect ✅
- MySQL chỉ lắng nghe localhost ✅
- Cloudflare proxy (bảo vệ IP thật) ✅

---

## 5. 📦 LARAVEL APP — 66/100

### 🔴 Vấn Đề Nghiêm Trọng

#### APP-01: CVE-2026-45065 trong symfony/routing
- Xem SEC-01 ở trên

#### APP-02: Laravel Framework 10.x → 12.x available
- Major version upgrade khả dụng
- **Gợi ý:** Lên plan nâng cấp lên Laravel 11/12

#### APP-03: Route duplicate — user.show conflict
- Route `/admin/admin/users/{user}` bị conflict với `user.show`
- `php artisan route:cache` sẽ fail
- **Fix:** Xem xét lại route definition trong web.php

#### APP-04: Bình luận lưu file JSON (không phải DB)
- 6 file JSON hiện có trong storage/comments/
- Dữ liệu có thể mất, không thể query, không backup đầy đủ
- **Fix:** Migration → bảng `comments` trong MySQL

### ⚠️ Cảnh Báo App

#### APP-05: QUEUE_CONNECTION = sync
- Job chạy đồng bộ → làm chậm response nếu có task nặng
- **Gợi ý:** Đổi sang Redis queue

#### APP-06: Session lifetime = 120 phút (ngắn)
- Người dùng bị đăng xuất sau 2h không hoạt động
- **Gợi ý:** Tăng lên 480 phút (8h)

#### APP-07: Guzzle 7.x → 8.x major update available

### ✅ Điểm Tốt App
- APP_DEBUG = false (production mode) ✅
- APP_ENV = production ✅
- APP_KEY được set ✅
- Composer packages installed ✅
- View cache hoạt động ✅
- Config cache hoạt động ✅

---

## 6. 📝 NỘI DUNG — 40/100

### Thống Kê Nội Dung Hiện Tại

| Chỉ số | Giá trị | Đánh giá |
|---|---|---|
| Tổng bài viết | 19 bài | 🔴 Rất ít — cần ít nhất 100+ |
| Danh mục | 8 | ✅ Đủ ban đầu |
| Người dùng | 1 (admin) | ⚠️ Chỉ có admin |
| Bình luận | 6 file JSON | ⚠️ Dữ liệu từ cũ |
| Ảnh thật | 0 (placeholder màu) | 🔴 Chưa có ảnh thật |
| Bài gốc PTB | 0 | 🔴 Chưa có Góc Nhìn PTB |
| Nội dung gốc | ~0% | 🔴 Toàn seed data |

### Vấn Đề Nội Dung
- Chưa có bài viết "Góc Nhìn PTB" — điểm khác biệt quan trọng nhất
- Ảnh thumbnail là placeholder màu (không chuyên nghiệp)
- 0 người dùng đăng ký thật
- Chưa có kết nối mạng xã hội thực tế

---

## 7. ⚖️ PHÁP LÝ — 80/100

### ✅ Đã Có
- Trang Giới thiệu (/gioi-thieu) ✅
- Điều khoản sử dụng (/dieu-khoan) ✅
- Chính sách bảo mật (/chinh-sach-bao-mat) ✅
- Quy chế hoạt động (/quy-che-hoat-dong) ✅
- Tuyên bố miễn trách trong footer ✅
- Hộp trích nguồn cuối bài ✅
- Không tự gọi là "báo điện tử" ✅

### ⚠️ Còn Thiếu
- Email phamthebao@vnkr.vn chưa hoạt động thực tế
- Chưa đăng ký Google Search Console (chưa xác nhận)
- Chưa có privacy policy cookie notice (GDPR-lite)

---

## TÓM TẮT — TOP 10 VIỆC CẦN LÀM NGAY

| # | Ưu tiên | Việc cần làm | Thời gian | Khó |
|---|---|---|---|---|
| 1 | 🔴 P0 | Bật UFW firewall | 2 phút | Dễ |
| 2 | 🔴 P0 | Fix PHP memory_limit + max_execution_time | 5 phút | Dễ |
| 3 | 🔴 P0 | Bật Redis cache cho Laravel | 5 phút | Dễ |
| 4 | 🔴 P0 | Tạo Swap 2GB | 5 phút | Dễ |
| 5 | 🔴 P0 | Vá CVE: composer update | 10 phút | Dễ |
| 6 | 🔴 P0 | Fix robots.txt + tạo sitemap | 15 phút | Dễ |
| 7 | 🟡 P1 | Tắt expose_php, fix .env permissions | 5 phút | Dễ |
| 8 | 🟡 P1 | Thêm HSTS + CSP headers vào Nginx | 10 phút | Trung bình |
| 9 | 🟡 P1 | Tăng upload_max_filesize + PHP-FPM workers | 5 phút | Dễ |
| 10 | 🟡 P1 | Di chuyển bình luận JSON → MySQL | 2 ngày | Trung bình |

---

*Báo cáo tạo tự động bởi: Bob AI Strategy Consultant*  
*Ngày: 22/09/2026 | Domain: vnkr.vn | Framework: Laravel 10 / PHP 8.2*
