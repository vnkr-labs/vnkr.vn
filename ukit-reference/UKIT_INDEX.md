# UKIT Reference — AXQ Design System

Thư mục này là bản giải nén của `project.zip` — monorepo AXQ/AxioPass.
Dùng để **tham khảo pattern CSS, tokens, component API** khi xây dựng VNKR UI Library.

> ⚠️ Đây là tài liệu tham khảo, không phải code VNKR. Không import hay deploy trực tiếp.

---

## Cấu trúc quan trọng

```
ukit-reference/
├── assets/css/                       ← CSS tĩnh cho doc site
│   ├── tokens.css                    ← Design tokens (CSS vars)
│   ├── base.css                      ← Reset + utilities + layout
│   └── header.css                    ← Site header component
│
├── packages/design-system/
│   └── src/components/               ← 24 React components (module.css)
│       ├── Alert/                    ← Alert, SecurityAlert
│       ├── Avatar/
│       ├── Badge/
│       ├── Button/
│       ├── Card/                     ← Card + CardVisual (3D flip)
│       ├── Checkbox/
│       ├── Dropdown/
│       ├── EmptyState/
│       ├── GasFeeSelector/
│       ├── Input/                    ← size: large / medium / small
│       ├── LivenessFrame/            ← KYC face scan frame
│       ├── Modal/
│       ├── Navbar/                   ← Bottom tab bar (mobile)
│       ├── OTPInput/
│       ├── PINPad/
│       ├── ProgressBar/
│       ├── QRDisplay/
│       ├── SearchBar/
│       ├── Skeleton/                 ← Loading pulse animation
│       ├── Toast/
│       ├── Toggle/
│       ├── Tooltip/
│       └── crypto/                   ← CoinIcon, NamespaceBadge, AddressDisplay, PasskeyButton
│
├── apps/
│   ├── axiopass-wallet/              ← React Native wallet app (screens)
│   ├── axq-governance-ui/            ← Governance web app
│   └── kpx-dex-frontend/             ← DEX trading frontend
│
└── smart-contracts/
    └── kpx-liquidity/                ← Solidity contracts
```

---

## Design Tokens chính (`assets/css/tokens.css`)

### Màu sắc
| Token | Value | Ghi chú |
|-------|-------|---------|
| `--color-accent-1` | `#3872f5` | Primary blue |
| `--color-accent-2` | `#9253d8` | Purple |
| `--color-accent-3` | `#e63967` | Pink/Red |
| `--color-accent-4` | `#43ac62` | Green |
| `--bg-body` | `#F7F9FC` | Page background |
| `--bg-card` | `#FFFFFF` | Card surface |
| `--text-main` | `#1a1a1a` | Body text |
| `--text-muted` | `#757d8f` | Secondary text |
| `--border-color` | `#E4E6EA` | Default border |

### Dark Mode — toggle via `html.dark`
Toàn bộ tokens được override trong `html.dark { }` — không cần class riêng từng component.

### Typography
| Token | Value |
|-------|-------|
| `--font-sans` | `'Work Sans', system-ui` |
| `--text-xs/sm/base/md/lg/xl/2xl/3xl` | 11 → 48px |
| `--weight-normal/medium/semibold/bold` | 400/500/600/700 |

### Spacing
`--space-1` (4px) → `--space-16` (64px)

### Radius
`--radius-sm` (4px) → `--radius-xl` (16px) → `--radius-full` (9999px)

---

## Patterns học được — áp dụng cho VNKR

### 1. Header với glassmorphism
```css
/* assets/css/header.css */
.site-header {
    background: color-mix(in srgb, var(--bg-card) 85%, transparent);
    backdrop-filter: blur(16px);
    border-bottom: 1px solid var(--border-color);
}
```
**VNKR tương đương:** `.vnkr-nav` — có thể thêm `backdrop-filter` khi scroll

### 2. Nav pill active state
```css
.nav-pill.active {
    background:   var(--color-accent-1);
    color:        #fff;
    border-color: var(--color-accent-1);
}
```
**VNKR tương đương:** `.vnkr-nav-links a.active` — hiện dùng `color: var(--gold)`

### 3. Callout boxes (VNKR chưa có)
```css
.callout { border-left: 3px solid; border-radius: var(--radius-md); padding: 12px 16px; }
.callout-info    { background: rgba(56,114,245,0.07);  color: #1d4ed8; }
.callout-warn    { background: rgba(245,196,94,0.12);  color: #92400e; }
.callout-danger  { background: rgba(230,57,103,0.08);  color: #9f1239; }
.callout-success { background: rgba(67,172,98,0.08);   color: #166534; }
```
→ **Thêm vào `vnkr-ui.css`** dưới dạng `.vnkr-callout--*`

### 4. KPI Box pattern
```css
.kpi-box { background: var(--bg-body); border: 1px solid; border-radius: var(--radius-lg); text-align: center; }
.kpi-val { font-size: var(--text-2xl); font-weight: bold; line-height: 1; }
.kpi-lbl { font-size: var(--text-xs); color: var(--text-muted); }
```
→ **Tương đương** `.vnkr-stat-num` / `.vnkr-stat-label` trong VNKR

### 5. Timeline component (VNKR chưa có)
```css
.tl-wrap { position: relative; padding-left: 28px; }
.tl-wrap::before { content:''; position:absolute; left:9px; width:2px; background: var(--border-color); }
.tl-dot { position:absolute; left:-24px; width:12px; height:12px; border-radius:50%; }
```
→ **Có thể thêm** cho roadmap timeline thay vì dùng grid cards hiện tại

### 6. Skeleton loading
```css
/* packages/design-system/components/Skeleton */
@keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.45} }
```
→ **Thêm vào `vnkr-ui.js`** để làm placeholder khi load bài viết

### 7. Input component — data-size variant
```css
.wrapper[data-size="large"]  { --input-h: 52px; }
.wrapper[data-size="medium"] { --input-h: 44px; }
```
→ Pattern dùng `data-*` attribute để control variant — sạch hơn class modifier

---

## Components VNKR nên thêm (học từ UKIT)

| Component | File tham khảo | Ưu tiên |
|-----------|----------------|---------|
| `.vnkr-callout` | `base.css` → `.callout-*` | 🔴 Cao — dùng trong bài viết |
| `.vnkr-timeline` | `base.css` → `.tl-*` | 🟡 Trung — roadmap |
| `.vnkr-skeleton` | `Skeleton/Skeleton.module.css` | 🟡 Trung — loading state |
| `.vnkr-input` | `Input/Input.module.css` | 🟠 Cao — form search, login |
| `.vnkr-modal` | `Modal/Modal.module.css` | 🟡 Trung — lightbox ảnh |
| `.vnkr-toggle` | `Toggle/Toggle.module.css` | 🟢 Thấp — dark mode switch |
| Dark mode | `tokens.css` → `html.dark {}` | 🟡 Trung — Phase 2 |

---

*Tham khảo nội bộ — VNKR.VN / Phạm Thế Bảo / TheKingBao*
