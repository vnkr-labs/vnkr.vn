#!/usr/bin/env bash
# ============================================================
# Clone toàn bộ 230 packages của storybook-bot theo lộ trình
# ngược thời gian ra đời (2026 → 2025 → 2023 → 2021 → trước 2021)
#
# Chạy:
#   chmod +x clone-storybook-bot.sh
#   ./clone-storybook-bot.sh
# ============================================================

set -e

DEST_DIR="${1:-./storybook-ecosystem}"
mkdir -p "$DEST_DIR"
cd "$DEST_DIR"

echo "🚀 Bắt đầu clone 230 packages của storybook-bot theo lộ trình ngược thời gian..."
echo "📁 Thư mục đích: $(pwd)"
echo ""

packages=(
  # === GIAI ĐOẠN 2025 - 2026: AI Agent, Vitest & Framework Hiện Đại ===
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

  # === GIAI ĐOẠN 2023 - 2024: Manager/Preview, Test & Doc Blocks ===
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

  # === GIAI ĐOẠN 2021 - 2022: Webpack 5, Testing & Addon Essentials ===
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

  # === TRƯỚC 2021: Renderer phụ & Tiện ích nguyên thủy ===
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

TOTAL=${#packages[@]}
COUNT=0
SUCCESS=0
SKIP=0
FAIL=0

echo "Tổng số packages: $TOTAL"
echo "=================================================="

for pkg in "${packages[@]}"; do
  COUNT=$((COUNT + 1))

  # Xác định dir_name và repo_url
  pkg_name="${pkg#@storybook/}"
  pkg_name="${pkg_name#@}"
  dir_name=$(echo "$pkg_name" | tr '/' '-')

  # Tất cả @storybook/* đều nằm trong monorepo chính
  case "$pkg" in
    "sb"|"storybook"|"@storybook/"*|"create-storybook"|"create-webpack5-react"|"storybook-addon-pseudo-states")
      repo_url="https://github.com/storybookjs/storybook.git"
      dir_name="storybook-monorepo"
      ;;
    "react-inspector")
      repo_url="https://github.com/storybookjs/react-inspector.git"
      dir_name="react-inspector"
      ;;
    "eslint-plugin-storybook")
      repo_url="https://github.com/storybookjs/eslint-plugin-storybook.git"
      dir_name="eslint-plugin-storybook"
      ;;
    *)
      repo_url="https://github.com/storybookjs/${dir_name}.git"
      ;;
  esac

  printf "[%3d/%d] %-55s " "$COUNT" "$TOTAL" "$pkg"

  if [ -d "$dir_name" ]; then
    echo "⏭️  skip (exists)"
    SKIP=$((SKIP + 1))
  else
    git clone --depth 1 --quiet "$repo_url" "$dir_name" 2>/dev/null \
      && { echo "✅ cloned"; SUCCESS=$((SUCCESS + 1)); } \
      || { echo "❌ failed"; FAIL=$((FAIL + 1)); }
  fi
done

echo ""
echo "=================================================="
echo "🎉 Hoàn tất!"
echo "   ✅ Cloned : $SUCCESS"
echo "   ⏭️  Skipped: $SKIP"
echo "   ❌ Failed : $FAIL"
echo "   📁 Repos  : $(ls -d */ 2>/dev/null | wc -l) thư mục"
echo "   📍 Đường dẫn: $(pwd)"
