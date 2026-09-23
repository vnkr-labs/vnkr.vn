/**
 * apps/axiopass-wallet/src/app/page.tsx
 * Axiopass Wallet — Full app shell (Onboarding → Banking → Crypto)
 *
 * The AppShell manages all 65+ screens via a lightweight in-app router.
 * Individual screen components live in src/screens/<zone>/
 */

import { AppShell } from '../screens/AppShell';

export default function WalletPage() {
  return <AppShell />;
}
