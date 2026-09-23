/**
 * TreasuryStats.tsx — Ecosystem Treasury overview (Tokenomics)
 */
'use client';

import React from 'react';

const TOTAL_SUPPLY = 500_000_000_000n;

const ALLOCATIONS = [
  { label: 'VPX Subsidy',  amount: 125_000_000_000n, pct: 25, color: '#3b82d4' },
  { label: 'R&D',          amount: 150_000_000_000n, pct: 30, color: '#7c5cd8' },
  { label: 'RWA Treasury', amount:  75_000_000_000n, pct: 15, color: '#16a34a' },
  { label: 'Team',         amount:  60_000_000_000n, pct: 12, color: '#f59e0b' },
  { label: 'Strategic',    amount:  65_000_000_000n, pct: 13, color: '#ef4444' },
  { label: 'TGE',          amount:  25_000_000_000n, pct:  5, color: '#06b6d4' },
];

function fmt(n: bigint) {
  return (Number(n) / 1e9).toLocaleString('en-US', { maximumFractionDigits: 0 }) + 'B';
}

export function TreasuryStats() {
  return (
    <div style={{
      display: 'grid',
      gridTemplateColumns: 'repeat(auto-fit, minmax(180px, 1fr))',
      gap: '0.75rem',
      marginBottom: '2rem',
    }}>
      {ALLOCATIONS.map(a => (
        <div key={a.label} style={{
          background: '#f7f8fa',
          border: '1px solid #e5e7eb',
          borderLeft: `4px solid ${a.color}`,
          borderRadius: 8,
          padding: '0.75rem 1rem',
        }}>
          <div style={{ fontSize: 12, color: '#57606a' }}>{a.label}</div>
          <div style={{ fontSize: 20, fontWeight: 700, color: '#1f2328' }}>{fmt(a.amount)}</div>
          <div style={{ fontSize: 13, color: a.color }}>{a.pct}% of {fmt(TOTAL_SUPPLY)}</div>
        </div>
      ))}
    </div>
  );
}
