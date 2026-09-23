#!/bin/bash
# ============================================================
# VNKR Labs — Publish all packages to npmjs.com
# ============================================================
# Yêu cầu: NPM_TOKEN trong .env phải là loại "Publish" (không phải Automation)
# Tạo tại: npmjs.com → Account Settings → Access Tokens → Generate New Token → Publish
#
# Chạy: bash packages/publish.sh
# ============================================================

set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
ENV_FILE="$SCRIPT_DIR/../.env"
NPMRC_TMP="/tmp/.npmrc-vnkr-$$"

# 1. Lấy token
NPM_TOKEN=$(grep NPM_TOKEN "$ENV_FILE" | cut -d'"' -f2)
if [ -z "$NPM_TOKEN" ]; then
  echo "❌ Không tìm thấy NPM_TOKEN trong $ENV_FILE"
  exit 1
fi

# 2. Tạo .npmrc tạm
echo "//registry.npmjs.org/:_authToken=${NPM_TOKEN}" > "$NPMRC_TMP"
trap "rm -f $NPMRC_TMP" EXIT

# 3. Xác minh token
echo "🔑 Kiểm tra token..."
WHOAMI=$(npm whoami --userconfig "$NPMRC_TMP" --registry https://registry.npmjs.org/ 2>&1)
if echo "$WHOAMI" | grep -q "error"; then
  echo "❌ Token không hợp lệ hoặc đã hết hạn: $WHOAMI"
  echo ""
  echo "→ Tạo token mới tại: https://www.npmjs.com/settings/<username>/tokens"
  echo "→ Chọn loại: Publish (không phải Automation)"
  echo "→ Cập nhật NPM_TOKEN trong .env"
  exit 1
fi
echo "✅ Logged in as: $WHOAMI"

# 4. Publish từng package theo thứ tự (tokens trước — là peer dep của các gói khác)
PACKAGES=("vnkr-tokens" "vnkr-ui" "vnkr-fe" "vnkr-admin")

for pkg in "${PACKAGES[@]}"; do
  PKG_DIR="$SCRIPT_DIR/$pkg"
  PKG_NAME=$(node -p "require('$PKG_DIR/package.json').name")
  PKG_VER=$(node -p "require('$PKG_DIR/package.json').version")

  echo ""
  echo "📦 Publishing $PKG_NAME@$PKG_VER ..."
  cd "$PKG_DIR"
  npm publish --access public --userconfig "$NPMRC_TMP"
  echo "✅ $PKG_NAME@$PKG_VER published!"
done

echo ""
echo "🎉 Tất cả packages đã được publish thành công!"
echo ""
echo "Install:"
echo "  npm install @vnkr-io/tokens @vnkr-io/ui @vnkr-io/fe"
echo "  npm install @vnkr-io/tokens @vnkr-io/ui @vnkr-io/admin"
