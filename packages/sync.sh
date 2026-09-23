#!/usr/bin/env bash
# ============================================================
# VNKR Labs — Sync source → packages
# ============================================================
# Dùng khi đã chỉnh sửa CSS/JS trong public/assets/
# Chạy: bash packages/sync.sh
# ============================================================

set -e

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
PKG_DIR="$ROOT/packages"
SRC_CSS="$ROOT/public/assets/css"
SRC_JS="$ROOT/public/assets/js"

echo "🔄 Syncing source files → packages..."
echo "   Root: $ROOT"
echo ""

cp "$SRC_CSS/vnkr-tokens.css" "$PKG_DIR/vnkr-tokens/index.css" && echo "   ✅ tokens"
cp "$SRC_CSS/vnkr-ui.css"     "$PKG_DIR/vnkr-ui/index.css"     && echo "   ✅ ui (css)"
cp "$SRC_JS/vnkr-ui.js"       "$PKG_DIR/vnkr-ui/index.js"      && echo "   ✅ ui (js)"
cp "$SRC_CSS/vnkr-fe.css"     "$PKG_DIR/vnkr-fe/index.css"     && echo "   ✅ fe"
cp "$SRC_CSS/vnkr-admin.css"  "$PKG_DIR/vnkr-admin/index.css"  && echo "   ✅ admin"

echo ""
echo "✅ Sync hoàn tất!"
echo ""
echo "Tiếp theo — bump version rồi publish:"
echo "  bash $PKG_DIR/publish.sh"
