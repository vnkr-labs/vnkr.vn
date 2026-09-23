# @vnkr-io/admin

> VNKR Design System — Admin Panel CSS

CSS cho admin panel VNKR (dựa trên AdminLTE/Arise): token bridge, sidebar, header, pill buttons, status badges, tables, forms, stats tiles — kèm responsive mobile.

## Cài đặt

```bash
npm install @vnkr-io/tokens @vnkr-io/admin
```

## Load order (bắt buộc — sau AdminLTE/Bootstrap)

```html
<link rel="stylesheet" href="@vnkr-io/tokens/index.css">
<!-- bootstrap / adminlte / arise ở đây -->
<link rel="stylesheet" href="@vnkr-io/admin/index.css">
```

## Sections

| Section | Mô tả |
|---|---|
| Token Bridge | Override Bootstrap vars (`--bs-primary`) → VNKR tokens |
| Sidebar | VNKR brand colors, active state, submenu |
| Header | Top bar, search pill, theme toggle |
| Buttons | Pill system — primary/danger/success/warning/default |
| Badges | `.badge-status-published/draft/review/archived` |
| Box/Card | `.box`, `.box-header`, `.box-title` (Work Sans) |
| Tables | Responsive wrapper, thead brand, row hover |
| Forms | Focus ring, validation states |
| Stats Tiles | `.stats-tile`, color variants |
| Typography | Work Sans headings, Be Vietnam Pro body |
| Responsive | Mobile sidebar overlay + ESC, table scroll |

## Peer Dependencies

```json
{
  "@vnkr-io/tokens": ">=2.0.0"
}
```
