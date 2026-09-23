# AxioLedger ($AXQ) — Monorepo

> **Kiến trúc:** 1 Hub + 4 Pillars | **Quy mô mục tiêu:** 500,000 LOC | **Giấy phép:** BSL 1.1 (Core) / MIT (SDK)

---

## Tổng quan Kiến trúc (Hub & Spokes)

```
axioledger-monorepo/ (500k LOC)
├── apps/                        # Giao diện người dùng
│   ├── axq-governance-ui        # DAO Voting UI
│   ├── axiopass-wallet          # Passkey-native wallet (FaceID/TouchID)
│   └── kpx-dex-frontend         # KinetoProtocol DEX
├── packages/                    # Thư viện dùng chung
│   ├── ans-resolver             # ANS Domain (.axq/.vpx/.sqx/.kpx/.vrq)
│   ├── zkp-crypto-lib           # ZK-Proof circuits & helpers
│   └── evm-interop              # EVM compatibility layer
├── core-nodes/                  # Node phần mềm (Rust/C++)
│   ├── valiprecision-node       # $VPX — Consensus Engine, P2P (~120k LOC)
│   └── sequentichain-node       # $SQX — L2 Execution, AF_XDP (~150k LOC)
└── smart-contracts/             # On-chain logic
    ├── axioledger-system        # $AXQ Core Hub — DAO, Treasury (~50k LOC)
    ├── ans-registry             # ANS Namespace Registry
    ├── kpx-liquidity            # AMM Router, RWA, Cross-chain Bridge (~80k LOC)
    └── governance-dao           # Quadratic Voting, Guardian Council, TimeLock
```

---

## 5 Trụ cột Hệ sinh thái

| Symbol | Tên | Vai trò | LOC |
|--------|-----|---------|-----|
| **$AXQ** | Core Hub | DAO Governance, Tokenomics, Ecosystem Treasury | ~50,000 |
| **$VPX** | Valiprecision | Consensus Engine, Network P2P (Rust/C++) | ~120,000 |
| **$SQX** | Sequentichain | L2 Execution, AF_XDP NIC Bypass, SVM Rollup | ~150,000 |
| **$KPX** | Kinetoprotocol | AMM Router, Cross-chain Bridge, RWA Logic | ~80,000 |
| **$VRQ** | Veraciphers & ANS | ZK-Circuits, DID Resolver, Axiopass Wallet, SDKs | ~100,000 |

---

## Mô hình Kinh tế (Tokenomics — 500 Tỷ $AXQ)

| Quỹ | Tỷ lệ | Lượng |
|-----|-------|-------|
| VPX Subsidy | 25% | 125B |
| R&D | 30% | 150B |
| RWA Treasury | 15% | 75B |
| Team | 12% | 60B |
| Strategic | 13% | 65B |
| TGE | 5% | 25B |

---

## Vòng lặp Giá trị

```
Người dùng mua Domain .axq
    → Trả phí thực thi .sqx
    → Xác thực qua .vpx
    → Thanh khoản chéo qua .kpx
    → Bảo mật danh tính qua .vrq
```

---

## Chỉ số KPI Mục tiêu

- **Thông lượng:** > 600,000 TPS
- **Độ trễ (Soft Confirmation):** < 100ms
- **Nakamoto Coefficient:** > 50
- **Chi phí Validator:** < 0.1 $AXQ/ngày

---

## Lộ trình Phát hành

| Milestone | Môi trường | Mục tiêu |
|-----------|-----------|---------|
| M1 | Localnet | `docker-compose`, `cargo test`, `cargo audit` |
| M2 | Devnet/Testnet | Faucet, 100K TPS stress-test, ZK-Proof pipeline |
| M3 | Mainnet | Genesis Block, chuyển giao Upgrade Authority → DAO |

---

## Quản trị Tam Quyền Phân Lập

- **Lập pháp:** Quadratic Voting — `phiếu = √(token)`
- **Tư pháp:** Guardian Council (5 ghế) — Veto 4/5 trong Objection Window
- **Hành pháp:** Time-Lock 7 ngày + Escape Hatch khẩn cấp

---

## Bắt đầu

```bash
# Cài đặt dependencies
npm install

# Build toàn bộ monorepo
npm run build

# Chạy tests
npm run test

# Kiểm tra bảo mật supply-chain
npm run audit:supply-chain
```

---

## Giấy phép

- **Core Node / Smart Contracts:** [Business Source License 1.1](./LICENSE)
- **SDKs / Packages:** MIT / Apache-2.0 (xem từng package)

> © AxioLedger Core Team. Mọi quyền được bảo lưu theo BSL 1.1.
