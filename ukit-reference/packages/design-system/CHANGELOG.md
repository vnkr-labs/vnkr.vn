# Changelog — @axioledger/axio-design-system

All notable changes to this package are documented here.
Format: [Keep a Changelog](https://keepachangelog.com/en/1.0.0/)
Versioning: [Semantic Versioning](https://semver.org/spec/v2.0.0.html)

---

## [2.0.0] — 2025-07-15

### ✨ Added — Phase 2: Advanced Components

#### Form Components
- **OTPInput** — 4/6-digit OTP field with auto-focus-next, paste support, numeric-only guard, masked mode, and per-digit accessible labels (`Digit N of N`)
- **Checkbox** — 5 states (unchecked / checked / indeterminate / hover / disabled) with `aria-checked="mixed"` for indeterminate
- **RadioButton** — companion to Checkbox with `role="radio"` and group pattern
- **SearchBar** — pill shape, 3 sizes, clear (×) button, magnifying glass icon, keyboard accessible
- **Dropdown** — single + multi-select, searchable, keyboard close (Escape), `aria-expanded`, `role="listbox"`

#### Feedback Components
- **Alert** — 4 semantic variants (info / success / warning / error), close button, custom icon override
- **SecurityAlert** — TLP-aware critical security component; `level="blocked"` unconditionally disables action button
- **ProgressBar** — linear + indeterminate, accessible `role="progressbar"` + `aria-valuenow/min/max`
- **StepIndicator** — numbered step flow with active / complete / pending visual states
- **EmptyState** — 7 variants (no-data, no-results, no-connection, no-wallet, loading, error, success)

#### Crypto / AxioLedger-specific
- **NamespaceBadge** — `.axq`, `.kpx`, `.vrq`, `.sqx`, `.vpx` TLP-coloured namespace labels
- **AddressDisplay** — shows resolved ANS name (TLP-aware) or raw hex with mandatory ⚠️ when ANS resolver is configured
- **PasskeyButton** — FIDO2/WebAuthn passkey auth flow with `register` / `authenticate` / `manage` modes
- **CryptoAssetCard** — token balance card with coin icon, price, 24h change
- **TransactionItem** — send/receive/swap/bridge rows with status chips (pending/confirmed/failed)
- **BalanceDisplay** — portfolio total with fiat/crypto toggle
- **PriceTicker** — live price with directional colouring (green/red)

#### Developer Tooling
- **`.storybook/main.ts`** — Storybook 8 + `@storybook/react-vite` + `addon-a11y` + `addon-themes`
- **`.storybook/preview.ts`** — dark/light theme toggle, WCAG 2.1 AA axe-core rules, `data-theme` attribute
- **Stories** — Button, Input, Card, Alert, SecurityAlert with Playground + all state variants
- **`scripts/build-tokens.js`** — parses `tokens/variables.css` → emits `dist/tokens.json`, `dist/tokens.js`, `dist/tokens.d.ts`
- **`.github/workflows/design-system.yml`** — dedicated CI: typecheck → test (coverage 70%) → build → Storybook → publish on tag

---

## [1.0.0] — 2025-07-01

### ✨ Added — Phase 0 & 1: Foundation + Core Components

#### Token Architecture
- **`tokens/variables.css`** — full 3-layer CSS Custom Properties cascade:
  - Layer 1 — Primitive: greyscale, brand (teal/green/orange/pink/purple/yellow/grey), status (info/success/warning/error 100–900), spacing, radius, font-size
  - Layer 2 — Semantic (Light + Dark): `--color-text-*`, `--color-background-*`, `--color-surface-*`, `--color-border-*`, `--color-icon-*`, `--color-status-*`, `--color-accent-*`, radius aliases, spacing aliases, typography aliases
  - Layer 3 — TLP tokens: `--tlp-safe*`, `--tlp-caution*`, `--tlp-blocked*`, `--tlp-system*`
- **`tokens/fonts.css`** — Work Sans font-face declarations
- **`src/types/tlp.ts`** — `TLPLevel`, `TLP_CONFIG`, `resolveTLPLevel()`, `getTLPConfig()`

#### Providers & Hooks
- **`AxioProvider`** — root context provider for theme + ANS
- **`ThemeContext`** — `useTheme()` with `setTheme()`, `toggleTheme()`
- **`ANSContext`** — ANS resolver config injection
- **`useANSResolver`** — async name resolution hook
- **`useToast`** — imperative toast notification hook (5 variants, action button, 4 positions)

#### Core Components (Phase 1)
- **Button** — filled / outlined / ghost × 8 colours × 4 sizes; WCAG contrast-safe light-bg colours use dark text
- **Input** — 3 sizes, all states; label, helper text, error text; icon slots; multiline; ANS resolve mode
- **Card** — `Card` / `CardHeader` / `CardBody` / `CardFooter`; renders as `<button>` when `onClick` is provided
- **Badge** + **Chip** — 4 variants + dot mode; Chip with active/removable states
- **Toggle** — `role="switch"`, 2 sizes, on/off/disabled
- **Avatar** + **AvatarGroup** — image + initials fallback, 5 sizes, online indicator, overflow counter
- **Tooltip** — 4 placements, hover + focus trigger, configurable delay
- **Skeleton** + **SkeletonCard** — text / circle / rect variants, `aria-busy`, `SkeletonCard` preset
- **Navbar** — bottom nav, badge counts, ArrowKey keyboard navigation
- **Modal** + **BottomSheet** — WCAG focus trap, Escape close, focus restore
- **Toast** + **ToastContainer** — 5 variants, action button, 4 screen positions

#### Tests
- `src/types/tlp.test.ts` — 15 cases for `resolveTLPLevel` + `getTLPConfig`
- `src/components/Alert/SecurityAlert.test.tsx` — 9 cases including security enforcement
- `src/components/OTPInput/OTPInput.test.tsx` — 6 cases
- `src/components/Button/Button.test.tsx` — 7 cases

#### Build
- `package.json` — `"exports"` map (ES + CJS + types), `peerDeps`, scripts
- `vite.lib.config.ts` — ES + CJS build, `vite-plugin-dts`, `cssCodeSplit: false`
- `jest.config.js` — jsdom, `@testing-library/jest-dom`, CSS mock, 70% coverage threshold
- `tsconfig.json` + `tsconfig.lib.json` — strict TypeScript 5

---

## Notes

### Security
- `SecurityAlert` `level="blocked"` rule is intentional and non-negotiable. The disabled state on the action button cannot be removed via props, CSS, or JS. Any PR that attempts to modify this behaviour will be rejected.
- `AddressDisplay` raw hex display without ⚠️ when ANS is configured is a hard bug, not a feature request.

### Token naming
- CSS Custom Properties use `--color-*` (not `--axq-*` which is docs-only).
- Figma Variable collections use `AXQ /` prefix to distinguish from third-party libraries.

### Dark mode
- Only the Semantic layer (`[data-theme="dark"]`) overrides are needed. Component tokens auto-adapt because they reference Semantic tokens.
