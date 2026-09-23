# Hướng dẫn Đóng góp — AxioLedger

Cảm ơn bạn đã quan tâm đến việc đóng góp cho AxioLedger! Tài liệu này mô tả quy trình để đóng góp mã nguồn.

---

## Yêu cầu Bắt buộc

### GPG Signing (Bắt buộc)

Mọi commit đều phải được ký bằng GPG:

```bash
# Cấu hình GPG signing toàn cục
git config --global commit.gpgsign true
git config --global user.signingkey <YOUR_GPG_KEY_ID>
```

### Môi trường Phát triển

- **Node.js** >= 20.0.0
- **Rust** >= 1.78 (stable toolchain)
- **Cargo** (đi kèm với Rust)
- **npm** >= 10.0.0

```bash
# Clone và cài đặt
git clone https://github.com/axioledger/axioledger-monorepo.git
cd axioledger-monorepo
npm install
```

---

## Quy trình Đóng góp

### 1. Fork & Branch

```bash
git checkout -b feat/<pillar>/<short-description>
# Ví dụ: feat/vpx/consensus-byzantine-fault
#         fix/sqx/af-xdp-memory-leak
#         docs/ans/resolver-api-update
```

**Quy ước đặt tên nhánh:**

| Tiền tố | Dùng khi |
|---------|----------|
| `feat/` | Tính năng mới |
| `fix/` | Sửa lỗi |
| `docs/` | Cập nhật tài liệu |
| `refactor/` | Tái cấu trúc code |
| `test/` | Thêm/sửa tests |
| `chore/` | Cập nhật build, CI/CD |
| `security/` | Vá lỗ hổng bảo mật |

**Pillar scopes:** `axq` | `vpx` | `sqx` | `kpx` | `vrq` | `ans` | `infra`

### 2. Viết Code

- Tuân thủ Clean-Room Engineering — không sao chép mã từ các dự án khác
- Mọi function phải có unit test tương ứng
- Rust code phải pass `cargo clippy` và `cargo fmt`
- TypeScript code phải pass `eslint` và `prettier`

### 3. Chạy Tests

```bash
# TypeScript/Node packages
npm run test

# Rust core-nodes
cd core-nodes/valiprecision-node && cargo test
cd core-nodes/sequentichain-node && cargo test

# Smart contracts
cd smart-contracts/axioledger-system && cargo test
```

### 4. Security Audit

```bash
# NPM supply chain audit
npm run audit:supply-chain

# Rust dependency audit
cargo audit
```

### 5. Commit

```bash
# Sử dụng Conventional Commits
git commit -S -m "feat(sqx): implement AF_XDP bypass for NIC I/O"
```

**Cấu trúc commit message:**
```
<type>(<scope>): <short description>

[optional body]

[optional footer(s)]
```

### 6. Pull Request

- Mô tả đầy đủ thay đổi trong PR description
- Liên kết Issue tương ứng (nếu có)
- Đảm bảo tất cả CI checks pass
- Yêu cầu review từ ít nhất 2 thành viên Core Team

---

## Tiêu chí Review

- [ ] Code đúng logic và không có lỗ hổng bảo mật
- [ ] Tests đầy đủ (coverage >= 80%)
- [ ] Tài liệu được cập nhật
- [ ] Không vi phạm BSL 1.1 hoặc quyền sở hữu trí tuệ
- [ ] GPG signature hợp lệ trên tất cả commits
- [ ] `npm audit` và `cargo audit` clean

---

## Báo cáo Lỗ hổng Bảo mật

**KHÔNG** tạo public Issue cho lỗ hổng bảo mật. Xem [`SECURITY.md`](./SECURITY.md).

---

*Bằng cách đóng góp, bạn đồng ý rằng đóng góp của bạn sẽ được cấp phép theo BSL 1.1 cho core và MIT cho SDK packages.*
