/**
 * VotingPanel.tsx — Quadratic Voting interface
 * votes = floor(sqrt(tokenBalance))
 */
'use client';

import React, { useState } from 'react';

function integerSqrt(n: bigint): bigint {
  if (n === 0n) return 0n;
  let x = n;
  let y = (x + 1n) / 2n;
  while (y < x) {
    x = y;
    y = (x + n / x) / 2n;
  }
  return x;
}

export function VotingPanel() {
  const [proposalId, setProposalId] = useState('');
  const [tokenBalance, setTokenBalance] = useState('');
  const [approve, setApprove] = useState(true);
  const [submitted, setSubmitted] = useState(false);

  const balance = BigInt(tokenBalance || '0');
  const votingWeight = integerSqrt(balance);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    // TODO: send CastVote instruction to AxioLedger program
    console.log({ proposalId, tokenBalance, approve, votingWeight: votingWeight.toString() });
    setSubmitted(true);
  };

  return (
    <form onSubmit={handleSubmit} style={{ maxWidth: 480 }}>
      <div style={{ marginBottom: '0.75rem' }}>
        <label>Proposal ID</label>
        <input
          type="number" value={proposalId} min="1"
          onChange={e => setProposalId(e.target.value)}
          style={{ display: 'block', width: '100%', padding: '0.4rem', marginTop: 4 }}
        />
      </div>
      <div style={{ marginBottom: '0.75rem' }}>
        <label>Your Token Balance ($AXQ)</label>
        <input
          type="number" value={tokenBalance} min="0"
          onChange={e => setTokenBalance(e.target.value)}
          style={{ display: 'block', width: '100%', padding: '0.4rem', marginTop: 4 }}
        />
        {balance > 0n && (
          <p style={{ fontSize: 13, color: '#57606a', marginTop: 4 }}>
            Voting weight: <strong>√{balance.toString()} = {votingWeight.toString()} votes</strong>
          </p>
        )}
      </div>
      <div style={{ marginBottom: '1rem', display: 'flex', gap: '1rem' }}>
        <label>
          <input type="radio" checked={approve} onChange={() => setApprove(true)} /> ✅ Approve
        </label>
        <label>
          <input type="radio" checked={!approve} onChange={() => setApprove(false)} /> ❌ Reject
        </label>
      </div>
      <button
        type="submit"
        disabled={!proposalId || !tokenBalance}
        style={{
          background: '#3b82d4', color: '#fff',
          border: 'none', borderRadius: 6, padding: '0.5rem 1.5rem',
          cursor: 'pointer', fontSize: 14,
        }}
      >
        Submit Vote
      </button>
      {submitted && <p style={{ color: '#16a34a', marginTop: 8 }}>✅ Vote submitted successfully</p>}
    </form>
  );
}
