/**
 * GuardianCouncil.tsx — 5-seat Guardian Council veto panel
 */
'use client';

import React, { useState } from 'react';

const MOCK_GUARDIANS = [
  { seat: 1, address: '3Abc...AA1', hasVetoed: false },
  { seat: 2, address: '5Def...BB2', hasVetoed: true },
  { seat: 3, address: '7Ghi...CC3', hasVetoed: false },
  { seat: 4, address: '9Jkl...DD4', hasVetoed: false },
  { seat: 5, address: '1Mno...EE5', hasVetoed: false },
];

export function GuardianCouncil() {
  const [guardians, setGuardians] = useState(MOCK_GUARDIANS);
  const vetoCount = guardians.filter(g => g.hasVetoed).length;
  const isVetoed = vetoCount >= 4;

  const castVeto = (seat: number) => {
    setGuardians(prev =>
      prev.map(g => g.seat === seat ? { ...g, hasVetoed: true } : g)
    );
  };

  return (
    <div>
      <p style={{ fontSize: 14, marginBottom: '0.75rem' }}>
        Veto count: <strong style={{ color: isVetoed ? '#dc2626' : '#1f2328' }}>
          {vetoCount}/5
        </strong>
        {isVetoed && <span style={{ color: '#dc2626', marginLeft: 8 }}>⛔ PROPOSAL VETOED</span>}
      </p>
      <div style={{ display: 'grid', gridTemplateColumns: 'repeat(5, 1fr)', gap: '0.5rem' }}>
        {guardians.map(g => (
          <div key={g.seat} style={{
            border: `2px solid ${g.hasVetoed ? '#dc2626' : '#e5e7eb'}`,
            borderRadius: 8,
            padding: '0.75rem',
            textAlign: 'center',
            background: g.hasVetoed ? '#fef2f2' : '#f7f8fa',
          }}>
            <div style={{ fontWeight: 600, fontSize: 13 }}>Seat #{g.seat}</div>
            <div style={{ fontSize: 11, color: '#57606a', marginBottom: 8 }}>{g.address}</div>
            {g.hasVetoed ? (
              <span style={{ color: '#dc2626', fontSize: 12 }}>⛔ VETOED</span>
            ) : (
              <button
                onClick={() => castVeto(g.seat)}
                style={{
                  fontSize: 11, padding: '3px 8px',
                  background: '#dc2626', color: '#fff',
                  border: 'none', borderRadius: 4, cursor: 'pointer',
                }}
              >
                Cast Veto
              </button>
            )}
          </div>
        ))}
      </div>
    </div>
  );
}
