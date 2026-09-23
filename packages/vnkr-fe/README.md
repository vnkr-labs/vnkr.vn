# @vnkr-io/fe

> VNKR Design System — Frontend Site CSS

CSS cho frontend website tin tức VNKR: top-bar, header, navigation, breaking ticker, article cards, sidebar, comments, pagination, footer — kèm dark mode.

## Cài đặt

```bash
npm install @vnkr-io/tokens @vnkr-io/ui @vnkr-io/fe
```

## Load order (bắt buộc)

```html
<link rel="stylesheet" href="@vnkr-io/tokens/index.css">
<link rel="stylesheet" href="@vnkr-io/ui/index.css">
<link rel="stylesheet" href="@vnkr-io/fe/index.css">
```

## Sections

| Section | Classes | Mô tả |
|---|---|---|
| Top bar | `.top-bar` | Breaking news ticker, social links |
| Header | `.site-header`, `.logo-mark` | Sticky header, logo |
| Navigation | `.main-nav`, `.nav-link` | Category nav, active underline |
| Breaking | `.breaking-bar` | Breaking news label + scroll |
| Vedette | `.vedette` | Hero article highlight |
| Cards | `.cat-block`, `.news-list-item` | Article listing |
| Badges | `.cat-badge`, `.source-badge` | Category + source labels |
| Sidebar | `.sidebar-widget`, `.widget-title` | Sidebar containers |
| Article | `.article-detail` | Full article page wrapper |
| Comments | `.comment-box` | Comment section |
| Pagination | `.vnkr-pagination` | Page navigation |
| Footer | `.site-footer` | Footer layout |
| Dark mode | `[data-theme="dark"]` | All above with dark overrides |

## Peer Dependencies

```json
{
  "@vnkr-io/tokens": ">=2.0.0",
  "@vnkr-io/ui": ">=2.0.0"
}
```
