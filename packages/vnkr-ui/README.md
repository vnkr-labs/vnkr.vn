# @vnkr-io/ui

> VNKR Design System — UI Component Library

CSS components + vanilla JavaScript — buttons, forms, cards, modals, accordion, dropdown, dark mode.

## Cài đặt

```bash
npm install @vnkr-io/tokens @vnkr-io/ui
```

## Sử dụng

```html
<!-- Bắt buộc load tokens TRƯỚC -->
<link rel="stylesheet" href="node_modules/@vnkr-io/tokens/index.css">
<link rel="stylesheet" href="node_modules/@vnkr-io/ui/index.css">
<script src="node_modules/@vnkr-io/ui/index.js" defer></script>
```

## Components CSS

| Class | Mô tả |
|---|---|
| `.btn` + `.btn-primary` | Pill button — filled black |
| `.btn-outline-primary` | Pill button — outlined |
| `.btn-ghost-primary` | Ghost button |
| `.btn-giant` / `.btn-large` / `.btn-medium` / `.btn-small` | Sizes |
| `.vnkr-btn` | VNKR branded button |
| `.form-input` | Input field với focus ring |
| `.form-label` | Label trên input |
| `.form-caption.error` | Error message |
| `.vnkr-toggle` | iOS-style toggle switch |
| `.badge` + `.badge-published` / `.badge-live` / `.badge-breaking` | Status badges |
| `.card` | Article/content card |
| `.vnkr-modal` | Modal dialog |
| `.vnkr-accordion-item` | Accordion FAQ |
| `.vnkr-dropdown` | Dropdown menu |
| `.vnkr-callout--info/success/warn/danger` | Alert callout boxes |
| `.vnkr-skeleton--text/circle/rect` | Skeleton loading |
| `.vnkr-timeline` | Timeline component |

## JavaScript API

```js
// Toast notifications
VNKRUI.toast('Thành công!', 'success');
VNKRUI.toast('Có lỗi xảy ra', 'error');
VNKRUI.toast('Chú ý', 'warning');
VNKRUI.toast('Thông tin', 'info');

// Theme
VNKRUI.theme.toggle();
VNKRUI.theme.set('dark');
VNKRUI.theme.set('light');

// Modal
VNKRUI.modal.open('modal-id');
VNKRUI.modal.close('modal-id');
```

## Peer Dependencies

```json
{
  "@vnkr-io/tokens": ">=2.0.0"
}
```
