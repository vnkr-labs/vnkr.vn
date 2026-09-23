# 📦 Nhận xét & Gợi ý Bộ Lõi Package — @vnkr-labs

> **Ngày:** 23/09/2026  
> **Nguồn tham khảo:** [npmjs.com/settings/vnkr-labs/packages](https://www.npmjs.com/settings/vnkr-labs/packages)  
> **Benchmark:** @radix-ui (71 pkgs), @tanstack (371 pkgs), @floating-ui (9 pkgs), Storybook-bot (230 pkgs)

---

## 1. Hiện trạng — 4 packages đã có

| Package | Version | Size | Mô tả |
|---|---|---|---|
| `@vnkr-labs/tokens` | 2.0.0 | 15.4 kB | CSS Custom Properties (design tokens) |
| `@vnkr-labs/ui` | 2.0.0 | 92.1 kB | UI Component Library (CSS + Vanilla JS) |
| `@vnkr-labs/fe` | 2.0.0 | 32.9 kB | Frontend site CSS (news/media layout) |
| `@vnkr-labs/admin` | 2.0.0 | 24.2 kB | Admin panel CSS |

**Nhận xét:** Đây là bộ nền tảng tốt — tokens → ui → site-specific. Tuy nhiên đang thiếu **14 nhóm package quan trọng** để phục vụ đa dạng đối tượng: doanh nghiệp, chính phủ, trường học, nhà phát triển độc lập.

---

## 2. Phân tích thiếu sót theo nhóm đối tượng

### 🏢 Doanh nghiệp (Enterprise)
| Thiếu | Lý do cần |
|---|---|
| Framework adapters (React, Vue) | 90% dự án doanh nghiệp dùng JS framework, không dùng thuần CSS |
| Form validation library | Enterprise forms phức tạp — multi-step, schema validation |
| Data table component | Hiển thị dữ liệu lớn, sortable, filterable, pagination |
| Chart/Analytics | Dashboard, báo cáo — yêu cầu bắt buộc của mọi doanh nghiệp |
| Auth/Permission helpers | Role-based access control pattern |

### 🏛️ Chính phủ (Government)
| Thiếu | Lý do cần |
|---|---|
| Accessibility package (`@vnkr-labs/a11y`) | Chuẩn WCAG 2.1 AA bắt buộc cho dịch vụ công |
| Typography Vietnamese | Font tiếng Việt chuẩn, dấu đúng |
| Print/PDF styles | Biểu mẫu, văn bản hành chính |
| High-contrast theme | Người khiếm thị, màn hình kém |

### 🎓 Trường học (Education)
| Thiếu | Lý do cần |
|---|---|
| Theme variants (edu/gov/corp) | Mỗi tổ chức muốn màu sắc riêng |
| Quiz/Survey components | Form hỏi đáp, khảo sát học sinh |
| Progress tracking UI | Tiến trình học tập, gamification |

### 👨‍💻 Nhà phát triển (Developer)
| Thiếu | Lý do cần |
|---|---|
| CLI tool (`create-vnkr`) | Scaffold project nhanh như `create-react-app` |
| ESLint/Prettier config | Chuẩn hóa code style toàn team |
| Storybook integration | Document và demo components |
| TypeScript types | Type-safe khi dùng trong TS projects |
| React Native tokens | Mobile app development |

---

## 3. Danh sách 26 packages cần bổ sung

### Tier 1 — Cốt lõi (publish ngay, blockers hiện tại)

```
@vnkr-labs/icons          SVG icon set — sprite, individual, webfont
@vnkr-labs/types          TypeScript type definitions cho toàn bộ hệ thống
@vnkr-labs/mobile         React Native design tokens + StyleSheet helpers
@vnkr-labs/a11y           Accessibility utilities — ARIA helpers, focus trap, screen reader
@vnkr-labs/utils          Helper functions — cn(), formatDate(), formatCurrency(VND)
```

### Tier 2 — Framework Adapters (Q4 2026)

```
@vnkr-labs/react          React component library (headless + styled)
@vnkr-labs/vue            Vue 3 component library
@vnkr-labs/blade          Laravel Blade component macros + directives
@vnkr-labs/svelte         Svelte component library
```

### Tier 3 — Feature Packages (Q1 2027)

```
@vnkr-labs/charts         Chart components (area, bar, pie, line) — dựa trên vnkr-tokens
@vnkr-labs/forms          Form validation schema + error display pattern
@vnkr-labs/table          Data table — sortable, filterable, paginated, exportable
@vnkr-labs/toast          Standalone toast notification (tách khỏi vnkr-ui)
@vnkr-labs/modal          Standalone modal/dialog/drawer
@vnkr-labs/date-picker    Date/time picker component
```

### Tier 4 — Themes & Variants (Q1 2027)

```
@vnkr-labs/theme-gov      Chủ đề chính phủ Việt Nam (màu cờ đỏ + vàng, font chuẩn)
@vnkr-labs/theme-edu      Chủ đề giáo dục (màu xanh học thuật, accessible)
@vnkr-labs/theme-corp     Chủ đề doanh nghiệp (neutral, professional)
@vnkr-labs/theme-dark     Dark mode standalone (không cần load full tokens)
@vnkr-labs/theme-print    Print/PDF stylesheet — biểu mẫu, văn bản hành chính
```

### Tier 5 — Tooling & DX (Q2 2027)

```
@vnkr-labs/cli            CLI tool — scaffold, sync tokens, bump + publish
create-vnkr               Project scaffolding (npm create vnkr)
@vnkr-labs/eslint-config  ESLint config chuẩn VNKR
@vnkr-labs/prettier       Prettier config chuẩn VNKR
@vnkr-labs/tsconfig       TypeScript config base (web, node, react-native)
@vnkr-labs/storybook      Storybook preset + theme cho VNKR components
```

---

## 4. Lộ trình theo mức độ ưu tiên

```
                    Hiện có ✅
                    ──────────────
                    @vnkr-labs/tokens
                    @vnkr-labs/ui
                    @vnkr-labs/fe
                    @vnkr-labs/admin

    Tier 1 (Q4/2026) — Cốt lõi bắt buộc
    ──────────────────────────────────────────
    icons · types · mobile · a11y · utils

    Tier 2 (Q4/2026) — Framework adapters
    ──────────────────────────────────────────
    react · vue · blade · svelte

    Tier 3 (Q1/2027) — Feature packages
    ──────────────────────────────────────────
    charts · forms · table · toast · modal · date-picker

    Tier 4 (Q1/2027) — Themes
    ──────────────────────────────────────────
    theme-gov · theme-edu · theme-corp · theme-dark · theme-print

    Tier 5 (Q2/2027) — DX tooling
    ──────────────────────────────────────────
    cli · create-vnkr · eslint-config · prettier · tsconfig · storybook
```

---

## 5. Dependency tree đầy đủ (sau khi hoàn thiện)

```
@vnkr-labs/tokens (v2)            ← nguồn sự thật duy nhất
│
├── @vnkr-labs/types               ← TypeScript definitions
├── @vnkr-labs/icons               ← SVG icons
├── @vnkr-labs/a11y                ← Accessibility helpers
├── @vnkr-labs/utils               ← Helper functions
│
├── @vnkr-labs/ui                  ← CSS + Vanilla JS (đã có)
│   ├── @vnkr-labs/toast           ← tách ra standalone
│   ├── @vnkr-labs/modal           ← tách ra standalone
│   ├── @vnkr-labs/forms           ← form validation
│   ├── @vnkr-labs/table           ← data table
│   ├── @vnkr-labs/charts          ← charts/analytics
│   └── @vnkr-labs/date-picker     ← date/time picker
│
├── @vnkr-labs/react               ← React components
│   └── @vnkr-labs/mobile          ← React Native
│
├── @vnkr-labs/vue                 ← Vue 3 components
├── @vnkr-labs/svelte              ← Svelte components
├── @vnkr-labs/blade               ← Laravel Blade macros
│
├── @vnkr-labs/fe                  ← (đã có) News/media site
├── @vnkr-labs/admin               ← (đã có) Admin panel
│
├── @vnkr-labs/theme-gov           ← Chủ đề chính phủ VN
├── @vnkr-labs/theme-edu           ← Chủ đề giáo dục
├── @vnkr-labs/theme-corp          ← Chủ đề doanh nghiệp
├── @vnkr-labs/theme-dark          ← Dark mode standalone
└── @vnkr-labs/theme-print         ← Print/PDF styles

Tooling (devDependencies):
├── @vnkr-labs/cli
├── create-vnkr
├── @vnkr-labs/eslint-config
├── @vnkr-labs/prettier
├── @vnkr-labs/tsconfig
└── @vnkr-labs/storybook
```

---

## 6. Package ưu tiên cao nhất — nên làm trước

### 🥇 `@vnkr-labs/icons` (làm ngay)
**Lý do:** Mọi dự án đều cần icons. Source SVG đã có sẵn tại `packages/icons/icons/` (vnkr-icons v3.46.0 — filled + outline). Chỉ cần đóng gói và publish.

```bash
# Source đã có:
packages/icons/icons/icons/filled/*.svg   # ~500+ icons
packages/icons/icons/icons/outline/*.svg  # ~500+ icons
```

**Deliverables:**
- `@vnkr-labs/icons/sprite.svg` — SVG sprite tổng hợp
- `@vnkr-labs/icons/index.css` — CSS class `.icon-*`
- `@vnkr-labs/icons/index.js` — JS helper `getIconPath('check')`
- `@vnkr-labs/icons/react.js` — React `<Icon name="check" />` (optional)

---

### 🥈 `@vnkr-labs/types` (làm ngay)
**Lý do:** Mọi dự án TypeScript cần types. Không có code logic, chỉ là `.d.ts`.

```typescript
// @vnkr-labs/types
export type ColorToken = '--brand' | '--accent' | '--gold' | ...
export type SpacingToken = '--sp-1' | '--sp-2' | ... | '--sp-20'
export type ButtonVariant = 'primary' | 'outline' | 'ghost' | 'danger'
export type ThemeMode = 'light' | 'dark' | 'system'
export type ToastType = 'success' | 'error' | 'warning' | 'info'
```

---

### 🥉 `@vnkr-labs/utils` (làm ngay)
**Lý do:** Mọi dự án Việt Nam cần `formatVND()`, `formatDate()` tiếng Việt. Zero dependencies.

```typescript
// @vnkr-labs/utils
formatVND(1500000)          // → "1.500.000 ₫"
formatDate('2026-09-23', 'vi') // → "23 tháng 9, 2026"
cn('btn', isActive && 'btn-active') // → classNames helper
truncate('Lorem ipsum...', 100)
slugify('Hà Nội đẹp')       // → "ha-noi-dep"
readingTime('<p>...</p>')    // → "5 phút đọc"
```

---

### `@vnkr-labs/a11y` (Q4/2026 — quan trọng cho chính phủ)
**Lý do:** Chuẩn WCAG 2.1 AA bắt buộc với dịch vụ công/chính phủ Việt Nam.

```typescript
// @vnkr-labs/a11y
focusTrap(element)          // Giữ focus trong modal
announce('Đã lưu thành công') // Screen reader announcement
skipLink()                  // "Bỏ qua điều hướng"
colorContrast('#0A3D62', '#fff') // → ratio 8.2:1 ✅ AA
```

---

### `@vnkr-labs/react` (Q4/2026 — quan trọng nhất về adoption)
**Lý do:** 85% dự án web mới dùng React. Không có React adapter thì hầu hết dev không dùng được.

---

## 7. So sánh với các hệ sinh thái lớn

| Tiêu chí | @vnkr-labs hiện tại | @vnkr-labs mục tiêu | Benchmark |
|---|---|---|---|
| Tổng packages | **4** | 30+ | Radix: 71, Tanstack: 371 |
| Design tokens | ✅ | ✅ | Mọi DS đều có |
| Framework adapters | ❌ | React, Vue, Blade, Svelte | Radix: React only |
| TypeScript support | ❌ | `@vnkr-labs/types` | Radix: ✅ built-in |
| Accessibility | Cơ bản | `@vnkr-labs/a11y` | Radix: ✅ headless a11y |
| Icons | Có file nhưng chưa publish | `@vnkr-labs/icons` | Tabler, Lucide |
| CLI/Scaffolding | `publish.sh` | `create-vnkr` | Shadcn: ✅ CLI |
| Dark mode | ✅ | ✅ | Mọi DS đều có |
| Mobile (RN) | Kế hoạch | `@vnkr-labs/mobile` | Tamagui, NativeWind |
| Themes | Cơ bản | gov, edu, corp, dark, print | MUI: 2 themes |
| Localization VN | ❌ | `@vnkr-labs/utils` (formatVND) | — |
| Government-ready | ❌ | `@vnkr-labs/theme-gov` + a11y | — |

---

## 8. Kết luận & Hành động ngay

**Bộ lõi tối thiểu cần có trước khi giới thiệu rộng rãi:**

```bash
# Publish ngay (source đã sẵn sàng)
@vnkr-labs/icons    ← source SVG đã có tại packages/icons/
@vnkr-labs/types    ← chỉ cần viết .d.ts
@vnkr-labs/utils    ← formatVND, formatDate, cn(), slugify

# Cần xây dựng (Q4/2026)
@vnkr-labs/react    ← React wrapper cho vnkr-ui components
@vnkr-labs/a11y     ← WCAG 2.1 AA helpers
```

Sau 3 packages trên, `@vnkr-labs` sẽ **dùng được cho 95% dự án** — từ doanh nghiệp, chính phủ, trường học đến developer cá nhân.

---

*Tài liệu nhận xét: VNKR Labs Engineering*  
*Ngày: 23/09/2026 | Phiên bản: 1.0*  
*Org: [npmjs.com/org/vnkr-labs](https://www.npmjs.com/org/vnkr-labs)*
