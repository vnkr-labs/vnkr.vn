# @axioledger/axio-design-system

> AXQ Design System — v2.0.0  
> Token-based React component library for AxioLedger products.  
> Architecture: **Primitive → Semantic → Component** (3-layer CSS Custom Properties)

[![License: BSL-1.1](https://img.shields.io/badge/License-BSL--1.1-blue.svg)](LICENSE)
[![React 18+](https://img.shields.io/badge/React-18%2B-61dafb)](https://react.dev)
[![TypeScript 5](https://img.shields.io/badge/TypeScript-5-3178c6)](https://typescriptlang.org)
[![WCAG 2.1 AA](https://img.shields.io/badge/WCAG-2.1%20AA-green)](https://www.w3.org/WAI/WCAG21/quickref/)

---

## Installation

```bash
npm install @axioledger/axio-design-system
```

> **Peer dependencies:** `react >= 18`, `react-dom >= 18`

---

## Quick Start

```tsx
// 1. Import the CSS tokens in your app entry point
import '@axioledger/axio-design-system/styles';

// 2. Wrap your app in AxioProvider
import { AxioProvider } from '@axioledger/axio-design-system';

function App() {
  return (
    <AxioProvider defaultTheme="light">
      <YourApp />
    </AxioProvider>
  );
}
```

```tsx
// 3. Use components
import { Button, Input, Card, CardBody, Alert, SecurityAlert } from '@axioledger/axio-design-system';

function WalletPage() {
  return (
    <Card variant="elevated">
      <CardBody>
        <Input label="Recipient" placeholder="alice.axq or 0x…" ansResolve />
        <Button color="blue" fullWidth>Send ETH</Button>
      </CardBody>
    </Card>
  );
}
```

---

## Token Architecture

The system uses a strict **3-layer cascade** of CSS Custom Properties:

```
Primitive  →  Semantic  →  Component
(raw values)  (UI roles)  (per-component)
```

### Layer 1 — Primitive

Raw, absolute values — hex colours, px numbers. Never used directly in components.

| Collection | Examples |
|---|---|
| `Primitive / Color` | `--primitive-grey-900: #101426` |
| `Primitive / Spacing` | `--primitive-space-16: 16px` |
| `Primitive / Radius` | `--primitive-radius-lg: 12px` |
| `Primitive / Font Size` | `--primitive-font-size-16: 16px` |

### Layer 2 — Semantic

Aliases of Primitive tokens expressing **UI meaning**. Override these to theme.

| Collection | Examples |
|---|---|
| `Semantic / Color` (mode: Light / Dark) | `--color-text-primary`, `--color-bg-brand` |
| `Semantic / Spacing` | `--color-inset-md`, `--color-gap-sm` |
| `Semantic / Radius` | `--color-radius-button`, `--color-radius-card` |

### Layer 3 — Component

Point to Semantic tokens — never skip a layer.

```
--button-primary-bg  →  --color-bg-brand  →  --primitive-black
```

### Dark Mode

```tsx
// Programmatic toggle
const { setTheme } = useTheme();
setTheme('dark');

// Or set data attribute directly
document.documentElement.setAttribute('data-theme', 'dark');
```

Only the Semantic layer changes between light/dark. Component tokens auto-adapt.

---

## Components

### Core

| Component | Import |
|---|---|
| `Button` | `{ Button }` |
| `Input` | `{ Input }` |
| `Card`, `CardHeader`, `CardBody`, `CardFooter` | `{ Card, ... }` |
| `Badge`, `Chip` | `{ Badge, Chip }` |
| `Toggle` | `{ Toggle }` |
| `Avatar`, `AvatarGroup` | `{ Avatar, AvatarGroup }` |
| `Tooltip` | `{ Tooltip }` |
| `Skeleton`, `SkeletonCard` | `{ Skeleton, SkeletonCard }` |
| `Navbar` | `{ Navbar }` |
| `Modal`, `BottomSheet` | `{ Modal, BottomSheet }` |
| `Toast`, `ToastContainer` | `{ Toast, ToastContainer }` |

### Form

| Component | Import |
|---|---|
| `OTPInput` | `{ OTPInput }` |
| `Checkbox`, `RadioButton` | `{ Checkbox, RadioButton }` |
| `SearchBar` | `{ SearchBar }` |
| `Dropdown` | `{ Dropdown }` |

### Feedback

| Component | Import |
|---|---|
| `Alert` | `{ Alert }` |
| `SecurityAlert` | `{ SecurityAlert }` |
| `ProgressBar`, `StepIndicator` | `{ ProgressBar, StepIndicator }` |
| `EmptyState` | `{ EmptyState }` |

### Crypto (AxioLedger-specific)

| Component | Import |
|---|---|
| `NamespaceBadge` | `{ NamespaceBadge }` |
| `AddressDisplay` | `{ AddressDisplay }` |
| `PasskeyButton` | `{ PasskeyButton }` |
| `CryptoAssetCard` | `{ CryptoAssetCard }` |
| `TransactionItem` | `{ TransactionItem }` |
| `BalanceDisplay` | `{ BalanceDisplay }` |
| `PriceTicker` | `{ PriceTicker }` |

---

## Hooks

```tsx
import { useTheme, useANSResolver, useToast } from '@axioledger/axio-design-system';

// Theme
const { theme, setTheme } = useTheme();

// ANS resolution
const { resolve, status } = useANSResolver();
const result = await resolve('alice.axq');

// Toast notifications
const toast = useToast();
toast.success('Transaction confirmed');
toast.error('Insufficient balance');
```

---

## Security — Critical Rules

### SecurityAlert

`SecurityAlert` enforces non-overridable security behaviour:

```tsx
// ✅ Safe — action button is enabled
<SecurityAlert level="safe" name="alice.axq" reason="Verified via ANS" onAction={sign} />

// ⚠️  Caution — action button is enabled but styled as warning
<SecurityAlert level="caution" name="pool.kpx" reason="Partially verified" onAction={sign} />

// 🚫 Blocked — action button is ALWAYS disabled. onAction NEVER fires.
<SecurityAlert level="blocked" name="0xDeadBeef…" reason="Unverified address" onAction={sign} />
```

> **Rule**: when `level="blocked"`, the action button renders `disabled` + `aria-disabled="true"` regardless of every other prop. This is intentional and cannot be circumvented.

### AddressDisplay

When an ANS resolver is configured, `AddressDisplay` **never** silently shows a raw hex address without a visible unverified warning.

---

## TLP (Traffic Light Protocol) Types

```ts
import { type TLPLevel, TLP_CONFIG, resolveTLPLevel } from '@axioledger/axio-design-system';

const level = resolveTLPLevel('alice.axq');   // 'safe'
const level2 = resolveTLPLevel('0xDeadBeef'); // 'blocked'

const config = TLP_CONFIG['blocked'];
// { label: 'Unverified', color: '#FF3D71', ariaDescription: '...' }
```

---

## Build Tokens

Generate JSON/JS token manifests from the CSS source:

```bash
npm run build:tokens
# Outputs: dist/tokens.json, dist/tokens.js, dist/tokens.d.ts
```

Import token values in non-CSS environments (React Native, mobile):

```ts
import { colorTokens, primitiveTokens } from '@axioledger/axio-design-system/dist/tokens.js';
```

---

## Development

```bash
# Storybook
npm run storybook       # http://localhost:6006

# Tests
npm test
npm run test:watch

# Type check
npm run type-check

# Build
npm run build
```

---

## Accessibility

All components target **WCAG 2.1 Level AA**:

- Focus rings on all interactive elements (`border/focus` token → `#0095FF`)
- `role`, `aria-*` attributes on all interactive and status components
- Keyboard navigation: `Modal` traps focus + restores on close; `Navbar` supports arrow keys; `Dropdown` is keyboard-navigable
- Colour contrast: yellow/green/orange buttons use dark text (`#101426`) to meet 4.5:1 ratio
- `@storybook/addon-a11y` runs axe-core on every story

---

## Naming Convention

All Figma Variable collections use the `AXQ /` prefix to distinguish from third-party libraries:

- `AXQ / Primitive / Color`
- `AXQ / Semantic / Color`
- `AXQ / Component / Button`

---

## License

[BSL-1.1](LICENSE) — AxioLedger Core Team
