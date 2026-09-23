# 🏗️ Kế Hoạch Xây Dựng Thư Viện Hệ Thống Gốc — VNKR Labs

> **Mô hình tham khảo:** [storybook-bot @ npmjs](https://www.npmjs.com/~storybook-bot) — 230+ packages theo lộ trình ngược thời gian  
> **Mục tiêu:** Xây dựng hệ sinh thái packages VNKR Labs tương tự — từ design tokens → UI components → site-specific layers  
> **Registry:** [@vnkr-labs](https://www.npmjs.com/org/vnkr-labs) · **Owner:** [vnkr-io](https://www.npmjs.com/~vnkr-io)

---

## MỤC LỤC

1. [Triết lý thiết kế hệ thống](#1-triết-lý-thiết-kế-hệ-thống)
2. [Lộ trình phát triển theo giai đoạn](#2-lộ-trình-phát-triển-theo-giai-đoạn)
3. [Kiến trúc package dependency](#3-kiến-trúc-package-dependency)
4. [Danh sách packages theo giai đoạn](#4-danh-sách-packages-theo-giai-đoạn)
5. [Script tự động hoá](#5-script-tự-động-hoá)
6. [Tham khảo: Clone Storybook Bot ecosystem](#6-tham-khảo-clone-storybook-bot-ecosystem)

---

## 1. Triết lý thiết kế hệ thống

### 1.1 Học từ Storybook Bot

[Storybook Bot](https://www.npmjs.com/~storybook-bot) xây dựng 230+ packages theo **lộ trình phân tầng rõ ràng**:

```
Giai đoạn 1 — Core primitives    (tokens, types, shared utils)
      ↓
Giai đoạn 2 — Builder & bundler  (webpack, vite adapters)
      ↓
Giai đoạn 3 — Framework renderers (react, vue, angular, svelte...)
      ↓
Giai đoạn 4 — Addons & tooling   (a11y, docs, testing, themes...)
      ↓
Giai đoạn 5 — DX wrappers        (CLI, create-*, MCP, AI agents)
```

### 1.2 Lộ trình VNKR Labs (áp dụng tương tự)

```
Giai đoạn 1 — @vnkr-labs/tokens   ← ĐÃ XONG ✅ (v2.0.0)
      ↓
Giai đoạn 2 — @vnkr-labs/ui       ← ĐÃ XONG ✅ (v2.0.0)
      ↓
Giai đoạn 3 — @vnkr-labs/fe       ← ĐÃ XONG ✅ (v2.0.0)
              @vnkr-labs/admin     ← ĐÃ XONG ✅ (v2.0.0)
      ↓
Giai đoạn 4 — @vnkr-labs/mobile   ← KẾ HOẠCH (React Native)
              @vnkr-labs/icons     ← KẾ HOẠCH
              @vnkr-labs/charts    ← KẾ HOẠCH
      ↓
Giai đoạn 5 — @vnkr-labs/cli      ← TƯƠNG LAI
              create-vnkr          ← TƯƠNG LAI
```

---

## 2. Lộ trình phát triển theo giai đoạn

### Giai đoạn 1 — Foundation (2025 Q3) ✅ HOÀN THÀNH

| Package | Version | Mô tả | Status |
|---|---|---|---|
| `@vnkr-labs/tokens` | 2.0.0 | CSS Design Tokens — màu, font, spacing, radius, shadow | ✅ Live |
| `@vnkr-labs/ui` | 2.0.0 | UI Component Library (CSS + Vanilla JS) | ✅ Live |
| `@vnkr-labs/fe` | 2.0.0 | Frontend Site CSS | ✅ Live |
| `@vnkr-labs/admin` | 2.0.0 | Admin Panel CSS | ✅ Live |

### Giai đoạn 2 — Expansion (2025 Q4)

| Package | Version | Mô tả | Status |
|---|---|---|---|
| `@vnkr-labs/icons` | 1.0.0 | SVG icon set VNKR brand | 📋 Planned |
| `@vnkr-labs/charts` | 1.0.0 | Chart components (dựa trên vnkr-tokens) | 📋 Planned |
| `@vnkr-labs/mobile` | 1.0.0 | React Native design tokens (từ KE-HOACH-XAY-DUNG-UI.md) | 📋 Planned |

### Giai đoạn 3 — Framework Adapters (2026 Q1)

| Package | Version | Mô tả | Status |
|---|---|---|---|
| `@vnkr-labs/react` | 1.0.0 | React components (headless + styled) | 🔮 Future |
| `@vnkr-labs/vue` | 1.0.0 | Vue 3 components | 🔮 Future |
| `@vnkr-labs/blade` | 1.0.0 | Laravel Blade component macros | 🔮 Future |

### Giai đoạn 4 — Tooling & DX (2026 Q2)

| Package | Version | Mô tả | Status |
|---|---|---|---|
| `@vnkr-labs/cli` | 1.0.0 | CLI tool — scaffold, publish, sync tokens | 🔮 Future |
| `create-vnkr` | 1.0.0 | Project scaffolding (`npm create vnkr`) | 🔮 Future |
| `@vnkr-labs/eslint-config` | 1.0.0 | ESLint config chuẩn VNKR | 🔮 Future |

---

## 3. Kiến trúc Package Dependency

```
                    @vnkr-labs/tokens (v2.0.0)
                           │
              ┌────────────┼─────────────┐
              │            │             │
        @vnkr-labs/ui  @vnkr-labs/mobile  @vnkr-labs/icons
              │
       ┌──────┴──────┐
       │             │
  @vnkr-labs/fe  @vnkr-labs/admin
       │
  @vnkr-labs/react (future)
  @vnkr-labs/vue   (future)
  @vnkr-labs/blade (future)
```

**Quy tắc dependency:**
- Mọi package đều `peerDependencies: @vnkr-labs/tokens >= 2.0.0`
- Không có circular dependencies
- Mỗi package độc lập, có thể dùng riêng lẻ

---

## 4. Danh sách packages theo giai đoạn

### 4.1 Packages đã publish (v2.0.0)

```
@vnkr-labs/tokens   — 15.8 kB  — CSS Custom Properties
@vnkr-labs/ui       — 94.3 kB  — CSS components + Vanilla JS
@vnkr-labs/fe       — 33.7 kB  — Frontend site CSS
@vnkr-labs/admin    — 24.7 kB  — Admin panel CSS
```

### 4.2 Packages kế hoạch giai đoạn 2

```
@vnkr-labs/icons    — SVG icon set  — sprite + individual SVG + webfont
@vnkr-labs/charts   — Chart CSS/JS  — area, bar, pie, line (dùng vnkr-tokens)
@vnkr-labs/mobile   — RN tokens     — React Native StyleSheet từ vnkr-tokens
```

---

## 5. Script tự động hoá

### 5.1 Publish tất cả packages

```bash
# Publish toàn bộ packages hiện có
bash /var/www/vnkr.vn/packages/publish.sh
```

### 5.2 Script tự động sync source → packages

Lưu thành `/var/www/vnkr.vn/packages/sync.sh`:

```bash
#!/usr/bin/env bash
# Sync source files từ public/assets/ vào packages/
set -e

ROOT="/var/www/vnkr.vn"

echo "🔄 Syncing source files vào packages..."

cp "$ROOT/public/assets/css/vnkr-tokens.css" "$ROOT/packages/vnkr-tokens/index.css"
cp "$ROOT/public/assets/css/vnkr-ui.css"     "$ROOT/packages/vnkr-ui/index.css"
cp "$ROOT/public/assets/js/vnkr-ui.js"       "$ROOT/packages/vnkr-ui/index.js"
cp "$ROOT/public/assets/css/vnkr-fe.css"     "$ROOT/packages/vnkr-fe/index.css"
cp "$ROOT/public/assets/css/vnkr-admin.css"  "$ROOT/packages/vnkr-admin/index.css"

echo "✅ Sync hoàn tất!"
echo ""
echo "Tiếp theo: bump version trong package.json rồi chạy:"
echo "  bash $ROOT/packages/publish.sh"
```

### 5.3 Bump version và publish

```bash
# Bump patch version tất cả packages (2.0.0 → 2.0.1)
for pkg in vnkr-tokens vnkr-ui vnkr-fe vnkr-admin; do
  cd /var/www/vnkr.vn/packages/$pkg
  npm version patch --no-git-tag-version
done

# Publish
bash /var/www/vnkr.vn/packages/publish.sh
```

---

## 6. Tham khảo: Clone Storybook Bot Ecosystem

Tập lệnh bash dưới đây liệt kê và clone toàn bộ 230 packages từ tài khoản [`storybook-bot` trên npm](https://www.npmjs.com/~storybook-bot) theo đúng **lộ trình ngược thời gian** (mới nhất → cũ nhất).

Lưu thành `clone-storybook-bot.sh`, sau đó:

```bash
chmod +x clone-storybook-bot.sh
./clone-storybook-bot.sh
```

```bash
#!/usr/bin/env bash

# Dừng lại nếu có lỗi xảy ra
set -e

echo "🚀 Bắt đầu quá trình chuẩn bị clone 230 packages của storybook-bot theo lộ trình ngược thời gian..."

# Danh sách 230 packages được sắp xếp theo thứ tự ngược thời gian (mới nhất -> cũ nhất)
# dựa trên dữ liệu trích xuất từ trang npm profile chính thức.

packages=(
  # === GIAI ĐOẠN 2025 - 2026: Tích hợp AI Agent, Vitest & Framework Hiện Đại ===
  "sb"
  "storybook"
  "@storybook/codemod"
  "@storybook/cli"
  "@storybook/addon-links"
  "@storybook/react"
  "@storybook/react-native"
  "@storybook/angular"
  "@storybook/addon-a11y"
  "@storybook/html"
  "@storybook/svelte"
  "@storybook/ember"
  "@storybook/addon-ondevice-backgrounds"
  "@storybook/addon-ondevice-notes"
  "@storybook/preact"
  "@storybook/addon-docs"
  "@storybook/addon-ondevice-actions"
  "@storybook/preset-create-react-app"
  "@storybook/web-components"
  "storybook-addon-pseudo-states"
  "@storybook/vue3"
  "@storybook/builder-webpack5"
  "@storybook/addon-svelte-csf"
  "@storybook/addon-ondevice-controls"
  "@storybook/test-runner"
  "@storybook/builder-vite"
  "@storybook/core-webpack"
  "@storybook/preset-react-webpack"
  "@storybook/react-webpack5"
  "@storybook/server-webpack5"
  "@storybook/react-vite"
  "@storybook/vue3-vite"
  "@storybook/svelte-vite"
  "@storybook/web-components-vite"
  "@storybook/nextjs"
  "@storybook/csf-plugin"
  "@storybook/html-vite"
  "@storybook/sveltekit"
  "@storybook/preact-vite"
  "@storybook/react-dom-shim"
  "create-storybook"
  "@storybook/addon-onboarding"
  "@storybook/react-native-theming"
  "@storybook/icons"
  "@storybook/addon-themes"
  "@storybook/react-native-web-vite"
  "@storybook/addon-vitest"
  "@storybook/react-native-ui-lite"
  "@storybook/react-native-ui-common"
  "@storybook/addon-mcp"
  "@storybook/mcp"
  "@storybook/tanstack-react"
  "@storybook/angular-vite"
  "@storybook/react-native-ui"

  # === GIAI ĐOẠN 2023 - 2024: Kiến trúc Manager/Preview, Test & Doc Blocks ===
  "@storybook/channels"
  "@storybook/addon-actions"
  "@storybook/components"
  "@storybook/addon-backgrounds"
  "@storybook/addon-viewport"
  "@storybook/client-logger"
  "@storybook/node-logger"
  "@storybook/core"
  "@storybook/addon-storysource"
  "@storybook/core-events"
  "@storybook/core-common"
  "@storybook/core-server"
  "@storybook/csf-tools"
  "@storybook/addon-measure"
  "@storybook/addon-outline"
  "@storybook/instrumenter"
  "@storybook/addon-interactions"
  "@storybook/docs-tools"
  "@storybook/telemetry"
  "@storybook/preset-html-webpack"
  "@storybook/preset-preact-webpack"
  "@storybook/preset-svelte-webpack"
  "@storybook/preset-vue3-webpack"
  "@storybook/html-webpack5"
  "@storybook/preact-webpack5"
  "@storybook/svelte-webpack5"
  "@storybook/vue3-webpack5"
  "@storybook/web-components-webpack5"
  "@storybook/addon-highlight"
  "@storybook/blocks"
  "@storybook/builder-manager"
  "@storybook/types"
  "@storybook/manager"
  "@storybook/preview"
  "@storybook/manager-api"
  "@storybook/preview-api"
  "@storybook/addon-mdx-gfm"
  "@storybook/test"
  "@storybook/experimental-nextjs-vite"
  "@storybook/experimental-addon-test"
  "@storybook/addon-test"
  "@storybook/experimental-nextjs-rsc"
  "@storybook/nextjs-vite-rsc"
  "@storybook/addon-story-inspector"

  # === GIAI ĐOẠN 2021 - 2022: Webpack 5, Testing Utilities & Addon Essentials ===
  "react-inspector"
  "@storybook/addon-knobs"
  "@storybook/addon-jest"
  "@storybook/addon-toolbars"
  "@storybook/addon-controls"
  "@storybook/design-system"
  "@storybook/source-loader"
  "@storybook/addon-queryparams"
  "@storybook/postinstall"
  "@storybook/addon-essentials"
  "@storybook/csf"
  "@storybook/native"
  "@storybook/native-addon"
  "@storybook/native-types"
  "@storybook/native-devices"
  "@storybook/native-components"
  "@storybook/deep-link-logger"
  "@storybook/native-controllers"
  "@storybook/native-dev-middleware"
  "@storybook/expect"
  "@storybook/store"
  "@storybook/preview-web"
  "@storybook/testing-angular"
  "@storybook/auto-config"
  "@storybook/addon-styling-webpack"

  # === GIAI ĐOẠN CŨ HƠN (Trước 2021): Thử nghiệm, Renderer phụ & Tiện ích nguyên thủy ===
  "@storybook/addon-google-analytics"
  "@storybook/addon-cssresources"
  "@storybook/addon-devkit"
  "@storybook/theming"
  "@storybook/router"
  "@storybook/react-native-server"
  "@storybook/api"
  "@storybook/preset-typescript"
  "@storybook/preset-scss"
  "@storybook/addon-design-assets"
  "@storybook/addon-parameter"
  "@storybook/addon-roundtrip"
  "@storybook/rax"
  "@storybook/addon-decorator"
  "@storybook/ember-cli-storybook"
  "@storybook/linter-config"
  "@storybook/eslint-config-storybook"
  "@storybook/preset-ant-design"
  "@storybook/addon-preview-wrapper"
  "@storybook/marionette"
  "@storybook/melody"
  "@storybook/server"
  "@storybook/aem"
  "@storybook/aem-cli"
  "@storybook/aurelia"
  "@storybook/semver"
  "@storybook/addon-bench"
  "@storybook/bench"
  "eslint-plugin-storybook"
  "@storybook/appetize-urls"
  "@storybook/appetize-utils"
  "@storybook/preset-ie11"
  "@storybook/addon-postcss"
  "@storybook/react-testing"
  "@storybook/testing-react"
  "@storybook/addon-ie11"
  "@storybook/react-docgen-typescript-plugin"
  "@storybook/testing-vue"
  "@storybook/manager-webpack4"
  "@storybook/manager-webpack5"
  "@storybook/testing-vue3"
  "@storybook/jest"
  "@storybook/testing-library"
  "@storybook/addon-react-native-web"
  "@storybook/babel-plugin-require-context-hook"
  "@storybook/mdx2-csf-loader"
  "@storybook/mdx1-csf-loader"
  "@storybook/mdx1-csf"
  "@storybook/mdx2-csf"
  "@storybook/docs-mdx"
  "@storybook/preset-web-components-webpack"
  "@storybook/preset-vue-webpack"
  "@storybook/vue-webpack5"
  "@storybook/preset-server-webpack"
  "@storybook/addon-coverage"
  "@storybook/components-marketing"
  "create-webpack5-react"
  "@storybook/vue-vite"
  "@storybook/global"
  "@storybook/addon-styling"
  "@storybook/react-docgen-typescript"
  "@storybook/addon-designs"
  "@storybook/playwright-ct"
  "@storybook/marko-webpack"
  "@storybook/marko-vite"
  "@storybook/nextjs-server"
  "@storybook/addon-webpack5-compiler-babel"
  "@storybook/addon-webpack5-compiler-swc"
  "@storybook/addon-react-native-server"
  "@storybook/addon-module-mock-fork"
  "@storybook/experimental-vitest-plugin"
  "@storybook/toolbox"
  "@storybook/nextjs-vite"
  "@storybook/experimental-addon-vitest"
  "@storybook/experimental-nextjs-vite"
  "@storybook/experimental-addon-coverage"
  "@storybook/install-footprint"
  "@storybook/addon-before-after"
  "@storybook/addon-review-changes"
  "@storybook/addon-review"
)

echo "Tổng số packages trong danh sách: ${#packages[@]}"
echo "--------------------------------------------------"

# Vòng lặp duyệt qua từng package và thực hiện git clone từ GitHub chính thức của Storybook
for pkg in "${packages[@]}"; do
  # Chuyển đổi tên scope package thành tên thư mục phù hợp
  dir_name=$(echo "$pkg" | sed 's|@[^/]*/||' | tr '/' '-')
  repo_url="https://github.com/storybookjs/${dir_name}.git"

  # Xử lý ngoại lệ — tên repo GitHub khác với tên package npm
  case "$pkg" in
    "sb"|"storybook"|"@storybook/"*)
      repo_url="https://github.com/storybookjs/storybook.git"
      dir_name="storybook-monorepo"
      ;;
    "react-inspector")
      repo_url="https://github.com/storybookjs/react-inspector.git"
      ;;
    "eslint-plugin-storybook")
      repo_url="https://github.com/storybookjs/eslint-plugin-storybook.git"
      ;;
    "create-storybook"|"create-webpack5-react")
      repo_url="https://github.com/storybookjs/storybook.git"
      dir_name="storybook-monorepo"
      ;;
  esac

  echo "➡️  Đang xử lý: $pkg"
  echo "   Repo: $repo_url"

  if [ -d "$dir_name" ]; then
    echo "   ⚠️  Thư mục $dir_name đã tồn tại — bỏ qua"
  else
    git clone --depth 1 "$repo_url" "$dir_name" 2>/dev/null \
      && echo "   ✅ Clone thành công: $dir_name" \
      || echo "   ❌ Không thể clone $pkg (private / đã gộp / không tồn tại)"
  fi

  echo "--------------------------------------------------"
done

echo ""
echo "🎉 Hoàn tất quét và clone toàn bộ packages theo lộ trình ngược thời gian!"
echo "📁 Thư mục hiện tại chứa: $(ls -d */ 2>/dev/null | wc -l) repos đã clone"
```

---

## 7. So sánh: VNKR Labs vs Storybook Bot

| Tiêu chí | Storybook Bot | VNKR Labs |
|---|---|---|
| **Tổng packages** | 230+ | 4 (hiện tại), mục tiêu 20+ |
| **Scope** | `@storybook/` | `@vnkr-labs/` |
| **Registry** | npmjs.com | npmjs.com |
| **Monorepo** | ✅ (Turborepo) | 📋 Planned |
| **Foundation** | `@storybook/csf` + `@storybook/types` | `@vnkr-labs/tokens` |
| **Framework adapters** | React, Vue, Angular, Svelte... | Laravel Blade, React (planned) |
| **DX tooling** | `create-storybook`, `@storybook/cli` | `create-vnkr` (planned) |
| **Design system** | `@storybook/design-system` | `@vnkr-labs/tokens` + `@vnkr-labs/ui` |
| **Mobile** | `@storybook/react-native` | `@vnkr-labs/mobile` (planned) |
| **Icons** | `@storybook/icons` | `@vnkr-labs/icons` (planned) |

---

## 8. Tài liệu liên quan

| Tài liệu | Đường dẫn |
|---|---|
| Packages đã publish | [`packages/PACKAGES.md`](../packages/PACKAGES.md) |
| Design System Plan | [`ý-tưởng/Design-system/DESIGN_SYSTEM_PLAN.md`](../ý-tưởng/Design-system/DESIGN_SYSTEM_PLAN.md) |
| Kế hoạch UI Mobile | [`ý-tưởng/UI/KE-HOACH-XAY-DUNG-UI.md`](KE-HOACH-XAY-DUNG-UI.md) |
| Publish script | [`packages/publish.sh`](../packages/publish.sh) |
| npmjs org | [npmjs.com/org/vnkr-labs](https://www.npmjs.com/org/vnkr-labs) |
| GitHub (storybook ref) | [github.com/storybookjs/storybook](https://github.com/storybookjs/storybook) |

---

*Tài liệu lập bởi: VNKR Labs Engineering*  
*Tham khảo mô hình: [storybook-bot @ npmjs](https://www.npmjs.com/~storybook-bot)*  
*Ngày lập: 23/09/2026 | Phiên bản: 1.0*
