/**
 * SwapPanel.tsx — AMM Swap UI (constant-product x*y=k)
 */
'use client';

import React, { useState, useCallback } from 'react';

const TOKENS = ['AXQ', 'VPX', 'SQX', 'KPX', 'VRQ', 'USDC'];

// Simulate AMM quote: amount_out = (reserve_out * amount_in * 0.9970) / (reserve_in + amount_in * 0.9970)
function getQuote(amountIn: number, reserveIn = 1_000_000, reserveOut = 1_000_000): number {
  const feeMultiplier = 0.9970; // 0.30% fee
  const amountInWithFee = amountIn * feeMultiplier;
  return (reserveOut * amountInWithFee) / (reserveIn + amountInWithFee);
}

export function SwapPanel() {
  const [tokenIn, setTokenIn] = useState('USDC');
  const [tokenOut, setTokenOut] = useState('AXQ');
  const [amountIn, setAmountIn] = useState('');
  const [slippage, setSlippage] = useState(0.5);
  const [swapped, setSwapped] = useState(false);

  const amountInNum = parseFloat(amountIn) || 0;
  const amountOut = amountInNum > 0 ? getQuote(amountInNum) : 0;
  const priceImpact = amountInNum > 0
    ? ((amountInNum - amountOut) / amountInNum * 100).toFixed(2)
    : '0.00';

  const flip = useCallback(() => {
    setTokenIn(tokenOut);
    setTokenOut(tokenIn);
    setAmountIn('');
  }, [tokenIn, tokenOut]);

  const handleSwap = (e: React.FormEvent) => {
    e.preventDefault();
    console.log(`Swap ${amountIn} ${tokenIn} → ${amountOut.toFixed(6)} ${tokenOut}`);
    setSwapped(true);
    setTimeout(() => setSwapped(false), 3000);
  };

  return (
    <div style={{
      border: '1px solid #e5e7eb', borderRadius: 12,
      padding: '1.5rem', background: '#f7f8fa',
    }}>
      <h2 style={{ fontSize: 16, fontWeight: 600, marginBottom: '1rem' }}>⚡ Swap</h2>

      <form onSubmit={handleSwap}>
        {/* Token In */}
        <div style={{
          background: '#fff', border: '1px solid #e5e7eb',
          borderRadius: 8, padding: '0.75rem', marginBottom: 8,
        }}>
          <div style={{ fontSize: 12, color: '#57606a', marginBottom: 4 }}>You pay</div>
          <div style={{ display: 'flex', gap: 8 }}>
            <input
              type="number" placeholder="0.0" value={amountIn}
              onChange={e => setAmountIn(e.target.value)}
              min="0" step="any"
              style={{ flex: 1, fontSize: 20, border: 'none', outline: 'none', background: 'transparent' }}
            />
            <select value={tokenIn} onChange={e => setTokenIn(e.target.value)}
              style={{ padding: '0.3rem 0.5rem', borderRadius: 6, border: '1px solid #e5e7eb', fontWeight: 600 }}>
              {TOKENS.filter(t => t !== tokenOut).map(t => <option key={t}>{t}</option>)}
            </select>
          </div>
        </div>

        {/* Flip button */}
        <button type="button" onClick={flip} style={{
          display: 'block', margin: '0 auto 8px',
          background: 'none', border: '1px solid #e5e7eb',
          borderRadius: '50%', width: 32, height: 32, cursor: 'pointer', fontSize: 16,
        }}>⇅</button>

        {/* Token Out */}
        <div style={{
          background: '#fff', border: '1px solid #e5e7eb',
          borderRadius: 8, padding: '0.75rem', marginBottom: '1rem',
        }}>
          <div style={{ fontSize: 12, color: '#57606a', marginBottom: 4 }}>You receive</div>
          <div style={{ display: 'flex', gap: 8 }}>
            <span style={{ flex: 1, fontSize: 20, color: amountOut > 0 ? '#1f2328' : '#57606a' }}>
              {amountOut > 0 ? amountOut.toFixed(6) : '0.0'}
            </span>
            <select value={tokenOut} onChange={e => setTokenOut(e.target.value)}
              style={{ padding: '0.3rem 0.5rem', borderRadius: 6, border: '1px solid #e5e7eb', fontWeight: 600 }}>
              {TOKENS.filter(t => t !== tokenIn).map(t => <option key={t}>{t}</option>)}
            </select>
          </div>
        </div>

        {/* Stats */}
        {amountInNum > 0 && (
          <div style={{ fontSize: 12, color: '#57606a', marginBottom: '1rem' }}>
            <div>Fee: 0.30% · Price impact: {priceImpact}%</div>
            <div>Slippage tolerance: {slippage}%</div>
          </div>
        )}

        <button type="submit" disabled={!amountIn || parseFloat(amountIn) <= 0} style={{
          width: '100%', background: '#3b82d4', color: '#fff',
          border: 'none', borderRadius: 8, padding: '0.75rem',
          cursor: 'pointer', fontWeight: 600, fontSize: 15,
        }}>
          Swap
        </button>
        {swapped && <p style={{ color: '#16a34a', textAlign: 'center', marginTop: 8, fontSize: 13 }}>✅ Swap executed!</p>}
      </form>
    </div>
  );
}
