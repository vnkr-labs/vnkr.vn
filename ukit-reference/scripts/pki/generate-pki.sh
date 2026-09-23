#!/usr/bin/env bash
# ──────────────────────────────────────────────────────────────────────────────
# scripts/pki/generate-pki.sh
# Giai đoạn 4: PKI Nội bộ — AxioLedger Internal Certificate Authority
#
# Tạo:
#   1. Root CA (4096-bit RSA)  → keys/root/axioledger-root-ca.{key,crt}
#   2. 5 Intermediate CAs (mỗi trụ cột 1 CA)
#   3. TLS leaf certificates cho các ANS domains
#   4. identity-declaration.json (xuất thông tin CA)
#
# Yêu cầu: openssl >= 3.0
# Sử dụng: bash scripts/pki/generate-pki.sh
# ──────────────────────────────────────────────────────────────────────────────
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PKI_DIR="${SCRIPT_DIR}/../../keys"
DAYS_ROOT=3650     # 10 years
DAYS_INTER=1825    # 5 years
DAYS_LEAF=365      # 1 year

# Pillar CA definitions: name → domain
declare -A PILLARS=(
  ["vpx"]="valiprecision.axioledger.network"
  ["sqx"]="sequentichain.axioledger.network"
  ["kpx"]="kinetoprotocol.axioledger.network"
  ["vrq"]="veraciphers.axioledger.network"
  ["axq"]="governance.axioledger.network"
)

mkdir -p "${PKI_DIR}/root" "${PKI_DIR}/intermediate" "${PKI_DIR}/leaf"
chmod 700 "${PKI_DIR}"

echo "=== [1/4] Generating Root CA (4096-bit RSA) ==="
openssl genrsa -out "${PKI_DIR}/root/axioledger-root-ca.key" 4096
chmod 600 "${PKI_DIR}/root/axioledger-root-ca.key"

openssl req -new -x509 \
  -key "${PKI_DIR}/root/axioledger-root-ca.key" \
  -out "${PKI_DIR}/root/axioledger-root-ca.crt" \
  -days "${DAYS_ROOT}" \
  -subj "/C=SG/O=AxioLedger Foundation/CN=AxioLedger Root CA/emailAddress=pki@axioledger.network" \
  -extensions v3_ca

echo "✅ Root CA generated: ${PKI_DIR}/root/axioledger-root-ca.crt"

echo ""
echo "=== [2/4] Generating Intermediate CAs (5 pillars) ==="
for PILLAR in "${!PILLARS[@]}"; do
  DOMAIN="${PILLARS[$PILLAR]}"
  INTER_DIR="${PKI_DIR}/intermediate/${PILLAR}"
  mkdir -p "${INTER_DIR}"

  openssl genrsa -out "${INTER_DIR}/${PILLAR}-ca.key" 3072
  chmod 600 "${INTER_DIR}/${PILLAR}-ca.key"

  openssl req -new \
    -key "${INTER_DIR}/${PILLAR}-ca.key" \
    -out "${INTER_DIR}/${PILLAR}-ca.csr" \
    -subj "/C=SG/O=AxioLedger Foundation/OU=${PILLAR^^}/CN=${PILLAR^^} Intermediate CA"

  openssl x509 -req \
    -in "${INTER_DIR}/${PILLAR}-ca.csr" \
    -CA "${PKI_DIR}/root/axioledger-root-ca.crt" \
    -CAkey "${PKI_DIR}/root/axioledger-root-ca.key" \
    -CAcreateserial \
    -out "${INTER_DIR}/${PILLAR}-ca.crt" \
    -days "${DAYS_INTER}" \
    -extensions v3_ca

  echo "  ✅ Intermediate CA [${PILLAR^^}] → ${DOMAIN}"
done

echo ""
echo "=== [3/4] Generating TLS leaf certificates (ANS domains) ==="
for PILLAR in "${!PILLARS[@]}"; do
  DOMAIN="${PILLARS[$PILLAR]}"
  INTER_DIR="${PKI_DIR}/intermediate/${PILLAR}"
  LEAF_DIR="${PKI_DIR}/leaf/${PILLAR}"
  mkdir -p "${LEAF_DIR}"

  openssl genrsa -out "${LEAF_DIR}/${DOMAIN}.key" 2048
  chmod 600 "${LEAF_DIR}/${DOMAIN}.key"

  openssl req -new \
    -key "${LEAF_DIR}/${DOMAIN}.key" \
    -out "${LEAF_DIR}/${DOMAIN}.csr" \
    -subj "/C=SG/O=AxioLedger Foundation/CN=${DOMAIN}"

  openssl x509 -req \
    -in "${LEAF_DIR}/${DOMAIN}.csr" \
    -CA "${INTER_DIR}/${PILLAR}-ca.crt" \
    -CAkey "${INTER_DIR}/${PILLAR}-ca.key" \
    -CAcreateserial \
    -out "${LEAF_DIR}/${DOMAIN}.crt" \
    -days "${DAYS_LEAF}" \
    -extfile <(cat <<EOF
subjectAltName = DNS:${DOMAIN}, DNS:*.${DOMAIN}
keyUsage = digitalSignature, keyEncipherment
extendedKeyUsage = serverAuth
EOF
)

  echo "  ✅ TLS cert [${DOMAIN}]"
done

echo ""
echo "=== [4/4] Exporting identity-declaration.json ==="
ROOT_FINGERPRINT=$(openssl x509 -in "${PKI_DIR}/root/axioledger-root-ca.crt" -fingerprint -sha256 -noout | cut -d= -f2)
GENERATED_AT=$(date -u +"%Y-%m-%dT%H:%M:%SZ")

cat > "${PKI_DIR}/identity-declaration.json" <<EOF
{
  "project": "AxioLedger",
  "version": "0.1.0",
  "generated_at": "${GENERATED_AT}",
  "root_ca": {
    "subject": "AxioLedger Root CA",
    "fingerprint_sha256": "${ROOT_FINGERPRINT}",
    "validity_days": ${DAYS_ROOT},
    "key_size_bits": 4096
  },
  "intermediate_cas": [
    { "pillar": "AXQ", "domain": "governance.axioledger.network" },
    { "pillar": "VPX", "domain": "valiprecision.axioledger.network" },
    { "pillar": "SQX", "domain": "sequentichain.axioledger.network" },
    { "pillar": "KPX", "domain": "kinetoprotocol.axioledger.network" },
    { "pillar": "VRQ", "domain": "veraciphers.axioledger.network" }
  ],
  "note": "Install root_ca.crt into OS/browser trust store to trust all ANS domains"
}
EOF

echo "✅ identity-declaration.json written to ${PKI_DIR}/identity-declaration.json"
echo ""
echo "══════════════════════════════════════════════════════"
echo " PKI generation complete."
echo " Root CA : ${PKI_DIR}/root/axioledger-root-ca.crt"
echo " Fingerprint: ${ROOT_FINGERPRINT}"
echo " NEXT: Run install-root-ca.sh to add to OS trust store"
echo "══════════════════════════════════════════════════════"
