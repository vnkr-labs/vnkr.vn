#!/usr/bin/env bash
# ──────────────────────────────────────────────────────────────────────────────
# scripts/pki/install-root-ca.sh
# Cài đặt Root CA vào OS Trust Store (Linux / macOS)
# Chạy sau generate-pki.sh
# ──────────────────────────────────────────────────────────────────────────────
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
ROOT_CA="${SCRIPT_DIR}/../../keys/root/axioledger-root-ca.crt"

if [ ! -f "${ROOT_CA}" ]; then
  echo "ERROR: Root CA not found at ${ROOT_CA}"
  echo "Run scripts/pki/generate-pki.sh first."
  exit 1
fi

OS="$(uname -s)"

case "${OS}" in
  Linux)
    if command -v update-ca-certificates &>/dev/null; then
      # Debian / Ubuntu
      sudo cp "${ROOT_CA}" /usr/local/share/ca-certificates/axioledger-root-ca.crt
      sudo update-ca-certificates
      echo "✅ Root CA installed (Debian/Ubuntu trust store)"
    elif command -v update-ca-trust &>/dev/null; then
      # RHEL / Fedora / CentOS
      sudo cp "${ROOT_CA}" /etc/pki/ca-trust/source/anchors/axioledger-root-ca.crt
      sudo update-ca-trust extract
      echo "✅ Root CA installed (RHEL/Fedora trust store)"
    else
      echo "⚠️  Unknown Linux distro — manually add to your trust store:"
      echo "   ${ROOT_CA}"
    fi
    ;;
  Darwin)
    sudo security add-trusted-cert -d -r trustRoot \
      -k /Library/Keychains/System.keychain "${ROOT_CA}"
    echo "✅ Root CA installed (macOS System Keychain)"
    ;;
  *)
    echo "⚠️  Unsupported OS: ${OS}"
    echo "Manually install: ${ROOT_CA}"
    ;;
esac
