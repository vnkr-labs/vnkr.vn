# @vnkr-io/tokens

> VNKR Design System — CSS Design Tokens

CSS custom properties (design tokens) — nguồn sự thật duy nhất cho toàn bộ hệ thống UI VNKR.

## Cài đặt

```bash
npm install @vnkr-io/tokens
# hoặc
yarn add @vnkr-io/tokens
```

## Sử dụng

### HTML/Blade
```html
<link rel="stylesheet" href="node_modules/@vnkr-io/tokens/index.css">
```

### CSS @import
```css
@import '@vnkr-io/tokens';
```

### Vite / webpack
```js
import '@vnkr-io/tokens';
```

## Token có sẵn

| Nhóm | Prefix | Ví dụ |
|---|---|---|
| Brand colors | `--brand`, `--accent`, `--gold` | `--brand: #0A3D62` |
| Neutral | `--neutral-*` | `--neutral-900: #111827` |
| Semantic | `--color-success`, `--color-error` | `--color-success: #00D68F` |
| Shade scales | `--info-100` → `--info-900` | `--error-500: #FF3D71` |
| Typography | `--text-h1` → `--text-caption` | `--text-h3: 1.75rem` |
| Font weight | `--fw-regular` → `--fw-bold` | `--fw-semibold: 600` |
| Spacing | `--sp-1` → `--sp-20` | `--sp-4: 1rem` |
| Border radius | `--radius-sm` → `--radius-pill` | `--radius-pill: 50px` |
| Shadow | `--shadow-xs` → `--shadow-xl` | `--shadow-card` |
| Transitions | `--transition-fast`, `--transition-base` | |
| Dark mode | `[data-theme="dark"]` overrides | |

## Dark Mode

```html
<!-- Toggle bằng attribute -->
<html data-theme="dark">

<!-- Hoặc dùng JS -->
document.documentElement.setAttribute('data-theme', 'dark');
```

## Phiên bản

- **2.0.0** — Sprint 1–7 complete, dark mode, shade scales, layout tokens
