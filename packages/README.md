# VNKR Labs — Packages

> Monorepo chứa toàn bộ packages của VNKR Design System

## Packages

| Package | Phiên bản | Mô tả |
|---|---|---|
| [`@vnkr-io/tokens`](./packages/vnkr-tokens) | 2.0.0 | CSS Design Tokens |
| [`@vnkr-io/ui`](./packages/vnkr-ui) | 2.0.0 | UI Component Library (CSS + JS) |
| [`@vnkr-io/fe`](./packages/vnkr-fe) | 2.0.0 | Frontend Site CSS |
| [`@vnkr-io/admin`](./packages/vnkr-admin) | 2.0.0 | Admin Panel CSS |

## Load order

```html
<!-- 1. Tokens — phải load đầu tiên -->
<link rel="stylesheet" href="@vnkr-io/tokens/index.css">

<!-- 2. UI components -->
<link rel="stylesheet" href="@vnkr-io/ui/index.css">
<script src="@vnkr-io/ui/index.js" defer></script>

<!-- 3. Site-specific (chọn 1) -->
<link rel="stylesheet" href="@vnkr-io/fe/index.css">      <!-- Frontend -->
<!-- hoặc -->
<link rel="stylesheet" href="@vnkr-io/admin/index.css">   <!-- Admin -->
```

## Cài đặt nhanh

```bash
# Frontend
npm install @vnkr-io/tokens @vnkr-io/ui @vnkr-io/fe

# Admin
npm install @vnkr-io/tokens @vnkr-io/ui @vnkr-io/admin

# Tất cả
npm install @vnkr-io/tokens @vnkr-io/ui @vnkr-io/fe @vnkr-io/admin
```

## Phát triển

```bash
# Publish tất cả packages
npm run publish:all

# Publish từng package
npm run publish:tokens
npm run publish:ui
npm run publish:fe
npm run publish:admin
```

## Design System Documentation

- [Kế hoạch Design System](../ý-tưởng/Design-system/DESIGN_SYSTEM_PLAN.md)
- [Kế hoạch UI Mobile](../ý-tưởng/UI/KE-HOACH-XAY-DUNG-UI.md)

## License

MIT © VNKR Labs
