#!/usr/bin/env bash
# ──────────────────────────────────────────────────────────────────────────────
# scripts/cron/fim-scan.sh
# Giai đoạn 6: File Integrity Monitoring (FIM) — quét toàn vẹn mã nguồn mỗi đêm
#   0 2 * * * /path/to/scripts/cron/fim-scan.sh >> /var/log/axioledger/fim.log 2>&1
#
# Tạo SHA-256 checksums của toàn bộ source files
# So sánh với baseline đã ký — phát hiện bất kỳ thay đổi trái phép nào
# ──────────────────────────────────────────────────────────────────────────────
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
REPO_ROOT="${SCRIPT_DIR}/../.."
BASELINE_FILE="${REPO_ROOT}/infra/monitoring/fim-baseline.sha256"
REPORT_FILE="${REPO_ROOT}/logs/fim-report-$(date +%Y%m%d).txt"
TIMESTAMP=$(date -u +"%Y-%m-%dT%H:%M:%SZ")
ALERT_WEBHOOK="${ALERT_WEBHOOK_URL:-}"

mkdir -p "${REPO_ROOT}/logs"

echo "[${TIMESTAMP}] FIM Scan starting..."

# Generate current checksums for tracked source directories
CURRENT_SUMS=$(find "${REPO_ROOT}" \
  \( -path "*/node_modules" -o -path "*/target" -o -path "*/.git" -o -path "*/keys" \) \
  -prune -o -type f \
  \( -name "*.rs" -o -name "*.ts" -o -name "*.tsx" -o -name "*.toml" \
     -o -name "*.json" -o -name "*.yml" -o -name "*.yaml" \) \
  -print0 | sort -z | xargs -0 sha256sum 2>/dev/null)

# First run: create baseline
if [ ! -f "${BASELINE_FILE}" ]; then
  echo "${CURRENT_SUMS}" > "${BASELINE_FILE}"
  echo "[${TIMESTAMP}] ✅ FIM baseline created: ${BASELINE_FILE}"
  echo "[${TIMESTAMP}] FIM Scan complete — baseline established"
  exit 0
fi

# Compare with baseline
CHANGED_FILES=()
while IFS= read -r line; do
  HASH=$(echo "${line}" | awk '{print $1}')
  FILE=$(echo "${line}" | awk '{print $2}')

  BASELINE_HASH=$(grep " ${FILE}$" "${BASELINE_FILE}" | awk '{print $1}' || true)

  if [ -z "${BASELINE_HASH}" ]; then
    echo "[${TIMESTAMP}] ⚠️  NEW FILE: ${FILE}"
    CHANGED_FILES+=("NEW: ${FILE}")
  elif [ "${HASH}" != "${BASELINE_HASH}" ]; then
    echo "[${TIMESTAMP}] ❌ MODIFIED: ${FILE}"
    CHANGED_FILES+=("MODIFIED: ${FILE}")
  fi
done <<< "${CURRENT_SUMS}"

# Check for deleted files
while IFS= read -r line; do
  FILE=$(echo "${line}" | awk '{print $2}')
  if ! echo "${CURRENT_SUMS}" | grep -q " ${FILE}$"; then
    echo "[${TIMESTAMP}] ⚠️  DELETED: ${FILE}"
    CHANGED_FILES+=("DELETED: ${FILE}")
  fi
done < "${BASELINE_FILE}"

# Report
{
  echo "FIM Report — ${TIMESTAMP}"
  echo "Changes detected: ${#CHANGED_FILES[@]}"
  for f in "${CHANGED_FILES[@]}"; do
    echo "  ${f}"
  done
} > "${REPORT_FILE}"

if [ "${#CHANGED_FILES[@]}" -gt 0 ]; then
  echo "[${TIMESTAMP}] 🚨 FIM: ${#CHANGED_FILES[@]} change(s) detected — see ${REPORT_FILE}"
  if [ -n "${ALERT_WEBHOOK}" ]; then
    curl -sf -X POST "${ALERT_WEBHOOK}" \
      -H "Content-Type: application/json" \
      -d "{\"text\":\"🚨 AxioLedger FIM: ${#CHANGED_FILES[@]} unauthorized file change(s) detected at ${TIMESTAMP}\"}" || true
  fi
  exit 2
else
  echo "[${TIMESTAMP}] ✅ FIM: No unauthorized changes detected"
  exit 0
fi
