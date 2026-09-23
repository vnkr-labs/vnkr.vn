/**
 * PoolStats.tsx — Liquidity pool statistics
 */
'use client';

import React from 'react';

interface Pool {
  pair: string;
  tvl: string;
  volume24h: string;
  apr: string;
  fee: string;
}

const POOLS: Pool[] = [
  { pair: 'AXQ / USDC', tvl: '$12,400,000', volume24h: '$3,200,000', apr: '28.4%', fee: '0.30%' },
  { pair: 'KPX / AXQ',  tvl: '$8,700,000',  volume24h: '$1,900,000', apr: '22.1%', fee: '0.30%' },
  { pair: 'VPX / AXQ',  tvl: '$6,200,000',  volume24h: '$980,000',   apr: '19.7%', fee: '0.30%' },
  { pair: 'SQX / AXQ',  tvl: '$4,800,000',  volume24h: '$720,000',   apr: '17.3%', fee: '0.30%' },
  { pair: 'VRQ / AXQ',  tvl: '$3,100,000',  volume24h: '$450,000',   apr: '14.8%', fee: '0.30%' },
  { pair: 'ETH / AXQ',  tvl: '$15,600,000', volume24h: '$5,100,000', apr: '31.2%', fee: '0.30%' },
];

export function PoolStats() {
  return (
    <div>
      <h2 style={{ fontSize: 16, fontWeight: 600, marginBottom: '0.75rem' }}>
        💧 Liquidity Pools
      </h2>
      <div style={{ overflowX: 'auto' }}>
        <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: 14 }}>
          <thead>
            <tr style={{ background: '#f7f8fa', borderBottom: '2px solid #e5e7eb' }}>
              {['Pair', 'TVL', 'Volume 24h', 'APR', 'Fee'].map(h => (
                <th key={h} style={{ padding: '0.6rem 1rem', textAlign: 'left', fontWeight: 600 }}>{h}</th>
              ))}
              <th style={{ padding: '0.6rem 1rem' }}></th>
            </tr>
          </thead>
          <tbody>
            {POOLS.map((p, i) => (
              <tr key={p.pair} style={{ borderBottom: '1px solid #e5e7eb', background: i % 2 ? '#fff' : '#f7f8fa' }}>
                <td style={{ padding: '0.6rem 1rem', fontWeight: 600 }}>{p.pair}</td>
                <td style={{ padding: '0.6rem 1rem' }}>{p.tvl}</td>
                <td style={{ padding: '0.6rem 1rem' }}>{p.volume24h}</td>
                <td style={{ padding: '0.6rem 1rem', color: '#16a34a', fontWeight: 600 }}>{p.apr}</td>
                <td style={{ padding: '0.6rem 1rem', color: '#57606a' }}>{p.fee}</td>
                <td style={{ padding: '0.6rem 1rem' }}>
                  <button style={{
                    background: '#3b82d4', color: '#fff',
                    border: 'none', borderRadius: 6,
                    padding: '4px 12px', cursor: 'pointer', fontSize: 12,
                  }}>
                    Add Liquidity
                  </button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}
