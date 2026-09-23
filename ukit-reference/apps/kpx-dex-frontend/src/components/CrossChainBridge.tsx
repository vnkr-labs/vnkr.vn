/**
 * CrossChainBridge.tsx — Cross-chain swap UI
 */
'use client';

import React, { useState } from 'react';

const CHAINS = ['AxioLedger', 'Ethereum', 'Arbitrum', 'BNB Chain'];
const TOKENS = ['AXQ', 'KPX', 'USDC', 'ETH', 'BNB'];

export function CrossChainBridge() {
  const [srcChain, setSrcChain] = useState('Ethereum');
  const [dstChain, setDstChain] = useState('AxioLedger');
  const [token, setToken] = useState('USDC');
  const [amount, setAmount] = useState('');
  const [recipient, setRecipient] = useState('');
  const [bridging, setBridging] = useState(false);
  const [done, setDone] = useState(false);

  const handleBridge = async (e: React.FormEvent) => {
    e.preventDefault();
    setBridging(true);
    // TODO: EVMBridgeClient.lockTokens() → KPX Bridge mint on AxioLedger
    await new Promise(r => setTimeout(r, 1500));
    setBridging(false);
    setDone(true);
  };

  return (
    <div style={{
      border: '1px solid #e5e7eb', borderRadius: 12,
      padding: '1.5rem', background: '#f7f8fa',
    }}>
      <h2 style={{ fontSize: 16, fontWeight: 600, marginBottom: '1rem' }}>🌉 Cross-chain Bridge</h2>
      <form onSubmit={handleBridge}>
        <div style={{ display: 'grid', gridTemplateColumns: '1fr auto 1fr', alignItems: 'center', gap: 8, marginBottom: 12 }}>
          <select value={srcChain} onChange={e => setSrcChain(e.target.value)}
            style={{ padding: '0.4rem', borderRadius: 6, border: '1px solid #e5e7eb' }}>
            {CHAINS.filter(c => c !== dstChain).map(c => <option key={c}>{c}</option>)}
          </select>
          <span style={{ fontSize: 18, color: '#57606a' }}>→</span>
          <select value={dstChain} onChange={e => setDstChain(e.target.value)}
            style={{ padding: '0.4rem', borderRadius: 6, border: '1px solid #e5e7eb' }}>
            {CHAINS.filter(c => c !== srcChain).map(c => <option key={c}>{c}</option>)}
          </select>
        </div>

        <div style={{ display: 'flex', gap: 8, marginBottom: 10 }}>
          <input type="number" placeholder="Amount" value={amount}
            onChange={e => setAmount(e.target.value)} min="0"
            style={{ flex: 1, padding: '0.4rem', borderRadius: 6, border: '1px solid #e5e7eb' }} />
          <select value={token} onChange={e => setToken(e.target.value)}
            style={{ padding: '0.4rem', borderRadius: 6, border: '1px solid #e5e7eb', fontWeight: 600 }}>
            {TOKENS.map(t => <option key={t}>{t}</option>)}
          </select>
        </div>

        <input placeholder="Recipient (ANS domain or address)" value={recipient}
          onChange={e => setRecipient(e.target.value)}
          style={{ display: 'block', width: '100%', padding: '0.4rem', borderRadius: 6, border: '1px solid #e5e7eb', marginBottom: 10, boxSizing: 'border-box' }} />

        <div style={{ fontSize: 12, color: '#57606a', marginBottom: 10 }}>
          ZK Bridge Proof required · Estimated time: ~2 min · Fee: 0.05%
        </div>

        <button type="submit" disabled={!amount || !recipient || bridging} style={{
          width: '100%', background: '#7c5cd8', color: '#fff',
          border: 'none', borderRadius: 8, padding: '0.7rem',
          cursor: 'pointer', fontWeight: 600,
        }}>
          {bridging ? '⏳ Bridging...' : 'Bridge Assets'}
        </button>
        {done && <p style={{ color: '#16a34a', textAlign: 'center', marginTop: 8, fontSize: 13 }}>✅ Bridge transaction submitted!</p>}
      </form>
    </div>
  );
}
