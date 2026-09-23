/**
 * Zone 6 — Transfer & Payments screens
 * TransferHubScreen | RecipientScreen | AmountScreen
 * HomeServicesScreen | TxReviewScreen | TxAuthScreen | ReceiptScreen
 */
'use client';
import React, { useState } from 'react';
import { Icon } from '@axioledger/axio-design-system';
import type { IconName } from '@axioledger/axio-design-system';

const S: Record<string, React.CSSProperties> = {
  screen:     { minHeight: '100svh', padding: '16px 20px 80px', background: '#fff', fontFamily: 'system-ui,sans-serif', overflowY: 'auto' },
  pageTitle:  { fontSize: 22, fontWeight: 700, color: '#1f2328', margin: '0 0 16px' },
  backBtn:    { background: 'none', border: 'none', fontSize: 20, cursor: 'pointer', marginBottom: 12, padding: 0, color: '#1f2328' },
  primaryBtn: { width: '100%', height: 56, background: '#0d1117', color: '#fff', border: 'none', borderRadius: 9999, fontSize: 16, fontWeight: 700, cursor: 'pointer', marginTop: 16 },
  card:       { background: '#f7f8fa', borderRadius: 14, padding: 16, marginBottom: 12 },
  input:      { width: '100%', height: 52, border: '1.5px solid #e4e9f2', borderRadius: 12, padding: '0 16px', fontSize: 15, background: '#fff', boxSizing: 'border-box', marginBottom: 12 },
  label:      { fontSize: 13, fontWeight: 600, color: '#57606a', marginBottom: 6, display: 'block' },
  row:        { display: 'flex', alignItems: 'center', gap: 12, padding: '12px 0', borderBottom: '1px solid #f0f2f4' },
};

/* ─────────────────────────────────────────────────────────
   TransferHubScreen
   ───────────────────────────────────────────────────────── */
const TRANSFER_TYPES: { id: string; label: string; desc: string; icon: IconName; iconColor: string; fee: string; eta: string }[] = [
  { id: 'internal', label: 'Internal',      desc: 'Between Axiopass accounts', icon: 'flash-circle',    iconColor: '#49dbc8', fee: 'Free',     eta: 'Instant' },
  { id: 'domestic', label: 'Bank Transfer', desc: 'Local bank account',        icon: 'bank',            iconColor: '#0057c2', fee: 'Free',     eta: '1-2 hours' },
  { id: 'intl',     label: 'International', desc: 'SWIFT / SEPA transfer',     icon: 'global',          iconColor: '#af96fb', fee: '$2–$15',   eta: '1-3 days' },
  { id: 'crypto',   label: 'Crypto Send',   desc: 'Any blockchain wallet',      icon: 'bitcoin-(btc)',   iconColor: '#f7931a', fee: 'Gas only', eta: '~1 min' },
];

export function TransferHubScreen({ onSelect }: { onSelect?: (type: string) => void }) {
  return (
    <div style={S.screen}>
      <h1 style={S.pageTitle}>Send Money</h1>
      {TRANSFER_TYPES.map((t) => (
        <div key={t.id} style={{ ...S.card, cursor: 'pointer', display: 'flex', alignItems: 'center', gap: 16 }} role="button" tabIndex={0} onClick={() => onSelect?.(t.id)}>
          <Icon name={t.icon} size={32} color={t.iconColor} aria-hidden />
          <div style={{ flex: 1 }}>
            <p style={{ fontSize: 15, fontWeight: 700, margin: 0, color: '#1f2328' }}>{t.label}</p>
            <p style={{ fontSize: 12, color: '#57606a', margin: '2px 0 0' }}>{t.desc}</p>
          </div>
          <div style={{ textAlign: 'right' }}>
            <p style={{ fontSize: 12, fontWeight: 600, margin: 0, color: '#1f2328' }}>{t.fee}</p>
            <p style={{ fontSize: 11, color: '#8f9bb3', margin: 0 }}>{t.eta}</p>
          </div>
          <span style={{ color: '#8f9bb3' }}>›</span>
        </div>
      ))}
    </div>
  );
}

/* ─────────────────────────────────────────────────────────
   AmountScreen
   ───────────────────────────────────────────────────────── */
export function AmountScreen({ onBack, onNext }: { onBack?: () => void; onNext?: (amt: string) => void }) {
  const [amount, setAmount] = useState('');
  const [note, setNote] = useState('');
  const PRESETS = ['100,000', '500,000', '1,000,000', '2,000,000'];

  return (
    <div style={S.screen}>
      <button style={S.backBtn} onClick={onBack}>←</button>
      <h1 style={S.pageTitle}>Enter Amount</h1>

      <div style={{ textAlign: 'center', margin: '32px 0 24px' }}>
        <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 8 }}>
          <span style={{ fontSize: 24, color: '#57606a', fontWeight: 700 }}>VND</span>
          <span style={{ fontSize: 48, fontWeight: 700, color: amount ? '#1f2328' : '#c5cee0', minWidth: 120 }}>{amount || '0'}</span>
        </div>
        <p style={{ fontSize: 13, color: '#57606a' }}>≈ USD {amount ? (parseInt(amount.replace(/,/g, '')) / 24_000).toFixed(2) : '0.00'}</p>
      </div>

      <div style={{ display: 'flex', flexWrap: 'wrap', gap: 8, marginBottom: 20 }}>
        {PRESETS.map((p) => (
          <button key={p} style={{ flex: '1 1 calc(50% - 4px)', height: 44, background: '#f7f8fa', border: 'none', borderRadius: 12, fontSize: 14, fontWeight: 600, cursor: 'pointer' }} onClick={() => setAmount(p)}>{p}</button>
        ))}
      </div>

      <label style={S.label}>Note (optional)</label>
      <input style={S.input} placeholder="e.g. Rent payment" value={note} onChange={(e) => setNote(e.target.value)} aria-label="Transfer note" />

      <button style={S.primaryBtn} disabled={!amount} onClick={() => onNext?.(amount)}>Continue →</button>
    </div>
  );
}

/* ─────────────────────────────────────────────────────────
   HomeServicesScreen
   ───────────────────────────────────────────────────────── */
const SERVICES: { id: string; label: string; icon: IconName }[] = [
  { id: 'electric', label: 'Electricity', icon: 'electricity'   },
  { id: 'water',    label: 'Water',       icon: 'glass-1'       },
  { id: 'internet', label: 'Internet',    icon: 'wifi'          },
  { id: 'tv',       label: 'Cable TV',    icon: 'monitor'       },
];

export function HomeServicesScreen({ onBack }: { onBack?: () => void }) {
  const [service, setService] = useState('electric');
  const [customerId, setCustomerId] = useState('');
  const [bill, setBill] = useState<{ amount: string; dueDate: string } | null>(null);

  return (
    <div style={S.screen}>
      <button style={S.backBtn} onClick={onBack}>←</button>
      <h1 style={S.pageTitle}>Bill Payment</h1>

      <div style={{ display: 'grid', gridTemplateColumns: 'repeat(4, 1fr)', gap: 10, marginBottom: 24 }}>
        {SERVICES.map((s) => (
          <button key={s.id} style={{ padding: '14px 8px', display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 6, background: service === s.id ? '#f2f8ff' : '#f7f8fa', border: `1.5px solid ${service === s.id ? '#0095ff' : 'transparent'}`, borderRadius: 12, cursor: 'pointer' }} onClick={() => setService(s.id)} aria-pressed={service === s.id}>
            <Icon name={s.icon} size={24} color={service === s.id ? '#0057c2' : '#57606a'} aria-hidden />
            <span style={{ fontSize: 11, fontWeight: 600, color: service === s.id ? '#0057c2' : '#57606a' }}>{s.label}</span>
          </button>
        ))}
      </div>

      <label style={S.label}>Customer ID</label>
      <input style={S.input} placeholder="Enter your customer number" value={customerId} onChange={(e) => setCustomerId(e.target.value)} aria-label="Customer ID" />

      <button style={{ ...S.primaryBtn, background: '#0095ff', marginBottom: 16 }} onClick={() => setBill({ amount: 'VND 320,000', dueDate: '30 Nov 2025' })}>
        Look Up Bill
      </button>

      {bill && (
        <div style={{ ...S.card, background: '#f0fff5', border: '1px solid #ccfce3' }}>
          <p style={{ fontSize: 14, color: '#57606a', margin: '0 0 6px' }}>Bill found:</p>
          <p style={{ fontSize: 22, fontWeight: 700, margin: 0, color: '#1f2328' }}>{bill.amount}</p>
          <p style={{ fontSize: 13, color: '#57606a', margin: '4px 0 16px' }}>Due: {bill.dueDate}</p>
          <button style={{ ...S.primaryBtn, marginTop: 0, background: '#00d68f' }}>Pay Now →</button>
        </div>
      )}
    </div>
  );
}

/* ─────────────────────────────────────────────────────────
   TxReviewScreen
   ───────────────────────────────────────────────────────── */
export function TxReviewScreen({ onBack, onConfirm }: { onBack?: () => void; onConfirm?: () => void }) {
  return (
    <div style={S.screen}>
      <button style={S.backBtn} onClick={onBack}>←</button>
      <h1 style={S.pageTitle}>Review Transaction</h1>

      <div style={S.card}>
        {[
          { label: 'To',       value: 'Bob (bob.axq)' },
          { label: 'Amount',   value: 'VND 1,000,000' },
          { label: 'Network',  value: 'Internal' },
          { label: 'Fee',      value: 'Free' },
          { label: 'Arrives',  value: 'Instantly' },
          { label: 'Note',     value: 'Rent payment' },
        ].map((r) => (
          <div key={r.label} style={{ display: 'flex', justifyContent: 'space-between', padding: '10px 0', borderBottom: '1px solid #f0f2f4' }}>
            <span style={{ fontSize: 14, color: '#57606a' }}>{r.label}</span>
            <span style={{ fontSize: 14, fontWeight: 600, color: '#1f2328' }}>{r.value}</span>
          </div>
        ))}
      </div>

      <button style={S.primaryBtn} onClick={onConfirm}>Confirm & Authenticate →</button>
    </div>
  );
}

/* ─────────────────────────────────────────────────────────
   TxAuthScreen
   ───────────────────────────────────────────────────────── */
export function TxAuthScreen({ onBack, onComplete }: { onBack?: () => void; onComplete?: () => void }) {
  const [method, setMethod] = useState<'pin' | 'faceid' | 'sms'>('faceid');

  return (
    <div style={S.screen}>
      <button style={S.backBtn} onClick={onBack}>←</button>
      <h1 style={S.pageTitle}>Authenticate</h1>
      <p style={{ fontSize: 14, color: '#57606a', marginBottom: 24 }}>VND 1,000,000 to bob.axq</p>

      <div style={{ display: 'flex', flexDirection: 'column', gap: 12, marginBottom: 24 }}>
        {([
          { id: 'faceid', icon: 'finger-scan'     as IconName, label: 'Face ID / Touch ID', desc: 'Recommended' },
          { id: 'pin',    icon: 'password-check'  as IconName, label: 'PIN Code',            desc: '' },
          { id: 'sms',    icon: 'sms'             as IconName, label: 'SMS OTP',             desc: 'Slower' },
        ] as const).map((m) => (
          <div key={m.id} style={{ ...S.card, border: `1.5px solid ${method === m.id ? '#0095ff' : '#e4e9f2'}`, cursor: 'pointer', display: 'flex', alignItems: 'center', gap: 12 }} role="button" tabIndex={0} onClick={() => setMethod(m.id as typeof method)} aria-pressed={method === m.id}>
            <div style={{ flex: 1, display: 'flex', alignItems: 'center', gap: 10 }}>
              <Icon name={m.icon} size={22} color={method === m.id ? '#0095ff' : '#57606a'} aria-hidden />
              <p style={{ fontSize: 15, fontWeight: 600, margin: 0 }}>{m.label}</p>
              {m.desc && <p style={{ fontSize: 12, color: '#57606a', margin: 0 }}>{m.desc}</p>}
            </div>
            {method === m.id && <Icon name="tick-circle" size={18} color="#0095ff" aria-hidden />}
          </div>
        ))}
      </div>

      {/* Auth action */}
      <button style={{ ...S.primaryBtn, display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 8 }} onClick={onComplete}>
        <Icon
          name={method === 'faceid' ? 'finger-scan' : method === 'pin' ? 'password-check' : 'sms'}
          size={20} color="#fff" aria-hidden
        />
        {method === 'faceid' ? 'Authenticate with Face ID' : method === 'pin' ? 'Enter PIN' : 'Send OTP'}
      </button>
    </div>
  );
}

/* ─────────────────────────────────────────────────────────
   ReceiptScreen
   ───────────────────────────────────────────────────────── */
export function ReceiptScreen({ onNewTransfer, onHome }: { onNewTransfer?: () => void; onHome?: () => void }) {
  return (
    <div style={{ ...S.screen, display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center', textAlign: 'center', gap: 16 }}>
      <div style={{ width: 80, height: 80, borderRadius: '50%', background: '#f0fff5', border: '3px solid #00d68f', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
        <Icon name="tick-circle" size={40} color="#00d68f" aria-label="Transfer successful" />
      </div>
      <h1 style={{ fontSize: 24, fontWeight: 700, color: '#1f2328', margin: 0 }}>Transfer Successful!</h1>
      <p style={{ fontSize: 14, color: '#57606a', margin: 0 }}>VND 1,000,000 sent to bob.axq</p>

      <div style={{ ...S.card, width: '100%', maxWidth: 320, textAlign: 'left' }}>
        {[
          ['Transaction ID', 'AXQ-2025-001234'],
          ['Date & Time',    'Nov 25, 2025 · 14:32'],
          ['Status', 'Completed'],
        ].map(([k, v]) => (
          <div key={k} style={{ display: 'flex', justifyContent: 'space-between', padding: '8px 0', borderBottom: '1px solid #f0f2f4' }}>
            <span style={{ fontSize: 13, color: '#57606a' }}>{k}</span>
            <span style={{ fontSize: 13, fontWeight: 600, color: '#1f2328' }}>{v}</span>
          </div>
        ))}
      </div>

      <div style={{ display: 'flex', gap: 10, width: '100%', maxWidth: 320 }}>
        <button style={{ flex: 1, height: 48, border: '1.5px solid #e4e9f2', borderRadius: 9999, background: 'none', fontSize: 14, fontWeight: 600, cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 6 }}>
          <Icon name="share" size={16} color="#57606a" aria-hidden /> Share
        </button>
        <button style={{ flex: 1, height: 48, border: '1.5px solid #e4e9f2', borderRadius: 9999, background: 'none', fontSize: 14, fontWeight: 600, cursor: 'pointer', display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 6 }}>
          <Icon name="save-2" size={16} color="#57606a" aria-hidden /> Save
        </button>
      </div>

      <button style={{ ...S.primaryBtn, maxWidth: 320 }} onClick={onHome}>Back to Home</button>
      <button style={{ background: 'none', border: 'none', color: '#0057c2', fontSize: 14, fontWeight: 600, cursor: 'pointer' }} onClick={onNewTransfer}>New Transfer</button>
    </div>
  );
}
