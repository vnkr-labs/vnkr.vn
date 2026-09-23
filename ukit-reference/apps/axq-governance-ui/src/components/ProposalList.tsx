/**
 * ProposalList.tsx — List of on-chain governance proposals
 */
'use client';

import React, { useEffect, useState } from 'react';

export type ProposalStatus = 'Active' | 'Passed' | 'Vetoed' | 'Executed' | 'Cancelled';

export interface Proposal {
  id: number;
  descriptionHash: string;
  proposer: string;
  votesFor: bigint;
  votesAgainst: bigint;
  createdAt: number;
  executionTime: number;
  status: ProposalStatus;
  vetoCount: number;
}

const STATUS_COLOR: Record<ProposalStatus, string> = {
  Active: '#3b82d4',
  Passed: '#16a34a',
  Vetoed: '#dc2626',
  Executed: '#7c5cd8',
  Cancelled: '#57606a',
};

export function ProposalList() {
  const [proposals, setProposals] = useState<Proposal[]>([]);

  useEffect(() => {
    // TODO: fetch from AxioLedger RPC / program account
    setProposals([
      {
        id: 1,
        descriptionHash: 'QmABC...1',
        proposer: '7Xf8...aBc',
        votesFor: 1500n,
        votesAgainst: 200n,
        createdAt: Date.now() / 1000 - 86400,
        executionTime: Date.now() / 1000 + 518400,
        status: 'Active',
        vetoCount: 0,
      },
      {
        id: 2,
        descriptionHash: 'QmDEF...2',
        proposer: '9Kj2...xYz',
        votesFor: 8000n,
        votesAgainst: 100n,
        createdAt: Date.now() / 1000 - 172800,
        executionTime: Date.now() / 1000 + 432000,
        status: 'Passed',
        vetoCount: 1,
      },
    ]);
  }, []);

  return (
    <div>
      {proposals.map(p => (
        <div key={p.id} style={{
          border: '1px solid #e5e7eb',
          borderRadius: 8,
          padding: '1rem',
          marginBottom: '0.75rem',
          background: '#f7f8fa',
        }}>
          <div style={{ display: 'flex', justifyContent: 'space-between' }}>
            <strong>Proposal #{p.id}</strong>
            <span style={{
              background: STATUS_COLOR[p.status],
              color: '#fff',
              borderRadius: 4,
              padding: '2px 8px',
              fontSize: 12,
            }}>{p.status}</span>
          </div>
          <div style={{ fontSize: 13, color: '#57606a', marginTop: 4 }}>
            IPFS: {p.descriptionHash} · Proposer: {p.proposer}
          </div>
          <div style={{ marginTop: 8, fontSize: 14 }}>
            ✅ For: {p.votesFor.toString()} &nbsp;|&nbsp;
            ❌ Against: {p.votesAgainst.toString()} &nbsp;|&nbsp;
            🛡 Vetoes: {p.vetoCount}/5
          </div>
        </div>
      ))}
    </div>
  );
}
