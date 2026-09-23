#!/usr/bin/env bash
# ──────────────────────────────────────────────────────────────────────────────
# scripts/cron/treasury-sweep.sh
# Giai đoạn 6: Treasury Sweep — gom phí gas L2 về Treasury mỗi giờ
#   0 * * * * /path/to/scripts/cron/treasury-sweep.sh >> /var/log/axioledger/treasury.log 2>&1
# ──────────────────────────────────────────────────────────────────────────────
set -euo pipefail

TIMESTAMP=$(date -u +"%Y-%m-%dT%H:%M:%SZ")
SQX_RPC="${SQX_RPC_URL:-http://127.0.0.1:8545}"
TREASURY_KEY="${TREASURY_KEY_PATH:-./keys/treasury-engine.json}"
TREASURY_ADDRESS="${TREASURY_ADDRESS:-}"
MIN_SWEEP_AXQ="${MIN_SWEEP_AXQ:-100}"   # Only sweep if > 100 AXQ accumulated
LOG_DIR="./logs"

mkdir -p "${LOG_DIR}"

echo "[${TIMESTAMP}] Treasury Sweep starting..."

if [ ! -f "${TREASURY_KEY}" ]; then
  echo "[${TIMESTAMP}] ❌ Treasury key not found at ${TREASURY_KEY}"
  exit 1
fi

if [ -z "${TREASURY_ADDRESS}" ]; then
  echo "[${TIMESTAMP}] ❌ TREASURY_ADDRESS env var not set"
  exit 1
fi

# Query accumulated gas fees from L2 fee collector
FEE_BALANCE=$(curl -sf --max-time 10 \
  -X POST "${SQX_RPC}" \
  -H "Content-Type: application/json" \
  -d "{\"jsonrpc\":\"2.0\",\"id\":1,\"method\":\"sqx_getFeeCollectorBalance\",\"params\":[]}" \
  2>/dev/null | python3 -c "import sys,json; d=json.load(sys.stdin); print(d.get('result',0))" 2>/dev/null || echo "0")

echo "[${TIMESTAMP}] Fee collector balance: ${FEE_BALANCE} AXQ"

if (( $(echo "${FEE_BALANCE} >= ${MIN_SWEEP_AXQ}" | bc -l 2>/dev/null || echo 0) )); then
  echo "[${TIMESTAMP}] Sweeping ${FEE_BALANCE} AXQ to treasury ${TREASURY_ADDRESS}..."
  # TODO: sign and submit sqx_sweepFeesToTreasury transaction
  # axioledger-cli treasury sweep \
  #   --key "${TREASURY_KEY}" \
  #   --recipient "${TREASURY_ADDRESS}" \
  #   --amount "${FEE_BALANCE}"
  echo "[${TIMESTAMP}] ✅ Sweep complete (stub — TODO: axioledger-cli integration)"
else
  echo "[${TIMESTAMP}] ℹ️  Balance ${FEE_BALANCE} AXQ below threshold ${MIN_SWEEP_AXQ} — skipping sweep"
fi

exit 0
