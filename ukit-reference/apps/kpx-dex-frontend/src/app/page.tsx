/**
 * apps/kpx-dex-frontend/src/app/page.tsx
 * KinetoProtocol DEX — AMM Swap + Cross-chain + RWA Markets
 */

import { SwapPanel } from '../components/SwapPanel';
import { PoolStats } from '../components/PoolStats';
import { CrossChainBridge } from '../components/CrossChainBridge';

export default function DEXPage() {
  return (
    <main style={{ maxWidth: 1100, margin: '0 auto', padding: '2rem' }}>
      <header style={{ marginBottom: '2rem' }}>
        <h1 style={{ fontSize: 24, fontWeight: 700 }}>KinetoProtocol DEX</h1>
        <p style={{ color: '#57606a', fontSize: 14 }}>
          AMM · Cross-chain Bridge · RWA Markets · 600,000 TPS
        </p>
      </header>
      <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '1.5rem' }}>
        <SwapPanel />
        <CrossChainBridge />
      </div>
      <div style={{ marginTop: '1.5rem' }}>
        <PoolStats />
      </div>
    </main>
  );
}
