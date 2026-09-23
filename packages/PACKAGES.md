# 📦 VNKR Labs — Packages Registry

> Tài liệu khai báo toàn bộ packages của VNKR Design System đã publish lên [npmjs.com](https://www.npmjs.com/org/vnkr-labs).  
> **Org:** [@vnkr-labs](https://www.npmjs.com/org/vnkr-labs) · **Owner:** [vnkr-io](https://www.npmjs.com/~vnkr-io) · **Registry:** https://registry.npmjs.org/

---

## 🎉 Packages đã live

| Package | Version | Size | Files | Link |
|---|---|---|---|---|
| `@vnkr-labs/tokens` | `2.0.0` | 15.8 kB | 3 | [npmjs.com/package/@vnkr-labs/tokens](https://www.npmjs.com/package/@vnkr-labs/tokens) |
| `@vnkr-labs/ui` | `2.0.0` | 94.3 kB | 4 | [npmjs.com/package/@vnkr-labs/ui](https://www.npmjs.com/package/@vnkr-labs/ui) |
| `@vnkr-labs/fe` | `2.0.0` | 33.7 kB | 3 | [npmjs.com/package/@vnkr-labs/fe](https://www.npmjs.com/package/@vnkr-labs/fe) |
| `@vnkr-labs/admin` | `2.0.0` | 24.7 kB | 3 | [npmjs.com/package/@vnkr-labs/admin](https://www.npmjs.com/package/@vnkr-labs/admin) |

---

## 📥 Cài đặt

### Frontend (vnkr.vn website)
```bash
npm install @vnkr-labs/tokens @vnkr-labs/ui @vnkr-labs/fe
```

### Admin Panel
```bash
npm install @vnkr-labs/tokens @vnkr-labs/ui @vnkr-labs/admin
```

### Tất cả
```bash
npm install @vnkr-labs/tokens @vnkr-labs/ui @vnkr-labs/fe @vnkr-labs/admin
```

---

## 🔗 Load Order

```html
<!-- 1. Tokens — PHẢI load đầu tiên (CSS Custom Properties) -->
<link rel="stylesheet" href="node_modules/@vnkr-labs/tokens/index.css">

<!-- 2. UI Component Library -->
<link rel="stylesheet" href="node_modules/@vnkr-labs/ui/index.css">
<script src="node_modules/@vnkr-labs/ui/index.js" defer></script>

<!-- 3a. Frontend site -->
<link rel="stylesheet" href="node_modules/@vnkr-labs/fe/index.css">

<!-- 3b. Hoặc Admin panel (không dùng cùng lúc với fe) -->
<link rel="stylesheet" href="node_modules/@vnkr-labs/admin/index.css">
```

### Với Vite / Laravel Mix
```js
import '@vnkr-labs/tokens/index.css';
import '@vnkr-labs/ui/index.css';
import '@vnkr-labs/fe/index.css';   // hoặc admin
import '@vnkr-labs/ui';             // JS module
```

---

## 📋 Mô tả từng package

### `@vnkr-labs/tokens` — Design Tokens
**File:** `index.css` (382 dòng)

CSS Custom Properties — nguồn sự thật duy nhất cho toàn bộ hệ thống UI.

| Nhóm token | Prefix | Ví dụ |
|---|---|---|
| Brand | `--brand`, `--accent`, `--gold` | `--brand: #0A3D62` |
| Neutral | `--neutral-*` | `--neutral-900: #111827` |
| Semantic | `--color-success`, `--color-error` | `--color-success: #00D68F` |
| Shade scales | `--info-100` → `--info-900` | `--error-500: #FF3D71` |
| Typography | `--text-h1` → `--text-caption` | `--text-h3: 1.75rem` |
| Font weight | `--fw-regular` → `--fw-bold` | `--fw-semibold: 600` |
| Spacing | `--sp-1` → `--sp-20` | `--sp-4: 1rem` |
| Border radius | `--radius-sm` → `--radius-pill` | `--radius-pill: 50px` |
| Shadow | `--shadow-xs` → `--shadow-xl` | `--shadow-card` |
| Dark mode | `[data-theme="dark"]` overrides | |

---

### `@vnkr-labs/ui` — UI Component Library
**Files:** `index.css` (2927 dòng) · `index.js` (364 dòng)

CSS components + Vanilla JS. Không phụ thuộc framework.

**CSS Components:**

| Class | Mô tả |
|---|---|
| `.btn` + `.btn-primary` | Pill button filled đen |
| `.btn-outline-primary` | Pill button outlined |
| `.btn-ghost-primary` | Ghost button |
| `.btn-giant/large/medium/small` | Button sizes |
| `.form-input` | Input field với focus ring |
| `.form-label` / `.form-caption` | Label + helper text |
| `.vnkr-toggle` | iOS-style toggle switch |
| `.badge-published/draft/live/breaking` | Status badges |
| `.card` | Article/content card |
| `.vnkr-modal` | Modal dialog |
| `.vnkr-accordion-item` | Accordion |
| `.vnkr-dropdown` | Dropdown menu |
| `.vnkr-callout--info/success/warn/danger` | Alert callouts |
| `.vnkr-skeleton--text/circle/rect` | Skeleton loading |
| `.vnkr-timeline` | Timeline |

**JavaScript API (`window.VNKRUI`):**

```js
// Toast
VNKRUI.toast('Thành công!', 'success');
VNKRUI.toast('Lỗi rồi', 'error');
VNKRUI.toast('Cảnh báo', 'warning');
VNKRUI.toast('Thông tin', 'info');

// Theme (dark/light)
VNKRUI.theme.toggle();
VNKRUI.theme.set('dark');
VNKRUI.theme.set('light');

// Modal
VNKRUI.modal.open('modal-id');
VNKRUI.modal.close('modal-id');
```

**Peer dependency:** `@vnkr-labs/tokens >= 2.0.0`

---

### `@vnkr-labs/fe` — Frontend Site CSS
**File:** `index.css` (662 dòng)

CSS cho frontend website tin tức VNKR — kèm dark mode.

| Section | Classes |
|---|---|
| Top bar | `.top-bar` |
| Header | `.site-header`, `.logo-mark` |
| Navigation | `.main-nav`, `.nav-link` |
| Breaking | `.breaking-bar` |
| Vedette | `.vedette` |
| Cards | `.cat-block`, `.news-list-item` |
| Badges | `.cat-badge`, `.source-badge` |
| Sidebar | `.sidebar-widget`, `.widget-title` |
| Article | `.article-detail` |
| Comments | `.comment-box` |
| Pagination | `.vnkr-pagination` |
| Footer | `.site-footer` |
| Dark mode | `[data-theme="dark"]` |

**Peer dependencies:** `@vnkr-labs/tokens >= 2.0.0`, `@vnkr-labs/ui >= 2.0.0`

---

### `@vnkr-labs/admin` — Admin Panel CSS
**File:** `index.css` (716 dòng)

CSS cho admin panel (AdminLTE/Arise override) — kèm responsive mobile.

| Section | Mô tả |
|---|---|
| Token Bridge | Override `--bs-primary` → VNKR tokens |
| Sidebar | Brand colors, active state, submenu |
| Header | Top bar, search pill |
| Buttons | Pill system — primary/danger/success/warning |
| Badges | `badge-status-published/draft/review/archived` |
| Box/Card | `.box`, `.box-header` (Work Sans) |
| Tables | Responsive, thead brand, row hover |
| Forms | Focus ring, validation states |
| Stats Tiles | `.stats-tile`, color variants |
| Responsive | Mobile sidebar overlay + ESC |

**Peer dependency:** `@vnkr-labs/tokens >= 2.0.0`

---

## 🔄 Cập nhật phiên bản mới

```bash
# Sửa version trong package.json của package cần update, sau đó:
bash /var/www/vnkr.vn/packages/publish.sh
```

> Script [`publish.sh`](./publish.sh) tự động xác minh token, publish theo đúng thứ tự dependency.

---

## 🗂️ Tài liệu liên quan

| Tài liệu | Đường dẫn |
|---|---|
| Design System Plan | [`ý-tưởng/Design-system/DESIGN_SYSTEM_PLAN.md`](../ý-tưởng/Design-system/DESIGN_SYSTEM_PLAN.md) |
| Kế hoạch UI Mobile | [`ý-tưởng/UI/KE-HOACH-XAY-DUNG-UI.md`](../ý-tưởng/UI/KE-HOACH-XAY-DUNG-UI.md) |
| Source tokens CSS | [`public/assets/css/vnkr-tokens.css`](../public/assets/css/vnkr-tokens.css) |
| Source ui CSS/JS | [`public/assets/css/vnkr-ui.css`](../public/assets/css/vnkr-ui.css) · [`public/assets/js/vnkr-ui.js`](../public/assets/js/vnkr-ui.js) |
| Source fe CSS | [`public/assets/css/vnkr-fe.css`](../public/assets/css/vnkr-fe.css) |
| Source admin CSS | [`public/assets/css/vnkr-admin.css`](../public/assets/css/vnkr-admin.css) |

---

*Published: 23/09/2026 · Registry: npmjs.com · Org: @vnkr-labs · Owner: vnkr-io*
