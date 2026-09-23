#!/usr/bin/env bash
# ──────────────────────────────────────────────────────────────────────────────
# scripts/cron/node-health-check.sh
# Giai đoạn 6: Check Node Health — runs every 5 minutes via cron
#   */5 * * * * /path/to/scripts/cron/node-health-check.sh >> /var/log/axioledger/health.log 2>&1
# ──────────────────────────────────────────────────────────────────────────────
set -euo pipefail

LOG_DIR="./logs"
TIMESTAMP=$(date -u +"%Y-%m-%dT%H:%M:%SZ")
VPX_RPC="${VPX_RPC_URL:-http://127.0.0.1:8899}"
SQX_RPC="${SQX_RPC_URL:-http://127.0.0.1:8545}"
ALERT_WEBHOOK="${ALERT_WEBHOOK_URL:-}"

mkdir -p "${LOG_DIR}"

check_rpc() {
  local name="$1"
  local url="$2"
  local response
  if response=$(curl -sf --max-time 5 \
      -X POST "${url}" \
      -H "Content-Type: application/json" \
      -d '{"jsonrpc":"2.0","id":1,"method":"getHealth","params":[]}' 2>&1); then
    echo "[${TIMESTAMP}] ✅ ${name} — healthy | ${url}"
    return 0
  else
    echo "[${TIMESTAMP}] ❌ ${name} — UNREACHABLE | ${url}"
    return 1
  fi
}

HEALTHY=true

check_rpc "VPX (Valiprecision)" "${VPX_RPC}" || HEALTHY=false
check_rpc "SQX (Sequentichain)" "${SQX_RPC}" || HEALTHY=false

# Check disk space
DISK_USE=$(df / | awk 'NR==2 {print $5}' | tr -d '%')
if [ "${DISK_USE}" -gt 85 ]; then
  echo "[${TIMESTAMP}] ⚠️  DISK USAGE HIGH: ${DISK_USE}%"
  HEALTHY=false
else
  echo "[${TIMESTAMP}] ✅ DISK usage: ${DISK_USE}%"
fi

# Check memory
if command -v free &>/dev/null; then
  MEM_FREE_MB=$(free -m | awk 'NR==2 {print $4}')
  if [ "${MEM_FREE_MB}" -lt 512 ]; then
    echo "[${TIMESTAMP}] ⚠️  LOW MEMORY: ${MEM_FREE_MB}MB free"
    HEALTHY=false
  else
    echo "[${TIMESTAMP}] ✅ Memory free: ${MEM_FREE_MB}MB"
  fi
fi

# Alert if unhealthy
if [ "${HEALTHY}" = false ] && [ -n "${ALERT_WEBHOOK}" ]; then
  curl -sf -X POST "${ALERT_WEBHOOK}" \
    -H "Content-Type: application/json" \
    -d "{\"text\":\"🚨 AxioLedger Node Health Alert at ${TIMESTAMP}\"}" || true
fi

exit 0
