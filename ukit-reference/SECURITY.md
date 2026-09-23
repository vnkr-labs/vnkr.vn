# Chính sách Bảo mật — AxioLedger

## Phiên bản được Hỗ trợ

| Phiên bản | Hỗ trợ bảo mật |
|-----------|----------------|
| `main` (latest) | ✅ Đầy đủ |
| Testnet release | ✅ Giới hạn |
| Devnet / Localnet | ⚠️ Best-effort |

---

## Báo cáo Lỗ hổng

**KHÔNG** tạo public GitHub Issue cho lỗ hổng bảo mật.

### Kênh Báo cáo Ưu tiên

1. **Email bảo mật:** `security@axioledger.network`
2. **PGP Key:** Có thể tải từ `https://axioledger.network/.well-known/security.pgp`
3. **Security.txt:** `https://axioledger.network/.well-known/security.txt`

### Thông tin cần Cung cấp

```
Tiêu đề: [SECURITY] <Mô tả ngắn gọn>
Nội dung:
- Mô tả lỗ hổng
- Các bước tái tạo (PoC nếu có)
- Phiên bản / component bị ảnh hưởng
- Tác động ước tính (CVSS nếu biết)
- Giải pháp đề xuất (nếu có)
```

---

## Cam kết Phản hồi

| Giai đoạn | Thời hạn |
|-----------|---------|
| Xác nhận đã nhận báo cáo | 48 giờ |
| Đánh giá mức độ nghiêm trọng | 7 ngày |
| Phát hành bản vá (critical) | 14 ngày |
| Phát hành bản vá (high) | 30 ngày |
| Công bố CVE (sau khi vá) | 90 ngày |

---

## Phạm vi Bảo mật

### Trong phạm vi (In-Scope)

- **Smart Contracts:** `smart-contracts/axioledger-system`, `smart-contracts/governance-dao`, `smart-contracts/kpx-liquidity`
- **Core Nodes:** `core-nodes/valiprecision-node`, `core-nodes/sequentichain-node`
- **Cryptography:** `packages/zkp-crypto-lib`, ZK-Proof circuits
- **Wallet / Authentication:** `apps/axiopass-wallet`, Passkey integration, Seed phrase handling
- **Bridge:** Cross-chain bridge logic trong `smart-contracts/kpx-liquidity`
- **PKI / TLS:** Hạ tầng chứng chỉ nội bộ

### Ngoài phạm vi (Out-of-Scope)

- Tấn công cần quyền truy cập vật lý vào server
- Social engineering / phishing
- Lỗi trong third-party dependencies (báo cáo trực tiếp cho vendor)
- Frontend UI bugs không có tác động bảo mật

---

## Chính sách Tiết lộ Có trách nhiệm

AxioLedger tuân theo chính sách **Coordinated Vulnerability Disclosure (CVD)**:

1. Người báo cáo gửi thông tin riêng tư cho nhóm bảo mật
2. Nhóm xác nhận và đánh giá trong vòng 7 ngày
3. Phát triển và kiểm tra bản vá
4. Phối hợp với người báo cáo về ngày công bố
5. Phát hành bản vá + công bố CVE

Người báo cáo hợp lệ sẽ được ghi nhận trong `SECURITY-HALL-OF-FAME.md` (nếu đồng ý).

---

## Kiến trúc Bảo mật

- **Zero-Knowledge Proofs:** Bảo vệ dữ liệu người dùng 100% qua ZK-Proofs
- **PKI Nội bộ:** Root CA 4096-bit + 5 Intermediate CA cho từng trụ cột
- **Supply Chain:** GPG-signed commits, `npm audit`, `cargo audit` trong CI/CD
- **File Integrity Monitoring (FIM):** Quét toàn vẹn mã nguồn hàng đêm
- **RBAC/IAM:** Phân quyền 3 tầng (SysAdmin / Node Operator / Treasury Engine)

---

*Cập nhật lần cuối: 2024 | Nhóm Bảo mật AxioLedger*
