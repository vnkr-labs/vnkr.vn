# 🎨 Redesign Log — VNexpress24H (vnkr.vn)

> **Phiên bản:** 2.0.0  
> **Ngày thực hiện:** 22/09/2026  
> **Tham khảo:** VnExpress.net — Chuẩn báo chí điện tử Việt Nam  

---

## Tổng Quan Thay Đổi

Giao diện được viết lại hoàn toàn theo phân tích chuẩn VnExpress, kế thừa kiến trúc
Laravel Blade hiện có, không thay đổi backend hay database.

---

## Files Đã Thay Đổi

| File | Thay đổi |
|---|---|
| `resources/views/fe/layouts/css.blade.php` | CSS variables, typography, toàn bộ component styles |
| `resources/views/fe/layouts/header.blade.php` | Top-bar + Main header + Sticky nav |
| `resources/views/fe/layouts/footer.blade.php` | Footer 4-cột chuẩn báo |
| `resources/views/fe/layouts/slide.blade.php` | Gỡ carousel cũ |
| `resources/views/fe/layouts/js.blade.php` | Xóa duplicate Bootstrap scripts |
| `resources/views/fe/index.blade.php` | Lang=vi, OG meta, main wrapper |
| `resources/views/fe/home.blade.php` | Toàn bộ layout trang chủ |
| `resources/views/fe/detail.blade.php` | Trang chi tiết bài viết |
| `resources/views/fe/result.blade.php` | Trang chuyên mục grid |
| `resources/views/fe/search/results.blade.php` | Trang tìm kiếm highlight |

---

## Tính Năng Giao Diện Mới

### Header
- **Top bar đỏ** với ngày giờ, link nhanh, trạng thái đăng nhập
- **Logo VNexpress24H** với typography nhận diện thương hiệu
- **Thanh tìm kiếm** rounded pill trong header
- **Sticky navigation** dính top khi scroll, border đỏ 3px dưới
- **Indicator LIVE** nhấp nháy bên phải nav
- **Dynamic menu** lấy danh mục từ DB

### Trang Chủ
- **Breaking news ticker** — chạy ngang tự động, pause khi hover
- **Vedette (Spotlight)** — bài chính lớn + overlay gradient + 3 bài phụ
- **Category blocks** — mỗi danh mục: 1 bài lớn + 4 bài list
- **Tin mới nhất** — list có thumbnail + badge danh mục
- **Sidebar widgets:**
  - Đọc nhiều nhất (numbered rank)
  - Thị trường (Vàng, Ngoại tệ)
  - Thời tiết (3 thành phố)
  - Newsletter đăng ký
  - Danh sách chuyên mục có đếm bài

### Trang Chi Tiết
- **Breadcrumb** navigation
- **Badge danh mục** + tiêu đề H1 lớn
- **Meta bar**: tác giả, thời gian, số bình luận, lượt xem
- **Share bar**: Facebook, Zalo, X (Twitter), Copy link
- **Ảnh full-width** với caption
- **Lead paragraph** (tomtat) in đậm
- **Bài liên quan** — grid 3 cột cuối bài
- **Bình luận** — avatar, like, reply, xóa, form đẹp
- **Sidebar**: tin mới nhất, cùng chuyên mục, thị trường

### Trang Chuyên Mục
- **Bài đầu full-width** nổi bật 2 cột
- **Grid 3 cột** cho các bài còn lại
- **Card hover shadow**
- **Badge danh mục** trên mỗi card

### Trang Tìm Kiếm
- **Keyword highlight** — từ khóa được tô vàng trong tiêu đề + tóm tắt
- **Empty state** khi không có kết quả
- **Gợi ý tìm kiếm** từ danh mục
- **Count** số bài tìm thấy

### Footer
- **4 cột**: Giới thiệu + Chuyên mục + Dịch vụ + Newsletter/Liên hệ
- **Social links** với Bootstrap Icons
- **Newsletter form** in-footer
- **Bottom bar** với bản quyền + policy links
- **Back-to-top button** floating

---

## Design System

```css
--brand:      #9F1B32   /* Đỏ đô nhận diện */
--brand-dark: #7a1326   /* Hover state */
--brand-light:#f9ecee   /* Background nhẹ */
--text:       #1a1a1a   /* Màu chữ chính */
--text-muted: #666      /* Màu chữ phụ */
--border:     #e5e5e5   /* Đường kẻ */
--bg:         #f5f5f5   /* Nền trang */
--font: 'Be Vietnam Pro', Arial, sans-serif
```

---

## Responsive

| Breakpoint | Layout |
|---|---|
| ≥ 992px | 2 cột: content 1fr + sidebar 300px |
| < 992px | 1 cột: sidebar xuống dưới |
| < 768px | Grid kết quả: 1 cột |
| < 767px | Vedette: 1 cột, stacked |

---

## Chú Ý Kỹ Thuật

- Toàn bộ CSS nằm trong `css.blade.php` sử dụng CSS Custom Properties
- Không dependency JS mới, giữ nguyên jQuery + Bootstrap 5
- Breaking ticker dùng CSS animation `ticker-scroll`, pause on hover
- `Str::limit()` dùng ở nhiều chỗ — đảm bảo `use Illuminate\Support\Str` trong controller nếu cần
- Comment section giữ nguyên JSON file backend — không thay đổi logic
- Tất cả query DB trong Blade được cache bởi OPcache PHP

