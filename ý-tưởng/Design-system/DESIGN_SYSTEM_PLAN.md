# 🎨 Kế Hoạch Design System — VNKR.VN

> **Nguồn tham khảo:** 7 ảnh trong thư mục `ý-tưởng/Design-system/`
> **Ngày lập:** 24/09/2026
> **Phiên bản:** 2.0 *(cập nhật sau triển khai Sprint 1–4)*
> **Áp dụng cho:** Website vnkr.vn (Laravel Blade) + PWA + Tương lai: Mobile App

---

## MỤC LỤC

1. [Phân Tích Nguồn Cảm Hứng](#1-phân-tích-nguồn-cảm-hứng)
2. [Logo & Brand Identity](#2-logo--brand-identity)
3. [Color System](#3-color-system)
4. [Typography System](#4-typography-system)
5. [Button & Form System](#5-button--form-system)
6. [UI Component Library](#6-ui-component-library)
7. [Spacing & Layout Grid](#7-spacing--layout-grid)
8. [Kế Hoạch Triển Khai vào CSS](#8-kế-hoạch-triển-khai-vào-css)
9. [Lộ Trình Thực Hiện](#9-lộ-trình-thực-hiện)

---

## 1. Phân Tích Nguồn Cảm Hứng

### Tổng Quan 7 Ảnh Đã Phân Tích

| File | Nội dung | Insight chính |
|---|---|---|
| `Color palette.png` | Hệ thống màu đầy đủ — Default tones + Brand colors + Shades | Palette đa dạng, có semantic colors (Info/Success/Warning/Error) |
| `Typography.png` | Font Work Sans — H1→H6 + Subtitle + Body + Button sizes | Hệ thống type scale rõ ràng, 3 weights |
| `Buttons & Inputs.png` | 3 variant × 8 màu: Filled / Outlined / Ghost | Pill-shaped (border-radius lớn), icon trái+phải |
| `UI kits.png` | Bottom navbar, forms, tabs, toggles, cards, list items | Mobile-first, clean/minimal |
| `App icon and logo white.png` | Logo VNKR trắng trên nền đen + wifi icon ở chữ R | Logo wordmark đơn giản, bold |
| `App icon and logo green.png` | Logo VNKR trắng trên nền đen (tương tự) | Dark mode variant |
| `Thumbnail.png` | VNKR trên nền hồng + graphic elements playful | Phong cách trẻ, năng động — dùng cho social media |

### Nhận Xét Tổng Quan

**Phong cách thiết kế tham khảo:**
- **Clean Minimal** — nền trắng, nhiều whitespace
- **Pill buttons** — border-radius lớn (~50px) thay vì square
- **Mobile-first** — UI kits tập trung vào bottom nav, mobile patterns
- **Dark mode ready** — logo có cả light/dark variant
- **Playful brand** — Thumbnail dùng màu hồng + graphic shapes vui nhộn → phù hợp cho social media nhưng không phải cho web tin tức

**Định hướng áp dụng cho VNKR.VN (tin tức):**
> Giữ **DNA clean/minimal** từ design system này, nhưng **không áp dụng hoàn toàn** phong cách playful. Tin tức cần nghiêm túc hơn nhưng vẫn accessible.

---

## 2. Logo & Brand Identity

### 2.1 Logo Wordmark

Từ ảnh logo đã có:
```
VNKR + icon wifi/signal ở góc phải chữ R
Font: Bold/Black weight, sans-serif
Variant: Dark-on-white + White-on-dark
```

**Logo không gian sử dụng:**
| Context | Variant | Background |
|---|---|---|
| Header website | Dark wordmark | White `#FFFFFF` |
| Dark header / mobile nav | White wordmark | Dark `#0A3D62` hoặc `#000000` |
| Favicon / PWA icon | Chữ "V" or "VK" | Brand color |
| Social media | Full wordmark | `#FF69B4` playful (thumbnail style) |

### 2.2 Icon Wifi/Signal

Ý nghĩa: **VNKR = Kết nối thông tin** — icon wifi trên chữ R là điểm nhận diện đặc trưng. Cần giữ nhất quán ở tất cả contexts.

### 2.3 CSS Variables cho Logo

```css
/* Logo sizing */
--logo-height-desktop: 32px;
--logo-height-mobile: 26px;
--logo-height-admin: 28px;
```

---

## 3. Color System

### 3.1 Palette Đã Phân Tích (từ Color palette.png)

#### Default / Semantic Tones
```css
/* Semantic colors — dùng cho trạng thái hệ thống */
--color-dark:      #000000;   /* Text chính, background tối */
--color-info:      #0095FF;   /* Link, info badge, live indicator */
--color-success:   #00D68F;   /* Thành công, xác nhận, published */
--color-warning:   #FFAA00;   /* Cảnh báo, draft, pending */
--color-error:     #FF3D71;   /* Lỗi, breaking news, urgent */
--color-greyscale: #2E3A59;   /* Text phụ, border, placeholder */
--color-white:     #FFFFFF;   /* Nền trang, card background */
```

#### Brand Colors (từ ảnh — đề xuất VNKR)
```css
/* Brand palette — dùng đa dạng theo context */
--brand-dark:    #000000;   /* Text header, logo dark */
--brand-grey:    #EFEFEF;   /* Surface nhẹ, hover state */
--brand-magenta: #FD9FDD;   /* Highlight, tag active, social */
--brand-orange:  #FC7339;   /* Breaking news accent, CTA phụ */
--brand-green:   #8EFF6C;   /* Success notification, bookmark saved */
--brand-violet:  #AF96FB;   /* Premium badge, Góc Nhìn PTB label */
--brand-blue:    #49DBC8;   /* Web3/Crypto section accent */
--brand-yellow:  #FFF172;   /* Highlight text, trending badge */
```

### 3.2 Màu Áp Dụng Cụ Thể Cho VNKR.VN

```css
/* === VNKR DESIGN TOKENS === */

/* Primary brand — quyết định dùng đen/tối thay vì đỏ */
--primary:         #0A3D62;   /* Navy xanh đậm — header, CTA chính */
--primary-dark:    #072d48;   /* Hover state */
--primary-light:   #e8f0f7;   /* Tint nhẹ — hover bg, tag bg */

/* Accent — dùng sparingly cho breaking, CTA nổi bật */
--accent:          #FC7339;   /* Cam — breaking news, "Đọc thêm" button */
--accent-alt:      #FF3D71;   /* Đỏ hồng — error, urgent, live badge */

/* Surface & Background */
--bg-page:         #F5F6FA;   /* Nền trang xám nhẹ */
--bg-card:         #FFFFFF;   /* Card, article item */
--bg-surface:      #EFEFEF;   /* Sidebar bg, input bg */

/* Text */
--text-primary:    #1a1a2e;   /* Tiêu đề, body chính */
--text-secondary:  #4a5568;   /* Timestamp, meta, caption */
--text-muted:      #718096;   /* Placeholder, helper text */
--text-inverse:    #FFFFFF;   /* Text trên nền tối */

/* Border */
--border-default:  #E2E8F0;   /* Card border, input border */
--border-strong:   #CBD5E0;   /* Divider, section separator */
--border-focus:    #0A3D62;   /* Input focus ring */

/* Status semantic */
--status-published: #00D68F;  /* Badge published */
--status-draft:    #FFAA00;   /* Badge draft */
--status-review:   #0095FF;   /* Badge review */
--status-archived: #718096;   /* Badge archived */
--status-live:     #FF3D71;   /* LIVE indicator — pulse */
--status-breaking: #FC7339;   /* Breaking news ticker */

/* Dark mode (future) */
--dm-bg-page:      #0d1117;
--dm-bg-card:      #161b22;
--dm-text-primary: #e6edf3;
--dm-border:       #30363d;
```

### 3.3 Shade Scale (từ Dark and light shades)

Mỗi semantic color có 8 bậc sáng/tối. Áp dụng cho VNKR:

```css
/* Info scale — xanh dương */
--info-900: #003B8E; --info-700: #0052CC; --info-500: #0095FF;
--info-300: #66B8FF; --info-100: #CCE5FF;

/* Success scale — xanh lá */
--success-900: #00543A; --success-700: #007A55; --success-500: #00D68F;
--success-300: #66EBC0; --success-100: #CCFAEC;

/* Warning scale — vàng cam */
--warning-900: #7A4100; --warning-700: #B36000; --warning-500: #FFAA00;
--warning-300: #FFD166; --warning-100: #FFF4CC;

/* Error scale — đỏ hồng */
--error-900: #8B0032; --error-700: #C4004D; --error-500: #FF3D71;
--error-300: #FF8FAD; --error-100: #FFD6E3;
```

---

## 4. Typography System

### 4.1 Font Family

Từ ảnh `Typography.png`:
```
Font: Work Sans — Semibold (600) · Medium (500) · Regular (400)
```

**Quyết định cho VNKR.VN:**
```css
/* Giữ Be Vietnam Pro cho body (hiện tại), thêm Work Sans cho headings */
--font-display:  'Work Sans', 'Be Vietnam Pro', Arial, sans-serif;  /* Headings */
--font-body:     'Be Vietnam Pro', Arial, sans-serif;               /* Body text */
--font-mono:     'JetBrains Mono', 'Courier New', monospace;       /* Code, quote */
```

> **Note:** Cả hai font đều có trên Google Fonts, load cùng 1 request `<link>`.

### 4.2 Type Scale

Từ `Typography.png` — áp dụng vào CSS Custom Properties:

```css
/* === HEADLINES (Work Sans) === */
--text-h1: 2.5rem;    /* 40px — Hero headline */
--text-h2: 2rem;      /* 32px — Section title */
--text-h3: 1.75rem;   /* 28px — Article title (large) */
--text-h4: 1.375rem;  /* 22px — Article title (normal) */
--text-h5: 1.125rem;  /* 18px — Subtitle / Section heading */
--text-h6: 1rem;      /* 16px — Small heading */

/* === BODY (Be Vietnam Pro) === */
--text-b1:       1rem;       /* 16px — Body 1, main article text */
--text-b2:       0.875rem;   /* 14px — Body 2, sidebar, meta */
--text-caption:  0.75rem;    /* 12px — Caption, timestamp, tag */
--text-overline: 0.625rem;   /* 10px — Category label uppercase */

/* === BUTTON === */
--text-btn-giant:  1.25rem;  /* 20px */
--text-btn-large:  1rem;     /* 16px */
--text-btn-medium: 0.875rem; /* 14px */
--text-btn-small:  0.75rem;  /* 12px */

/* === LINE HEIGHT === */
--lh-tight:   1.2;   /* Headlines */
--lh-normal:  1.5;   /* Body */
--lh-relaxed: 1.75;  /* Long-form article content */

/* === FONT WEIGHT === */
--fw-regular:   400;
--fw-medium:    500;
--fw-semibold:  600;
--fw-bold:      700;
```

### 4.3 Sử Dụng Thực Tế

| Element | Size | Weight | Line-height |
|---|---|---|---|
| Article H1 (detail page) | `--text-h3` (28px) | Semibold 600 | 1.3 |
| Article title (card) | `--text-h5` (18px) | Semibold 600 | 1.4 |
| Article excerpt | `--text-b2` (14px) | Regular 400 | 1.6 |
| Category label | `--text-overline` | Medium 500 | — |
| Timestamp / meta | `--text-caption` | Regular 400 | — |
| Button primary | `--text-btn-medium` | Semibold 600 | — |
| Breaking ticker | `--text-b2` | Medium 500 | — |
| Nav links | `--text-b2` | Medium 500 | — |
| Footer text | `--text-caption` | Regular 400 | 1.6 |

---

## 5. Button & Form System

### 5.1 Button Variants (từ Buttons & Inputs.png)

3 variant rõ ràng:
```
1. FILLED   — Background solid + text trắng
2. OUTLINED — Border + text màu, background trong
3. GHOST    — Chỉ text màu, không border/background
```

8 màu sắc: Dark · Grey · Blue · Green · Orange · Pink/Error · Navy · White-on-Dark

### 5.2 Button CSS

```css
/* === BASE BUTTON === */
.btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  border-radius: 50px;          /* Pill shape từ design */
  font-family: var(--font-body);
  font-weight: var(--fw-semibold);
  font-size: var(--text-btn-medium);
  line-height: 1;
  cursor: pointer;
  transition: all 0.15s ease;
  text-decoration: none;
  white-space: nowrap;
  border: 2px solid transparent;
}

/* === SIZES === */
.btn-giant  { padding: 16px 32px; font-size: var(--text-btn-giant); }
.btn-large  { padding: 12px 24px; font-size: var(--text-btn-large); }
.btn-medium { padding: 9px 20px;  font-size: var(--text-btn-medium); }
.btn-small  { padding: 6px 14px;  font-size: var(--text-btn-small); }

/* === FILLED VARIANTS === */
.btn-primary   { background: var(--primary);  color: white; border-color: var(--primary); }
.btn-accent    { background: var(--accent);   color: white; border-color: var(--accent); }
.btn-dark      { background: #000;            color: white; border-color: #000; }
.btn-success   { background: var(--color-success); color: white; }
.btn-warning   { background: var(--color-warning); color: #1a1a2e; }
.btn-error     { background: var(--color-error);   color: white; }

/* === OUTLINED VARIANTS === */
.btn-outline-primary { background: transparent; color: var(--primary); border-color: var(--primary); }
.btn-outline-dark    { background: transparent; color: #000; border-color: #000; }
.btn-outline-accent  { background: transparent; color: var(--accent); border-color: var(--accent); }

/* === GHOST VARIANTS === */
.btn-ghost-primary { background: transparent; color: var(--primary); border-color: transparent; }
.btn-ghost-dark    { background: transparent; color: #000; border-color: transparent; }

/* === HOVER STATES === */
.btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); }
.btn-outline-primary:hover { background: var(--primary-light); }
.btn-ghost-primary:hover { background: var(--primary-light); }

/* === DISABLED === */
.btn:disabled, .btn.disabled {
  opacity: 0.5;
  cursor: not-allowed;
  pointer-events: none;
}
```

### 5.3 Form Input System

```css
/* === BASE INPUT === */
.form-input {
  width: 100%;
  padding: 10px 16px;
  border: 1.5px solid var(--border-default);
  border-radius: 12px;             /* Rounded nhưng không pill */
  font-family: var(--font-body);
  font-size: var(--text-b1);
  color: var(--text-primary);
  background: var(--bg-surface);
  transition: border-color 0.15s, box-shadow 0.15s;
  outline: none;
}

.form-input:focus {
  border-color: var(--border-focus);
  box-shadow: 0 0 0 3px var(--primary-light);
  background: var(--bg-card);
}

.form-input::placeholder { color: var(--text-muted); }

/* Error state */
.form-input.is-error {
  border-color: var(--color-error);
  box-shadow: 0 0 0 3px var(--error-100);
}

/* Label */
.form-label {
  display: block;
  font-size: var(--text-b2);
  font-weight: var(--fw-medium);
  color: var(--text-secondary);
  margin-bottom: 6px;
}

/* Caption / helper */
.form-caption {
  font-size: var(--text-caption);
  color: var(--text-muted);
  margin-top: 4px;
}
.form-caption.error { color: var(--color-error); }
```

### 5.4 Toggle & Checkbox (từ UI kits.png)

```css
/* Toggle switch */
.toggle {
  width: 44px; height: 24px;
  border-radius: 12px;
  background: var(--border-default);
  position: relative; cursor: pointer;
  transition: background 0.2s;
}
.toggle.active { background: var(--color-success); }
.toggle::after {
  content: '';
  width: 18px; height: 18px;
  background: white;
  border-radius: 50%;
  position: absolute;
  top: 3px; left: 3px;
  transition: left 0.2s;
  box-shadow: 0 1px 3px rgba(0,0,0,.2);
}
.toggle.active::after { left: 23px; }
```

---

## 6. UI Component Library

### 6.1 Badge / Tag

```css
.badge {
  display: inline-flex;
  align-items: center;
  padding: 2px 10px;
  border-radius: 50px;
  font-size: var(--text-caption);
  font-weight: var(--fw-semibold);
  text-transform: uppercase;
  letter-spacing: 0.04em;
}

/* Status badges */
.badge-published { background: var(--success-100); color: var(--success-900); }
.badge-draft     { background: var(--warning-100); color: var(--warning-900); }
.badge-review    { background: var(--info-100);    color: var(--info-700); }
.badge-live      { background: var(--error-100);   color: var(--error-700); animation: pulse 1.5s infinite; }
.badge-breaking  { background: var(--accent);       color: white; }

/* Category badge */
.badge-category {
  background: var(--primary-light);
  color: var(--primary);
}

@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.6; }
}
```

### 6.2 Card / Article Card

```css
.card {
  background: var(--bg-card);
  border: 1px solid var(--border-default);
  border-radius: 12px;
  overflow: hidden;
  transition: box-shadow 0.2s, transform 0.2s;
}
.card:hover {
  box-shadow: 0 4px 20px rgba(10, 61, 98, 0.1);
  transform: translateY(-2px);
}

.card-img { width: 100%; aspect-ratio: 16/9; object-fit: cover; }

.card-body { padding: 16px; }

.card-category { /* badge-category */ }

.card-title {
  font-size: var(--text-h5);
  font-weight: var(--fw-semibold);
  color: var(--text-primary);
  line-height: var(--lh-tight);
  margin: 8px 0 6px;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.card-excerpt {
  font-size: var(--text-b2);
  color: var(--text-secondary);
  line-height: var(--lh-normal);
  -webkit-line-clamp: 2;
  /* ... */
}

.card-meta {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: var(--text-caption);
  color: var(--text-muted);
  margin-top: 10px;
}
```

### 6.3 Navigation Components

**Top bar (từ UI kits.png — Headline section):**
```css
.app-header {
  position: sticky; top: 0; z-index: 100;
  background: var(--bg-card);
  border-bottom: 1px solid var(--border-default);
  box-shadow: 0 1px 8px rgba(0,0,0,.06);
}

.nav-link {
  font-size: var(--text-b2);
  font-weight: var(--fw-medium);
  color: var(--text-secondary);
  padding: 8px 12px;
  border-radius: 8px;
  transition: color 0.15s, background 0.15s;
  text-decoration: none;
}
.nav-link:hover, .nav-link.active {
  color: var(--primary);
  background: var(--primary-light);
}

/* Active underline style cho news nav */
.nav-link.active {
  border-bottom: 2px solid var(--primary);
  border-radius: 0;
  background: transparent;
}
```

### 6.4 List Items (từ UI kits.png)

```css
/* Profile / Settings list item */
.list-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 14px 0;
  border-bottom: 1px solid var(--border-default);
}
.list-item:last-child { border-bottom: none; }

.list-item-icon {
  width: 36px; height: 36px;
  border-radius: 8px;
  background: var(--bg-surface);
  display: flex; align-items: center; justify-content: center;
  margin-right: 12px;
  flex-shrink: 0;
}

.list-item-title {
  font-size: var(--text-b1);
  font-weight: var(--fw-medium);
  color: var(--text-primary);
}
.list-item-desc {
  font-size: var(--text-caption);
  color: var(--text-muted);
}
```

### 6.5 Accordion / FAQ (từ UI kits.png)

```css
.accordion-item {
  border-bottom: 1px solid var(--border-default);
}
.accordion-trigger {
  width: 100%;
  padding: 14px 0;
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: var(--text-b1);
  font-weight: var(--fw-medium);
  color: var(--text-primary);
  cursor: pointer;
  background: none;
  border: none;
}
.accordion-body {
  font-size: var(--text-b2);
  color: var(--text-secondary);
  padding: 0 0 14px;
  line-height: var(--lh-relaxed);
}
```

---

## 7. Spacing & Layout Grid

### 7.1 Spacing Scale (8px base)

```css
--space-1:  4px;    /* 0.25rem */
--space-2:  8px;    /* 0.5rem  */
--space-3:  12px;   /* 0.75rem */
--space-4:  16px;   /* 1rem    */
--space-5:  20px;   /* 1.25rem */
--space-6:  24px;   /* 1.5rem  */
--space-8:  32px;   /* 2rem    */
--space-10: 40px;   /* 2.5rem  */
--space-12: 48px;   /* 3rem    */
--space-16: 64px;   /* 4rem    */
--space-20: 80px;   /* 5rem    */
```

### 7.2 Border Radius

```css
--radius-sm:   4px;    /* Input, badge nhỏ */
--radius-md:   8px;    /* Button, card nhỏ */
--radius-lg:   12px;   /* Card, panel */
--radius-xl:   16px;   /* Modal, drawer */
--radius-pill: 50px;   /* Button pill (từ design) */
--radius-full: 9999px; /* Avatar, toggle */
```

### 7.3 Shadow Scale

```css
--shadow-sm:  0 1px 3px rgba(0,0,0,.08);
--shadow-md:  0 4px 12px rgba(0,0,0,.1);
--shadow-lg:  0 8px 24px rgba(0,0,0,.12);
--shadow-card: 0 2px 8px rgba(10, 61, 98, 0.08);
--shadow-sticky: 0 2px 12px rgba(0,0,0,.1);
```

### 7.4 Breakpoints

```css
/* Mobile-first (từ UI kits.png — rõ ràng mobile-first) */
--bp-sm:  576px;   /* Landscape mobile */
--bp-md:  768px;   /* Tablet */
--bp-lg:  992px;   /* Desktop (main breakpoint hiện tại) */
--bp-xl:  1200px;  /* Wide desktop */
--bp-xxl: 1400px;  /* Ultra wide */
```

### 7.5 Container & Layout

```css
.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 var(--space-4);
}

/* News layout: main + sidebar */
.layout-news {
  display: grid;
  grid-template-columns: 1fr 300px;
  gap: var(--space-6);
}
@media (max-width: 992px) {
  .layout-news { grid-template-columns: 1fr; }
}

/* Card grid */
.grid-articles {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: var(--space-4);
}
@media (max-width: 768px) {
  .grid-articles { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 576px) {
  .grid-articles { grid-template-columns: 1fr; }
}
```

---

## 8. Kế Hoạch Triển Khai vào CSS

### 8.1 Cấu Trúc File CSS Hiện Tại

```
resources/views/fe/layouts/css.blade.php  ← Toàn bộ CSS (800+ dòng hiện tại)
```

### 8.2 Cấu Trúc Đề Xuất — Tách Thành Layers

```
resources/
└── css/
    ├── tokens.css          ← CSS Custom Properties (Design Tokens)
    ├── typography.css      ← Font import + type scale classes
    ├── buttons.css         ← .btn variants
    ├── forms.css           ← inputs, labels, toggles
    ├── components/
    │   ├── card.css
    │   ├── badge.css
    │   ├── navbar.css
    │   ├── sidebar.css
    │   ├── ticker.css
    │   └── comment.css
    └── layouts/
        ├── home.css
        ├── detail.css
        └── admin.css
```

> **Short-term:** Gộp tất cả vào `css.blade.php` nhưng chia rõ sections bằng comments.  
> **Long-term:** Dùng Vite để import riêng từng file.

### 8.3 Font Import Cập Nhật

```html
<!-- Trong <head> của index.blade.php -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&family=Work+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
```

### 8.4 Thứ Tự Triển Khai CSS

```
PHASE 1 (Ngay): Cập nhật CSS Custom Properties trong css.blade.php
  → Thay thế --brand: #9F1B32 bằng token system mới
  → Thêm semantic colors, spacing scale, shadow scale

PHASE 2 (Tuần 1): Chuẩn hóa buttons
  → Áp dụng .btn + variants, thay thế các button inline styles
  → Đảm bảo pill shape nhất quán

PHASE 3 (Tuần 2): Chuẩn hóa cards & typography
  → Áp dụng type scale vars vào h1-h6, body, meta
  → Chuẩn hóa article card component

PHASE 4 (Tuần 3-4): Form system + badge system
  → Cập nhật search, comment form, newsletter form
  → Status badges nhất quán

PHASE 5 (Tháng 2): Dark mode support
  → @media (prefers-color-scheme: dark) vars
  → Manual toggle class .dark-mode
```

---

## 9. Lộ Trình Thực Hiện

### Sprint 1 — Design Tokens ✅ HOÀN THÀNH

**Files thực thi:**
- [`public/assets/css/vnkr-tokens.css`](../../../public/assets/css/vnkr-tokens.css) — file mới, toàn bộ design tokens

```
[x] Tách CSS Custom Properties ra file riêng: vnkr-tokens.css
    [x] Color tokens (brand, accent, gold, neutral, semantic)
    [x] Dark mode vars ([data-theme="dark"])
    [x] Semantic shade scale (info/success/warning/error ×5 bậc)
    [x] Typography scale (--text-xs → --text-hero, --text-h1 → --text-h6)
    [x] Font weight + line-height tokens
    [x] Spacing scale (--sp-1 → --sp-20)
    [x] Border radius scale (--radius-sm → --radius-full)
    [x] Shadow scale (--shadow-xs → --shadow-xl)
    [x] Transition tokens
    [x] Layout tokens (--container-max, --nav-height, --logo-height-*)
    [x] Focus ring tokens (accessibility)
    [x] Global reset + base styles
    [x] Utility helpers (.text-brand, .bg-accent, .fw-bold, v.v.)
[x] Cập nhật css.blade.php: load vnkr-tokens.css trước Bootstrap
[x] Visual regression OK
```

---

### Sprint 2 — Button & Form Unification ✅ HOÀN THÀNH

**Files thực thi:**
- [`public/assets/css/vnkr-ui.css`](../../../public/assets/css/vnkr-ui.css) — file mới, UI component library

```
[x] .vnkr-btn + variants (primary, outline, outline-dark, brand, ghost, danger)
[x] .btn pill system (3 variants × sizes: giant/large/medium/small)
    [x] .btn-primary, .btn-accent, .btn-dark, .btn-success, .btn-warning, .btn-error
    [x] .btn-outline-primary/dark/accent
    [x] .btn-ghost-primary/dark
[x] Form input system: .vnkr-field + .vnkr-input (size variants, error/disabled states)
[x] .vnkr-search — search bar pill trong header
[x] Toggle switch: .vnkr-toggle
[x] .form-input / .form-label / .form-caption (backward-compat)
[x] Callout / Alert box (§28): .vnkr-callout--info/success/warn/danger/gold
[x] Skeleton loading (§30): .vnkr-skeleton--text/circle/rect
[x] Modal / Dialog (§31): .vnkr-modal + data-modal-open/close attributes
[x] Timeline (§32): .vnkr-timeline + .vnkr-tl-item
[x] Dark mode adjustments (§33): [data-theme="dark"] overrides toàn bộ components
[x] Dropdown (§34): .vnkr-dropdown + data-dropdown-trigger
[x] Theme toggle button: .vnkr-theme-toggle
```

---

### Sprint 3 — Typography Refinement ✅ HOÀN THÀNH

**Files thực thi:**
- [`resources/views/fe/layouts/css.blade.php`](../../../resources/views/fe/layouts/css.blade.php)
- [`public/assets/css/vnkr-fe.css`](../../../public/assets/css/vnkr-fe.css)

```
[x] Google Fonts: thêm subset=vietnamese cho Work Sans
    → URL: family=Work+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&subset=vietnamese
[x] h1–h6 toàn site dùng --font-display (Work Sans)
[x] font-feature-settings: "kern" 1, "liga" 0 cho body + headings
    → Tắt ligatures không cần thiết, bật kerning → dấu tiếng Việt chính xác
[x] text-rendering: optimizeLegibility + antialiased
[x] Type scale áp dụng vào article detail (§4.3):
    [x] h1 → var(--text-h3) 28px, Work Sans semibold 600
    [x] article body → var(--text-b1) 16px, var(--lh-relaxed) 1.65
    [x] h2 trong body → var(--text-h4) 22px, Work Sans semibold
    [x] h3 trong body → var(--text-h5) 18px, Work Sans semibold
    [x] blockquote → var(--text-b2) 14px, var(--lh-relaxed)
    [x] article-meta → var(--text-caption) 12px
```

---

### Sprint 4 — Card & Component System ✅ HOÀN THÀNH

**Files thực thi:**
- [`public/assets/css/vnkr-fe.css`](../../../public/assets/css/vnkr-fe.css)
- [`public/assets/js/vnkr-ui.js`](../../../public/assets/js/vnkr-ui.js) — file mới (tạo từ đầu)
- [`resources/views/fe/layouts/header.blade.php`](../../../resources/views/fe/layouts/header.blade.php)

```
[x] .cat-block — bg-card, radius-md, shadow-sm, Work Sans heading
[x] .news-list-item — token font/spacing/radius
[x] .cat-badge / .source-badge — text-xs, fw-bold, radius-sm
[x] .sidebar-widget / .widget-title — Work Sans, token vars
[x] .most-read-item / .market-table — token colors (success-700, error-700)
[x] .article-detail wrapper — bg-card, radius-md, shadow-sm, transition
[x] .main-nav active states — fw-bold, token vars
[x] Nav community/sponsor/web3 → success/gold/violet tokens
[x] Dark mode overrides trong vnkr-fe.css (20+ selectors):
    [x] site-header, top-bar, main-nav, breaking-bar
    [x] cat-block, sidebar-widget, article-detail
    [x] result-card, related-card, vedette, news-list-item
    [x] source-box, editor-credit, auth-card, newsletter-input
    [x] tag-pill, ptb-section

[x] vnkr-ui.js — JS library mới (vanilla, không jQuery):
    [x] ThemeModule: toggle dark/light, sync icon ☀/🌙, PWA meta-theme, localStorage
    [x] ToastModule: 4 loại (success/error/warning/info), auto-dismiss 3.5s
    [x] ModalModule: open/close qua data-modal-open, ESC key, focus trap
    [x] AccordionModule: .vnkr-accordion-item toggle, exclusive trong group
    [x] DropdownModule: data-dropdown toggle, click-outside, ESC
    [x] ScrollWatcher: .vnkr-nav--scrolled glassmorphism khi scroll >60px
    [x] Public API: window.VNKRUI.toast(), .theme, .modal

[x] Header: nút #vnkr-theme-toggle (☀/🌙) trong top-bar
    → Class .vnkr-theme-toggle đã có CSS ở vnkr-ui.css §33
```

---

### Sprint 5 — Dark Mode ✅ HOÀN THÀNH (trong Sprint 4)

> Dark Mode được hoàn thành sớm hơn kế hoạch — tích hợp trực tiếp vào Sprint 4.

```
[x] [data-theme="dark"] vars trong vnkr-tokens.css (brand/neutral/bg/border/semantic)
[x] Early-apply script trong js.blade.php (trước paint — không flash trắng)
    → localStorage.getItem('vnkr-theme') || prefers-color-scheme
[x] Toggle button #vnkr-theme-toggle trong header
[x] Save preference vào localStorage key 'vnkr-theme'
[x] Sync icon khi switch (bi-sun-fill ↔ bi-moon-stars-fill)
[x] PWA meta-theme-color cập nhật khi toggle
[x] Component overrides trong vnkr-ui.css §33 (hero, code block, card, input, table, progress)
[x] Component overrides trong vnkr-fe.css (tất cả fe components)
[x] Follows OS preference tự động nếu chưa chọn thủ công
```

---

### Sprint 6 — Tối ưu Admin Panel ✅ HOÀN THÀNH

**Files thực thi:**
- [`public/assets/css/vnkr-admin.css`](../../../public/assets/css/vnkr-admin.css) — file mới (717 dòng)
- [`resources/views/admin/master.blade.php`](../../../resources/views/admin/master.blade.php)

```
[x] Tạo vnkr-admin.css — 12 sections:
    [x] §1  Token Bridge: override --bs-primary/danger/success/warning/info → VNKR tokens
    [x] §2  Sidebar: VNKR brand colors, active state, submenu, icons
    [x] §3  Header: top bar, search pill, toggle button
    [x] §4  Buttons: pill system (.btn pill + primary/danger/success/warning/default/info)
    [x] §5  Badges & Status Labels: .badge-status-published/draft/review/archived
    [x] §6  Box / Card: .box, .box-header, .box-title (Work Sans), .box-footer
    [x] §7  Tables: responsive wrapper, thead brand color, token row hover
    [x] §8  Forms: .form-control/.form-select border-radius, focus ring, validation states
    [x] §9  Stats Tiles: .stats-tile, .sale-icon color variants → vnkr tokens
    [x] §10 Typography: Work Sans headings, Be Vietnam Pro body, page-title
    [x] §11 Responsive: mobile sidebar overlay + ESC key, table scroll, search hidden xs
    [x] §12 Utilities: .cat-label-admin, .rank-num, .text-admin-muted, alerts, pagination

[x] master.blade.php cập nhật:
    [x] lang="en" → lang="vi"
    [x] Thêm <meta robots noindex,nofollow> (admin không cần index)
    [x] Google Fonts: Work Sans + Be Vietnam Pro (subset=vietnamese)
    [x] Load vnkr-tokens.css trước main.min.css
    [x] Load vnkr-admin.css sau main.min.css (để ghi đè Arise)
    [x] @yield('styles') slot cho page-level CSS
    [x] Mobile sidebar JS: .sidebar-open toggle + .sidebar-backdrop overlay + ESC
```

### Sprint 7 — Performance & PWA ✅ HOÀN THÀNH

**Files thực thi:**
- [`resources/views/fe/index.blade.php`](../../../resources/views/fe/index.blade.php)
- [`vite.config.js`](../../../vite.config.js)
- [`public/sw.js`](../../../public/sw.js)
- [`public/offline.html`](../../../public/offline.html)

```
[x] Font preload hints trong index.blade.php:
    [x] <link rel="preload" as="style"> cho Google Fonts URL
        → Work Sans ital,wght 400-700 + Be Vietnam Pro ital,wght 300-800
        → subset=vietnamese cho cả hai font
    [x] Giảm FOUT (Flash of Unstyled Text) khi render lần đầu

[x] Critical CSS inline trong index.blade.php (above-the-fold):
    [x] Base body: font-family, background, color, font-size, line-height
    [x] Reset minimal: box-sizing, img display, a text-decoration
    [x] .top-bar: background brand, color white
    [x] .site-header: background white, border-bottom brand
    [x] .main-nav: sticky, border-bottom accent, z-index 999
    [x] .container: max-width 1200px, margin auto, padding 16px
    [x] .logo-mark: prevent layout shift — kích thước cố định
    → Trang render đúng ngay lập tức không cần chờ external CSS

[x] Vite pipeline — vite.config.js:
    [x] Thêm 5 VNKR files vào input[] của laravel-vite-plugin:
        vnkr-tokens.css, vnkr-ui.css, vnkr-fe.css, vnkr-admin.css, vnkr-ui.js
    [x] refresh[] bao gồm blade views + CSS + JS
    [x] build.rollupOptions: giữ tên file gốc (không hash) để SW cache stable
    [x] sourcemap: true trong dev, false trong production

[x] Service Worker nâng cấp — sw.js v2:
    [x] CACHE_VERSION: 'vnkr-v1' → 'vnkr-v2' (bump khi deploy mới)
    [x] CACHE_FONTS bucket riêng cho Google Fonts
    [x] PRECACHE_URLS thêm VNKR Design System files:
        vnkr-tokens.css, vnkr-ui.css, vnkr-fe.css, vnkr-ui.js,
        bootstrap-icons.css, bootstrap-icons.woff2
    [x] Thêm chiến lược Stale-While-Revalidate cho Google Fonts
        → Đọc từ cache ngay, update ngầm không block render
    [x] Exclude _debugbar từ SW interception
    [x] Inline fallback đẹp hơn (pill button, brand colors)

[x] PWA offline page — public/offline.html:
    [x] VNKR Design Tokens inline (không cần file ngoài khi offline)
    [x] Work Sans headings + Be Vietnam Pro body (Google Fonts preconnect)
    [x] Card layout với gradient background (brand blue)
    [x] Pill buttons: "↩ Thử lại ngay" + "🏠 Trang chủ"
    [x] Tips section giúp user debug
    [x] Online banner slide-down khi network restored
    [x] Auto-reload sau 1.2s khi có mạng
    [x] Auto-redirect nếu load trang offline khi đang online (nhầm trang)
    [x] <meta robots noindex> — không index trang lỗi
```

---

## 10. Cấu Trúc File Hiện Tại (sau Sprint 1–7)

```
public/assets/css/
├── vnkr-tokens.css     ← Design tokens (382 dòng)
│                          Colors, typography, spacing, radius, shadow,
│                          transition, layout, focus, reset, utilities
├── vnkr-ui.css         ← UI Component Library (2927 dòng)
│                          Nav, hero, section, buttons (pill + vnkr-btn),
│                          badges, cards, forms, skeleton, modal, timeline,
│                          accordion, dropdown, dark mode overrides
└── vnkr-fe.css         ← Frontend Site CSS (662 dòng)
                           Top-bar, header, nav, breaking ticker, vedette,
                           cat-block, news items, badges, sidebar, article,
                           comment, result, pagination, footer, dark mode fe

public/assets/css/
└── vnkr-admin.css      ← Admin Panel CSS (717 dòng)
                           Token bridge, sidebar, header, buttons (pill),
                           badges, box/card, tables, forms, stats tiles,
                           typography, responsive, utilities

public/assets/js/
└── vnkr-ui.js          ← UI JS Library (364 dòng, vanilla)
                           ThemeModule, ToastModule, ModalModule,
                           AccordionModule, DropdownModule, ScrollWatcher

resources/views/fe/layouts/
├── css.blade.php       ← Load order: tokens → Bootstrap → UI → fe
│                          Google Fonts: Be Vietnam Pro + Work Sans (vi subset)
├── js.blade.php        ← Early-apply theme script + vnkr-ui.js (defer)
└── header.blade.php    ← Dark mode toggle button (#vnkr-theme-toggle)

resources/views/admin/
└── master.blade.php    ← Load order: tokens → Arise → vnkr-admin
                           Google Fonts (vi subset), mobile sidebar JS

resources/views/fe/
└── index.blade.php     ← Font preload hints, critical CSS inline

public/
├── sw.js               ← Service Worker v2: cache-first + SWR + precache
├── offline.html        ← PWA offline page (design system inline tokens)
├── manifest.json       ← PWA manifest
└── assets/css/         ← VNKR CSS files (all Vite pipeline entries)
```

**Load order trong `<head>`:**
```
1. vnkr-tokens.css     (Design tokens — phải load đầu tiên)
2. bootstrap.min.css   (Reset + grid)
3. bootstrap-icons     (CDN)
4. themify-icons
5. vnkr-ui.css         (Component library)
6. vnkr-fe.css         (Site-specific overrides)
```

---

## Tham Khảo & Liên Kết

| Resource | Link |
|---|---|
| Work Sans font | https://fonts.google.com/specimen/Work+Sans |
| Be Vietnam Pro font | https://fonts.google.com/specimen/Be+Vietnam+Pro |
| Design tokens ảnh | `ý-tưởng/Design-system/Color palette.png` |
| Typography ảnh | `ý-tưởng/Design-system/Typography.png` |
| Button system ảnh | `ý-tưởng/Design-system/Buttons & Inputs.png` |
| UI kits ảnh | `ý-tưởng/Design-system/UI kits.png` |
| Logo dark variant | `ý-tưởng/Design-system/App icon and logo green.png` |
| Logo light variant | `ý-tưởng/Design-system/App icon and logo white.png` |
| Brand thumbnail | `ý-tưởng/Design-system/Thumbnail.png` |

---

*Tài liệu lập bởi: Bob AI Engineering Consultant*
*Dựa trên phân tích: 7 ảnh Design System VNKR*
*Ngày lập: 24/09/2026 | Cập nhật lần cuối: 25/09/2026 | Phiên bản: 4.0 (Sprint 7 — Final)*
