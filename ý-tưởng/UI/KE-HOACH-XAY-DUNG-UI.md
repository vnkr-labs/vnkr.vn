# 📱 KẾ HOẠCH XÂY DỰNG UI — VNKR FINTECH MOBILE APP

> **Nguồn prototype:** 42 màn hình PNG trong `ý-tưởng/UI/`  
> **Design System gốc:** [`ý-tưởng/Design-system/DESIGN_SYSTEM_PLAN.md`](../Design-system/DESIGN_SYSTEM_PLAN.md)  
> **Phiên bản:** 1.0  
> **Áp dụng cho:** React Native (Expo) Mobile App — kế thừa **đầy đủ** Design System VNKR đã triển khai trên web

---

## MỤC LỤC

1. [Nguyên Tắc Kết Hợp Design System](#1-nguyên-tắc-kết-hợp-design-system)
2. [Design Tokens — Kế Thừa từ VNKR](#2-design-tokens--kế-thừa-từ-vnkr)
3. [Typography System](#3-typography-system)
4. [Component Library Mobile](#4-component-library-mobile)
5. [Luồng Điều Hướng](#5-luồng-điều-hướng)
6. [Chi Tiết Từng Màn Hình](#6-chi-tiết-từng-màn-hình)
7. [Tech Stack & Cấu Trúc Dự Án](#7-tech-stack--cấu-trúc-dự-án)
8. [Sprint Roadmap](#8-sprint-roadmap)
9. [Assets Cần Thiết](#9-assets-cần-thiết)
10. [Animation & Interaction Spec](#10-animation--interaction-spec)

---

## 1. NGUYÊN TẮC KẾT HỢP DESIGN SYSTEM

### 1.1 Nguồn gốc & Ưu tiên

Mobile app VNKR **kế thừa trực tiếp** từ Design System web đã hoàn thiện (Sprint 1–7). Nguyên tắc:

| Quy tắc | Chi tiết |
|---|---|
| **Token = nguồn duy nhất** | Mọi màu, font, spacing đều tham chiếu token từ `DESIGN_SYSTEM_PLAN.md` |
| **Prototype → thực tế** | Màu trong UI prototype được **map sang token VNKR** tương ứng, không dùng hex rời |
| **Mobile-first từ thiết kế** | UI kits trong `ý-tưởng/Design-system/UI kits.png` đã là mobile pattern |
| **Pill buttons nhất quán** | Border-radius `--radius-pill: 50px` áp dụng đồng nhất web + mobile |
| **VNKR Brand DNA** | Clean/minimal, Work Sans headings, playful accent colors, dark mode ready |

### 1.2 Map màu Prototype → Token VNKR

| Màu prototype (mô tả) | Token VNKR tương ứng | Hex thực tế |
|---|---|---|
| Lime green (Home, Swap, Cashback bg) | `--brand-green` | `#8EFF6C` |
| Mint green (Splash v1, Home Services) | `--color-success` / `--success-300` | `#00D68F` / `#66EBC0` |
| Purple (Splash v2, Add Balance, FAQ) | `--brand-violet` | `#AF96FB` |
| Orange (Settings) | `--accent` / `--brand-orange` | `#FC7339` |
| Sky blue (Edit Profile) | `--color-info` | `#0095FF` |
| Pink (Notifications) | `--brand-magenta` | `#FD9FDD` |
| Teal (Home Services, Pay debt) | `--brand-blue` | `#49DBC8` |
| Black button (CTA chính) | `--color-dark` | `#000000` |
| White surface | `--color-white` / `--bg-card` | `#FFFFFF` |
| Light gray background | `--bg-page` | `#F5F6FA` |
| Input field bg | `--bg-surface` | `#EFEFEF` |
| Input error bg | `--error-100` | `#FFD6E3` |
| Error text (debt, validation) | `--color-error` | `#FF3D71` |
| Success text (crypto +%) | `--color-success` | `#00D68F` |
| Negative text (crypto -%) | `--color-error` | `#FF3D71` |
| Blue radio/link | `--color-info` | `#0095FF` |
| Green toggle ON | `--color-success` | `#00D68F` |
| Gray toggle OFF | `--border-default` | `#E2E8F0` |
| Price change pill (lime) | `--brand-green` | `#8EFF6C` |
| Buy button (purple) | `--brand-violet` | `#AF96FB` |
| Sell button (orange) | `--brand-orange` | `#FC7339` |
| Text primary | `--text-primary` | `#1a1a2e` |
| Text secondary/muted | `--text-secondary` / `--text-muted` | `#4a5568` / `#718096` |

---

## 2. DESIGN TOKENS — KẾ THỪA TỪ VNKR

Các token sau được dùng trực tiếp trong React Native StyleSheet — **không định nghĩa lại**, chỉ import từ `design-tokens.ts`:

### 2.1 Colors

```typescript
// src/design-tokens.ts — kế thừa từ DESIGN_SYSTEM_PLAN.md §3
export const colors = {
  // === BRAND (từ VNKR Design System) ===
  dark:       '#000000',
  brandGrey:  '#EFEFEF',
  brandMagenta:'#FD9FDD',   // Notifications bg
  brandOrange: '#FC7339',   // Sell btn, accent, Settings bg
  brandGreen:  '#8EFF6C',   // Home/Cashback/Swap bg
  brandViolet: '#AF96FB',   // Purple screens, Buy btn, FAQ
  brandBlue:   '#49DBC8',   // Teal — Home Services, Pay debt
  brandYellow: '#FFF172',

  // === SEMANTIC ===
  info:        '#0095FF',   // Sky blue — Edit Profile, radio
  success:     '#00D68F',   // Mint green / positive %
  warning:     '#FFAA00',
  error:       '#FF3D71',   // Red — error, negative %
  greyscale:   '#2E3A59',

  // === SHADE SCALES ===
  success300:  '#66EBC0',
  error100:    '#FFD6E3',   // Input error bg
  info100:     '#CCE5FF',

  // === PRIMARY BRAND VNKR ===
  primary:     '#0A3D62',
  primaryDark: '#072d48',
  primaryLight:'#e8f0f7',
  accent:      '#FC7339',   // = brandOrange

  // === SURFACE ===
  bgPage:      '#F5F6FA',
  bgCard:      '#FFFFFF',
  bgSurface:   '#EFEFEF',   // Input background

  // === TEXT ===
  textPrimary:   '#1a1a2e',
  textSecondary: '#4a5568',
  textMuted:     '#718096',
  textInverse:   '#FFFFFF',

  // === BORDER ===
  borderDefault: '#E2E8F0',
  borderStrong:  '#CBD5E0',
  borderFocus:   '#0A3D62',

  // === STATUS ===
  statusLive:     '#FF3D71',
  statusBreaking: '#FC7339',
  statusPublished:'#00D68F',
  statusDraft:    '#FFAA00',
};
```

### 2.2 Spacing (8px base — từ DESIGN_SYSTEM_PLAN.md §7.1)

```typescript
export const spacing = {
  sp1:  4,    // --space-1
  sp2:  8,    // --space-2
  sp3:  12,   // --space-3
  sp4:  16,   // --space-4  ← horizontal padding màn hình
  sp5:  20,   // --space-5
  sp6:  24,   // --space-6
  sp8:  32,   // --space-8
  sp10: 40,
  sp12: 48,
  sp16: 64,
  sp20: 80,
};
```

### 2.3 Border Radius (từ DESIGN_SYSTEM_PLAN.md §7.2)

```typescript
export const radius = {
  sm:   4,    // --radius-sm
  md:   8,    // --radius-md
  lg:   12,   // --radius-lg  ← Input, Card
  xl:   16,   // --radius-xl
  pill: 50,   // --radius-pill ← Button CTA
  full: 9999, // --radius-full ← Avatar, Toggle, Numpad key tròn
};
```

### 2.4 Shadows (từ DESIGN_SYSTEM_PLAN.md §7.3)

```typescript
export const shadows = {
  sm:   { shadowColor: '#000', shadowOffset: { width: 0, height: 1 }, shadowOpacity: 0.08, shadowRadius: 3, elevation: 2 },
  md:   { shadowColor: '#000', shadowOffset: { width: 0, height: 4 }, shadowOpacity: 0.10, shadowRadius: 12, elevation: 4 },
  card: { shadowColor: '#0A3D62', shadowOffset: { width: 0, height: 2 }, shadowOpacity: 0.08, shadowRadius: 8, elevation: 3 },
};
```

---

## 3. TYPOGRAPHY SYSTEM

**Font**: **Work Sans** (headings) + **Be Vietnam Pro** (body) — đúng theo DESIGN_SYSTEM_PLAN.md §4.1

```typescript
// src/design-tokens.ts
export const fonts = {
  display: 'WorkSans',      // Headings — --font-display
  body:    'BeVietnamPro',  // Body text — --font-body
};

export const fontWeights = {
  regular:  '400',  // --fw-regular
  medium:   '500',  // --fw-medium
  semibold: '600',  // --fw-semibold
  bold:     '700',  // --fw-bold
};

export const textSizes = {
  // Display (balance lớn)
  displayXl: 48,   // $840.20 — amount hero
  displayLg: 40,   // --text-h1 = 2.5rem
  displayMd: 32,   // --text-h2 = 2rem

  // Headings (Work Sans)
  h3: 28,          // --text-h3
  h4: 22,          // --text-h4
  h5: 18,          // --text-h5
  h6: 16,          // --text-h6

  // Body (Be Vietnam Pro)
  b1: 16,          // --text-b1
  b2: 14,          // --text-b2
  caption: 12,     // --text-caption
  overline: 10,    // --text-overline

  // Buttons (từ §5 DESIGN_SYSTEM_PLAN)
  btnGiant:  20,   // --text-btn-giant
  btnLarge:  16,   // --text-btn-large
  btnMedium: 14,   // --text-btn-medium
  btnSmall:  12,   // --text-btn-small

  // Numpad
  numpad: 22,
};

export const lineHeights = {
  tight:   1.2,   // --lh-tight   Headlines
  normal:  1.5,   // --lh-normal  Body
  relaxed: 1.75,  // --lh-relaxed Long-form
};
```

**Setup fonts trong Expo:**
```bash
expo install @expo-google-fonts/work-sans @expo-google-fonts/be-vietnam-pro
```

```typescript
// app/_layout.tsx
import { useFonts, WorkSans_400Regular, WorkSans_600SemiBold, WorkSans_700Bold } from '@expo-google-fonts/work-sans';
import { BeVietnamPro_400Regular, BeVietnamPro_500Medium, BeVietnamPro_600SemiBold } from '@expo-google-fonts/be-vietnam-pro';
```

---

## 4. COMPONENT LIBRARY MOBILE

Tất cả component kế thừa variant từ `DESIGN_SYSTEM_PLAN.md §5–6`. Dưới đây là spec mobile.

### 4.1 `<PrimaryButton>` — Pill Button (từ §5.1 DESIGN_SYSTEM_PLAN)

```typescript
// Variants từ Buttons & Inputs.png: Filled / Outlined / Ghost
// 3 sizes: large (12/24), medium (9/20), small (6/14)

// FILLED (đen) — dùng chủ yếu ở mobile
style={{ backgroundColor: colors.dark, borderRadius: radius.pill, paddingVertical: 14, paddingHorizontal: 24 }}
// labelStyle: font BeVietnamPro, weight semibold, size btnLarge, color white

// OUTLINED — Add balance default
style={{ backgroundColor: 'transparent', borderWidth: 2, borderColor: colors.dark, borderRadius: radius.pill }}

// GHOST — nav links
style={{ backgroundColor: 'transparent' }}

// Brand variants
// Buy: backgroundColor: colors.brandViolet
// Sell: backgroundColor: colors.brandOrange
```

### 4.2 `<InputField>` — Form Input (từ §5.3 DESIGN_SYSTEM_PLAN)

```
border-radius: radius.lg (12)
background (default): colors.bgSurface (#EFEFEF)
background (focused): colors.bgCard (#FFFFFF)
border (default): colors.borderDefault
border (focused): colors.borderFocus (primary #0A3D62)
border (error): colors.error (#FF3D71)
background (error): colors.error100 (#FFD6E3)
label: font BeVietnamPro, weight medium, size b2, color textSecondary
error text: font BeVietnamPro, size caption, color error
```

### 4.3 `<Toggle>` — iOS Switch (từ §5.4 DESIGN_SYSTEM_PLAN)

```
width: 44, height: 24
border-radius: 12
background OFF: colors.borderDefault (#E2E8F0)
background ON:  colors.success (#00D68F)       ← --color-success
thumb: white circle, size 18, shadow sm
transition: 200ms
```

### 4.4 `<Badge>` — Status Badge (từ §6.1 DESIGN_SYSTEM_PLAN)

```
border-radius: radius.pill
font: BeVietnamPro, caption size, semibold, uppercase
padding: 2px 10px

Variants:
- published: bg success100, color success900
- live: bg error100, color error700, pulse animation
- breaking: bg accent (brandOrange), color white
- category: bg primaryLight, color primary
```

### 4.5 `<NumericKeypad>` — 2 Variants

```
Variant A — Rectangular (với sub-label ABC, DEF...):
  key size: flex 1, height: 56, margin: 2
  border-radius: radius.md (8)
  bg: colors.bgSurface
  text: font WorkSans, size numpad (22), weight regular
  sub-label: font BeVietnamPro, size caption, color textMuted
  backspace: icon ←

Variant B — Circular (không sub-label — Swap, PIN):
  key size: 64×64, border-radius: radius.full
  bg: colors.bgSurface
  text: font WorkSans, size numpad, weight regular
```

### 4.6 `<CryptoListItem>`

```
Layout: flex-row, align-center, padding vertical sp3
- Icon coin: circle 44px, colored bg
- Left: coin name (b1, semibold, textPrimary) + ticker-price (caption, textMuted)
- Right: giá (b2, semibold) + % thay đổi (caption, success hoặc error color)
Divider: borderBottom colors.borderDefault, 1px
```

### 4.7 `<TransactionListItem>`

```
Giống CryptoListItem nhưng:
- Right: số tiền (b2, semibold, textPrimary)
- Icon: brand logo tròn
```

### 4.8 `<RoundIconButton>`

```
Container: circle 56px, bg pastel (mỗi action dùng brand color tương ứng)
Icon: SVG 24px, color white hoặc dark
Label: caption, BeVietnamPro, textSecondary, below icon, center
Spacing giữa các button: flex-row, justify: space-evenly
```

### 4.9 `<BottomNavBar>`

```
height: 60px + safe area bottom
bg: colors.bgCard
border-top: 1px colors.borderDefault
shadow: shadows.sm

5 tabs: Home | Crypto | Card | Cashback | More
Active: icon đặc + label bold + color textPrimary
Inactive: icon outline + label regular + color textMuted

Badge dot (Crypto active — đã có crypto): small dot trên icon
```

### 4.10 `<BottomSheet>`

```
Border-radius top-left/right: radius.xl (16) hoặc 24
bg: colors.bgCard
Overlay: rgba(0,0,0,0.4)
Animation: slide up 300ms ease-out (react-native-reanimated)
Handle bar: 4×32, bg borderDefault, centered, top 8px
```

### 4.11 `<CardVisual>` — Thẻ ngân hàng

```
border-radius: radius.xl (16) → 20px
aspect-ratio: 1.586 (thẻ chuẩn 85.6×54mm)
bg: colors.brandGreen (#8EFF6C)
Decoration: blob shapes SVG (đen + hồng)
- VISA text: WorkSans, bold, textPrimary
- Card number: WorkSans, h5 size, tracking-widest
- MM/YY + CVV: caption, textPrimary
shadow: shadows.card
```

### 4.12 `<PriceChangePill>`

```
bg: colors.brandGreen (#8EFF6C)
border-radius: radius.pill
padding: 4px 12px
Icon: ↗ arrow
text: BeVietnamPro, b2, semibold, textPrimary
```

### 4.13 `<Accordion>` — FAQ (từ §6.5 DESIGN_SYSTEM_PLAN)

```
// Cùng spec với web component
trigger: flex-row, justify-between, padding sp3 0
  text: b1, medium, textPrimary
  icon: + / - (24px), color textSecondary
body: b2, textSecondary, lh relaxed
divider: borderBottom borderDefault
```

### 4.14 `<FilterChip>` — Tabs/Pills

```
Active: bg colors.dark, text white, border-radius pill
Inactive: bg transparent, border 1px borderDefault, text textSecondary
padding: sp1 sp4 (4px 16px)
font: BeVietnamPro, btnSmall, medium
```

---

## 5. LUỒNG ĐIỀU HƯỚNG

```
                    ┌─────────────────────┐
                    │    Splash Screen     │
                    │  v1 (brandBlue teal) │
                    │  v2 (brandViolet)    │
                    └──────────┬───────────┘
                               │ auto 2s
                    ┌──────────▼───────────┐
                    │    App Language      │ ← lần đầu (lang-1)
                    │  (BottomSheet modal) │
                    └──────────┬───────────┘
                               │
                    ┌──────────▼───────────┐
                    │     Onboarding       │
                    │  v1 / v2 (swipeable) │
                    └──────────┬───────────┘
                               │
                    ┌──────────▼───────────┐
                    │    Registration      │
                    │  default → filled    │
                    └──────────┬───────────┘
                               │
                    ┌──────────▼───────────┐
                    │    Confirm OTP       │
                    │  default → filled    │
                    └──────────┬───────────┘
                               │
                    ┌──────────▼───────────┐
                    │  Set PIN → Setting   │
                    └──────────┬───────────┘
                               │
       ┌───────────────────────▼───────────────────────┐
       │              MAIN APP — BottomNavBar           │
       │   Home │ Crypto │ Card │ Cashback │ More       │
       └──┬──────────┬────────┬──────────┬─────────────┘
          │          │        │          │          │
       ┌──▼──┐  ┌────▼──┐ ┌──▼──┐ ┌────▼──┐  ┌───▼──┐
       │Home │  │Crypto │ │Card │ │Cashback│  │More  │
       └──┬──┘  └────┬──┘ └──┬──┘ └────────┘  └───┬──┘
          │          │        │                     │
     ┌────┤    ┌─────┤    (CardFlip)          ┌────┤
     │    │    │     │    (CardScroll)         │    │
  Payments │ WalletDetails             Profile│    │
     │    │    │  Swap                  Edit  │ Notifs
  Home  Transfer                      Profile│    │
  Svcs     │                                  │ Notif
     │  Transfer                           FAQ│  Detail
  Search   To                                 │
  BillID Transfer                        Settings
     │   Filled                              │
  PayDebt  │                          Appearance
     │   Success                        (sheet)
  Success                            App Language
                                          (sheet)
```

---

## 6. CHI TIẾT TỪNG MÀN HÌNH

---

### 6.1 Splash Screen

| | v1 | v2 |
|---|---|---|
| **Token bg** | `colors.brandBlue` (#49DBC8) | `colors.brandViolet` (#AF96FB) |
| **Logo** | VNKR wordmark (white) | VNKR wordmark (white) |
| **Duration** | 2000ms | 2000ms |
| **Transition** | Fade → Onboarding | Fade → Onboarding |

---

### 6.2 Onboarding

- **Background v1**: `colors.success` teal gradient
- **Background v2**: `colors.brandViolet` purple
- Layout: Illustration trung tâm + title (WorkSans h3, bold) + sub (BeVietnamPro b2, textSecondary) + pagination dots + `<PrimaryButton>` "Get started"
- Pagination dots: active `colors.dark`, inactive `colors.borderDefault`
- Lưu `AsyncStorage` key `vnkr_onboarded = true`

---

### 6.3 App Language

**2 contexts:**
- `App language-1.png`: màn onboarding, bg `brandViolet`, có "Skip" text button (ghost, textMuted, caption)
- `App language.png`: từ Settings, bg Settings bị dim, BottomSheet trắng

**BottomSheet content:**
- Title: WorkSans h5, bold, center
- Danh sách `<RadioItem>`: flag + name (b1, textPrimary) + radio circle (active: `colors.info`)
- Ngôn ngữ: English ✓, French, Spanish, Chinese, Japanese, Azerbaijani, Russian

---

### 6.4 Registration

- **Background**: `colors.bgCard` trắng
- Phone `<InputField>`: country flag prefix, radius `radius.lg`
- Button "Get OTP": `<PrimaryButton>` filled black, disabled khi trống → active khi nhập

---

### 6.5 Confirm OTP

- 6 ô digit input: border `borderDefault` → `borderFocus` khi active → `borderStrong` khi filled
- Countdown "Resend code in 0:59": BeVietnamPro caption, textMuted
- Button "Confirm": `<PrimaryButton>` filled black

---

### 6.6 Set PIN / Setting PIN Code / Enter PIN / PIN is wrong

- **Background**: `colors.bgCard`
- PIN dots: 4 circles 14px, border `borderDefault` → filled `colors.dark`
- Numpad: **Variant B circular** — radius full, bg `bgSurface`
- **Error state**: dots chuyển `colors.error`, shake animation (translateX ±8px, 4 cycles, 300ms), text "Wrong PIN" (caption, error)
- **Biometric**: prompt FaceID/TouchID sau khi set

---

### 6.7 Home v1 / v2

- **Header bg**: `colors.brandGreen` (#8EFF6C)
- **Balance Card**: bg `bgCard`, shadow `shadows.card`, radius `radius.xl`
  - "Total balance" label: BeVietnamPro caption, textMuted
  - Amount: WorkSans displayLg (40px), bold, textPrimary
- **Action buttons**: 4 `<RoundIconButton>` — Receive (brandBlue), Send (brandYellow), Swap (brandViolet), More (brandGrey)
- **v1 services grid**: icon grid 2 hàng, 4 cột — `<RoundIconButton>` nhỏ hơn (44px)
- **Recent transactions**: `<SectionLabel>` "Today" + danh sách `<TransactionListItem>`
- Pull-to-refresh: RefreshControl với `colors.primary`

---

### 6.8 Payments

- **Background**: `colors.bgPage` (#F5F6FA)
- Grid 2-3 cột: `<RoundIconButton>` large + label — Home services, Mobile, TV, Internet, Water, Gas, Electricity...
- Search bar: `<InputField>` pill style, search icon trái

---

### 6.9 Home Services

- **Header bg**: `colors.brandBlue` (#49DBC8)
- Search bar: bg white, radius pill
- Section label "Recently sent": BeVietnamPro caption, textMuted — `<SectionLabel>`
- List: `<TransactionListItem>` — logo provider tròn + tên
- Tap → BottomSheet "Search Bill ID"

---

### 6.10 Search Bill ID (BottomSheet)

- `<BottomSheet>` trên Home Services
- Header: provider logo 40px + name WorkSans h5
- `<InputField>` "Bill ID": label nhỏ, nhập bằng numpad A
- Button "Search bill": `<PrimaryButton>` black
- **Numpad A** (rectangular, có ABC sub-label)

---

### 6.11 Pay Service Debt

- **Header bg**: `colors.brandBlue`
- Provider logo circle 64px
- "Current debt: $38.00": BeVietnamPro b2, `colors.error` — `<Badge>` debt variant
- Amount display "$0": WorkSans displayXl (48px), textMuted → textPrimary khi nhập
- "Your balance: $2500.70": BeVietnamPro caption, textMuted
- Button "Send": `<PrimaryButton>` black
- **Numpad A**

---

### 6.12 Success

- Background: `colors.bgCard`
- Check icon: circle 80px `colors.success`, ✓ white — draw animation (SVG stroke)
- Title: WorkSans h4, bold, textPrimary
- Details: BeVietnamPro b2, textSecondary
- Button "Back to home": `<PrimaryButton>` black
- Optional: confetti (react-native-confetti-cannon)

---

### 6.13 Transfer / Transfer to / Transfer to Filled

- **Transfer**: bg `bgCard`, amount display, numpad A, button "Transfer"
- **Transfer to**: search input (recipient) + `<BalanceDisplay>` + numpad
  - Button disabled (outline) khi chưa nhập → active (black) khi đủ
- **Transfer to Filled**: amount hiện, button active

---

### 6.14 Add Balance (default + filled)

- **Background**: `colors.brandViolet` (#AF96FB) full
- `<CardVisual>`: VISA, bg `colors.brandGreen`, blob shapes đen + magenta
- Button "Continue":
  - default: outline style (`borderColor: dark, bg: transparent`)
  - filled: `<PrimaryButton>` black
- **Numpad A**: nhập số thẻ tuần tự → update CardVisual real-time

---

### 6.15 Adding Amount

- **Background**: `colors.brandViolet` → white card dưới
- Card hiển thị: "From" label + icon thẻ xanh + số thẻ/date/CVV
- Amount "$0": WorkSans displayXl, textMuted → textPrimary
- Button "Top up": `<PrimaryButton>` black
- **Numpad A**

---

### 6.16 Card Screen

- **Background**: `colors.bgPage`
- `<CardVisual>` đặt trên cùng — tap để flip (rotateY 180°)
- 4 `<RoundIconButton>`: Freeze (brandBlue), Top up (brandGreen), Transfer (brandViolet), More (brandGrey)
- Section "Transactions": `<TransactionListItem>` × n
- Scroll behavior: card sticky/parallax khi scroll

---

### 6.17 Wallet Details (Crypto coin)

- **Background**: `colors.bgCard`
- Coin icon 48px (BTC circle cam)
- Amount: WorkSans displayLg, textPrimary
- Change: `colors.success`, BeVietnamPro b2
- `<PriceChangePill>`: bg `brandGreen`, tooltip trên chart
- **Area Chart**: fill gradient brandGreen → transparent, line `colors.success`
  - Time tabs D/W/M/6M/Y/All: `<FilterChip>`
- Section "Your wallet": `<CryptoListItem>` × n
- Footer: `[Buy]` (bg brandViolet) + `[Sell]` (bg brandOrange) — pill, side by side

---

### 6.18 Crypto Screen

**Empty state** (Crypto start trade):
- Background: `bgCard`
- Geometric shapes: asterisk `brandGreen`, circle `brandViolet`, diamond `brandBlue`, diamond `brandYellow`, stroke `brandMagenta`
- Title 3 dòng: WorkSans h3, bold, textPrimary
- Button "Start trade": `<PrimaryButton>` black

**Có dữ liệu**:
- Background: `bgPage`
- `<BalanceDisplay>` + `<PriceChangePill>` (brandGreen)
- 5 `<RoundIconButton>`: Receive, Send, Swap, Buy, Sell
- Section "Trading": `<CryptoListItem>` × n
- Positive %: `colors.success`, Negative %: `colors.error`

---

### 6.19 Swap

- **Top half bg**: `colors.brandGreen` (#8EFF6C)
- **Bottom half bg**: `bgCard`
- From: WorkSans displayLg + coin ticker + coin icon
- Divider: line ngang + `⇌` button circle `brandMagenta` — rotateZ 180° khi tap
- To: WorkSans displayLg + coin ticker + coin icon
- **Numpad B** (circular)

---

### 6.20 Cashback

- **Top bg**: `colors.brandGreen`
- Amount: WorkSans displayXl, textPrimary
- Button "Send to card": `<PrimaryButton>` black
- Bottom white card:
  - "History" + filter icon: `<Badge>` filter icon corner
  - `<SectionLabel>` "Today" / "Yesterday"
  - `<TransactionListItem>` — Starbucks (coffee), Apple (tech), Cabify (taxi)...

---

### 6.21 Profile (More tab)

- **Header bg**: `colors.brandGreen`
- Avatar: circle 80px, border 3px white
- Name: WorkSans h4, bold, textPrimary
- Contact items: icon circle (brandBlue / brandBlue) + info
- Menu items — `<ListItem>` (từ §6.4 DESIGN_SYSTEM_PLAN):
  - Tariffs → (brandYellow icon), Notifications (brandMagenta), FAQ (brandViolet), Settings (brandOrange)
  - Log out: text `colors.error`, icon `brandMagenta`
- Arrow `>` cho navigable items

---

### 6.22 Edit Profile

- **Header bg**: `colors.info` (#0095FF) sky blue
- Section label "Personal details": BeVietnamPro caption, textMuted
- 4 `<InputField>`: Name, Surname, Email (error state), Phone
  - Email error: border `error`, bg `error100`, caption "Email will not be empty" màu `error`
- Button "Save details": `<PrimaryButton>` black, bottom

---

### 6.23 Notifications

- **Header bg**: `colors.brandMagenta` (#FD9FDD)
- `<SectionLabel>` "New" / "Old"
- `<NotificationItem>` = `<TransactionListItem>` variant:
  - "New": title bold (h6, WorkSans semibold)
  - "Old": title regular (b1, BeVietnamPro)
- Tap → Notification Details

---

### 6.24 Notification Details

- **Header bg**: `colors.brandMagenta` dimmed (overlay 0.3)
- `<BottomSheet>`:
  - Title: WorkSans h4, bold
  - Hero image: full-width, radius `radius.lg`, aspect 16:9
  - Body: BeVietnamPro b1, textSecondary, lh relaxed
  - Close `×` float: circle button, bg `bgCard`, shadow sm — góc dưới center

---

### 6.25 FAQ

- **Header bg**: `colors.brandViolet` (#AF96FB)
- Filter `<FilterChip>` horizontal scroll: All | Card | Refund | Crypto | Credit...
  - Active: bg `dark`, text white
  - Inactive: border `borderDefault`
- `<Accordion>` list (từ DESIGN_SYSTEM_PLAN §6.5):
  - Trigger: b1 medium textPrimary + +/- icon textSecondary
  - Body: b2 textSecondary lh relaxed
  - Divider: `borderDefault`

---

### 6.26 Settings

- **Header bg**: `colors.brandOrange` (#FC7339) = `colors.accent`
- White card — `<ListItem>` (từ DESIGN_SYSTEM_PLAN §6.4):
  - App language → (icon `brandBlue` teal circle)
  - Face ID → `<Toggle>` ON (`colors.success`)
  - Push notifications → `<Toggle>` OFF (`borderDefault`)
  - Appearance → (icon `brandViolet` circle)
  - Download content → (icon `brandMagenta` circle)
  - Email updates → `<Toggle>` ON
- Tap "App language" / "Appearance" → `<BottomSheet>`

---

### 6.27 Appearance (BottomSheet)

- Dim overlay trên Settings
- `<BottomSheet>`:
  - Title "Appearance": WorkSans h5, center
  - 3 `<FilterChip>` ngang: `☆ System` | `☀ Light` | `🌙 Dark`

---

### 6.28 App Language (BottomSheet từ Settings)

- Tương tự §6.3 nhưng không có "Skip"
- Hiển thị trên Settings (bg `brandOrange` bị dim)

---

## 7. TECH STACK & CẤU TRÚC DỰ ÁN

### 7.1 Tech Stack

| Layer | Công nghệ | Lý do chọn |
|---|---|---|
| Framework | **React Native + Expo SDK 51** | Cross-platform, fast dev, OTA updates |
| Navigation | **React Navigation v6** (Stack + BottomTab) | Standard, well-supported |
| State | **Zustand** | Nhẹ, đơn giản, phù hợp app tầm vừa |
| Fonts | **@expo-google-fonts** (Work Sans + Be Vietnam Pro) | Đồng nhất với web VNKR |
| Animations | **React Native Reanimated v3** | 60fps native thread |
| Gestures | **React Native Gesture Handler** | Pan, swipe, long press |
| Charts | **Victory Native XL** hoặc `react-native-svg` custom | Area chart, tooltip |
| Forms | **react-hook-form + zod** | Type-safe validation |
| i18n | **i18next + react-i18next** | Đồng nhất với web |
| Storage | **@react-native-async-storage** | Local persist |
| Biometrics | **expo-local-authentication** | FaceID / TouchID |
| Bottom Sheet | **@gorhom/bottom-sheet** | Smooth animation |
| Toast | Custom component dùng VNKRUI token | Đồng nhất với web ToastModule |

### 7.2 Cấu Trúc Thư Mục

```
src/
├── design-tokens.ts          ← Token kế thừa từ DESIGN_SYSTEM_PLAN.md
├── components/
│   ├── base/                 ← PrimaryButton, InputField, Toggle, Badge, FilterChip
│   ├── numpad/               ← NumericKeypadRect, NumericKeypadCircle
│   ├── cards/                ← CardVisual, CryptoListItem, TransactionListItem
│   ├── navigation/           ← BottomNavBar, BottomSheet
│   └── charts/               ← AreaChart, PriceChangePill
├── screens/
│   ├── auth/                 ← Splash, Onboarding, Registration, OTP, PIN
│   ├── home/                 ← Home, Payments, HomeServices, SearchBillID, PayDebt
│   ├── card/                 ← Card, AddBalance, AddingAmount
│   ├── crypto/               ← Crypto, WalletDetails, Swap
│   ├── cashback/             ← Cashback
│   ├── transfer/             ← Transfer, TransferTo, Success
│   └── profile/              ← Profile, EditProfile, Notifications, FAQ, Settings
├── navigation/
│   ├── AuthStack.tsx
│   ├── MainTabs.tsx
│   └── RootNavigator.tsx
├── store/                    ← Zustand stores
├── hooks/                    ← useTheme, useAuth, usePIN
└── i18n/                     ← vi.json, en.json (+ ngôn ngữ khác)
```

---

## 8. SPRINT ROADMAP

| Sprint | Màn hình / Deliverable | Priority |
|---|---|---|
| **S1** | `design-tokens.ts`, base components (Button, Input, Toggle, Badge) | **P0** |
| **S2** | Fonts (Work Sans + Be Vietnam Pro), BottomNavBar, navigation setup | **P0** |
| **S3** | Splash, Onboarding, App Language (first-time) | **P0** |
| **S4** | Registration, Confirm OTP | **P0** |
| **S5** | Set PIN, Enter PIN, PIN is wrong | **P0** |
| **S6** | Home v1/v2 (BottomNavBar live) | **P0** |
| **S7** | Card screen, Card flip animation, Card scroll | **P1** |
| **S8** | Transfer, Transfer to, Success screen | **P1** |
| **S9** | Crypto empty state, Crypto list, Wallet Details + chart | **P1** |
| **S10** | Swap screen | **P1** |
| **S11** | Add Balance, Adding Amount | **P1** |
| **S12** | Cashback screen | **P1** |
| **S13** | Payments, Home Services, Search Bill ID, Pay Service Debt | **P2** |
| **S14** | Profile, Edit Profile | **P2** |
| **S15** | Notifications, Notification Details | **P2** |
| **S16** | FAQ (Accordion + FilterChip) | **P2** |
| **S17** | Settings, Appearance, App Language (settings context) | **P2** |
| **S18** | Dark mode: token swap `[data-theme="dark"]` → RN Appearance API | **P2** |
| **S19** | Animations polish, haptic feedback, confetti, PIN shake | **P3** |
| **S20** | i18n setup (vi/en), PWA/deeplink, performance audit | **P3** |

---

## 9. ASSETS CẦN THIẾT

| Asset | Dùng ở | Ghi chú |
|---|---|---|
| VNKR logo wordmark SVG (white + dark) | Splash, Onboarding | Từ `App icon and logo white.png` |
| 3D coin/money illustration | Splash v2, Onboarding v2, App Language-1 | Export từ Figma hoặc dùng LottieFiles |
| Geometric shapes SVG (asterisk, diamond, circle, stroke) | Crypto start trade | Colors: brandGreen, brandViolet, brandBlue, brandYellow, brandMagenta |
| Card blob shapes SVG | CardVisual component | Đen + brandMagenta |
| Coin icons: BTC, ETH, USDT, SOL, DOGE | Crypto list, Wallet details | SVG, circular |
| Brand logos: Starbucks, Apple, McDonalds, Cabify... | Cashback history | PNG 40×40 |
| Utility provider logos: PG&E, Edison... | Home Services | PNG 40×40 |
| Country flag emojis/images | App Language list | Twemoji hoặc system emoji |
| Category icons: Internet, TV, Gas, Phone... | Payments grid | SVG icon set |

---

## 10. ANIMATION & INTERACTION SPEC

| Interaction | Animation | Timing | Thư viện |
|---|---|---|---|
| Splash → App | Fade opacity 0→1 | 400ms ease | Reanimated |
| BottomSheet open | translateY slide up | 300ms ease-out | @gorhom/bottom-sheet |
| BottomSheet close | translateY slide down | 250ms ease-in | @gorhom/bottom-sheet |
| Card flip | rotateY 0→180° | 400ms ease | Reanimated |
| PIN wrong | shake translateX ±8px × 4 | 300ms | Reanimated |
| OTP digit input | scale 1→1.1→1 | 100ms bounce | Reanimated |
| Numpad tap | scale 0.9→1 + haptic | 50ms | Haptics.selectionAsync |
| Swap ⇌ button | rotateZ 0→180° + content slide | 300ms | Reanimated |
| Success check draw | SVG stroke-dashoffset | 600ms ease | react-native-svg |
| Success confetti | particle burst | 2000ms | react-native-confetti-cannon |
| PriceChangePill | fadeIn + translateY 4→0 | 200ms | Reanimated |
| Page transition | Native slide (Stack default) | 350ms | React Navigation |
| Pull to refresh | spinner `colors.primary` | — | RefreshControl |
| Crypto % change | color flash (neutral→green/red) | 500ms | Reanimated |

---

## 11. LIÊN KẾT TÀI LIỆU

| Tài liệu | Đường dẫn |
|---|---|
| Design System VNKR (nguồn gốc) | [`ý-tưởng/Design-system/DESIGN_SYSTEM_PLAN.md`](../Design-system/DESIGN_SYSTEM_PLAN.md) |
| Color Palette ảnh | `ý-tưởng/Design-system/Color palette.png` |
| Typography ảnh | `ý-tưởng/Design-system/Typography.png` |
| Button system ảnh | `ý-tưởng/Design-system/Buttons & Inputs.png` |
| UI kits ảnh | `ý-tưởng/Design-system/UI kits.png` |
| Logo VNKR dark | `ý-tưởng/Design-system/App icon and logo green.png` |
| Logo VNKR light | `ý-tưởng/Design-system/App icon and logo white.png` |
| Brand thumbnail | `ý-tưởng/Design-system/Thumbnail.png` |
| UI Prototype folder | `ý-tưởng/UI/` (42 PNG) |
| Web CSS tokens | `public/assets/css/vnkr-tokens.css` |
| Web UI library | `public/assets/css/vnkr-ui.css` |
| Web JS library | `public/assets/js/vnkr-ui.js` |

---

*Tài liệu kết hợp từ: 42 màn hình UI prototype + VNKR Design System (Sprint 1–7)*  
*Nguyên tắc: **1 nguồn token duy nhất** — web và mobile dùng chung Design System VNKR*  
*Phiên bản: 2.0 — Updated để đồng nhất với `DESIGN_SYSTEM_PLAN.md`*
