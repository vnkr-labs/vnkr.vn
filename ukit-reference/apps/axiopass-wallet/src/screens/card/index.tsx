/**
 * Zone 4 — Card Management screens
 * CardCenterScreen | IssueCardScreen | AddBalanceScreen
 * CardSettingsScreen | RevealCardScreen
 */
'use client';
import React, { useState } from 'react';
import { Icon, CardVisual } from '@axioledger/axio-design-system';
import type { IconName } from '@axioledger/axio-design-system';

/* ─────────────────────────────────────────────────────────
   CardCenterScreen
   ───────────────────────────────────────────────────────── */
const SAMPLE_CARDS = [
  { id: 'v1', last4: '4291', name: 'Alex Nguyen', expiry: '09/28', variant: 'virtual' as const, scheme: 'axq' as const, skin: 'dark' as const, frozen: false },
];

export function CardCenterScreen({ onIssue }: { onIssue?: () => void }) {
  const [flipped, setFlipped] = useState(false);

  return (
    <div style={S.screen}>
      <h1 style={S.pageTitle}>My Cards</h1>

      {SAMPLE_CARDS.map((card) => (
        <div key={card.id} style={{ marginBottom: 12 }}>
          <CardVisual
            name={card.name}
            last4={card.last4}
            expiry={card.expiry}
            variant={card.variant}
            scheme={card.scheme}
            skin={card.skin}
            frozen={card.frozen}
            flipped={flipped}
            onFlip={() => setFlipped((f) => !f)}
          />
          <div style={S.cardActions}>
            <button style={{ ...S.cardActionBtn, display: 'inline-flex', alignItems: 'center', justifyContent: 'center', gap: 5 }}>
              <Icon name="slash" size={14} color="#57606a" aria-hidden /> Freeze
            </button>
            <button style={{ ...S.cardActionBtn, display: 'inline-flex', alignItems: 'center', justifyContent: 'center', gap: 5 }}>
              <Icon name="eye" size={14} color="#57606a" aria-hidden /> Reveal
            </button>
            <button style={{ ...S.cardActionBtn, display: 'inline-flex', alignItems: 'center', justifyContent: 'center', gap: 5 }}>
              <Icon name="setting-2" size={14} color="#57606a" aria-hidden /> Settings
            </button>
          </div>
        </div>
      ))}

      <button style={S.addCardBtn} onClick={onIssue}>＋ Add New Card</button>
    </div>
  );
}

/* ─────────────────────────────────────────────────────────
   IssueCardScreen
   ───────────────────────────────────────────────────────── */
export function IssueCardScreen({ onBack, onConfirm }: { onBack?: () => void; onConfirm?: () => void }) {
  const [type, setType] = useState<'virtual' | 'physical'>('virtual');
  const [skinIdx, setSkinIdx] = useState(0);
  const SKINS: { color: string; name: 'dark' | 'teal' | 'purple' | 'green' }[] = [
    { color: '#0d1117', name: 'dark' },
    { color: '#134e4a', name: 'teal' },
    { color: '#4c1d95', name: 'purple' },
    { color: '#14532d', name: 'green' },
  ];

  return (
    <div style={S.screen}>
      <button style={S.backBtn} onClick={onBack}>←</button>
      <h1 style={S.pageTitle}>Issue Card</h1>

      {/* Type toggle */}
      <div style={S.segmentedControl}>
        {(['virtual', 'physical'] as const).map((t) => (
          <button
            key={t}
            style={{ ...S.segmentBtn, ...(type === t ? S.segmentBtnActive : {}) }}
            onClick={() => setType(t)}
          >
            <span style={{ display: 'inline-flex', alignItems: 'center', gap: 5 }}>
              <Icon name={t === 'virtual' ? 'monitor' : 'box'} size={16} color={type === t ? '#1f2328' : '#57606a'} aria-hidden />
              {t === 'virtual' ? 'Virtual' : 'Physical'}
            </span>
          </button>
        ))}
      </div>

      {/* Preview */}
      <div style={{ marginBottom: 16 }}>
        <CardVisual
          name="YOUR NAME"
          last4="••••"
          expiry="MM/YY"
          variant={type}
          scheme="axq"
          skin={SKINS[skinIdx].name}
        />
      </div>

      {/* Skin picker */}
      <div style={{ display: 'flex', gap: 12, marginBottom: 24 }}>
        {SKINS.map((s, i) => (
          <button
            key={i}
            style={{ width: 36, height: 36, borderRadius: '50%', background: s.color, border: skinIdx === i ? '3px solid #49dbc8' : '3px solid transparent', cursor: 'pointer' }}
            onClick={() => setSkinIdx(i)}
            aria-label={`Card skin: ${s.name}`}
            aria-pressed={skinIdx === i}
          />
        ))}
      </div>

      {type === 'physical' && (
        <div style={S.formField}>
          <label style={S.formLabel}>Delivery Address</label>
          <input style={S.formInput} placeholder="123 Main St, City, Country" aria-label="Delivery address" />
        </div>
      )}

      <button style={S.primaryBtn} onClick={onConfirm}>
        {type === 'virtual' ? 'Create Virtual Card' : 'Order Physical Card'}
      </button>
    </div>
  );
}

/* ─────────────────────────────────────────────────────────
   AddBalanceScreen
   ───────────────────────────────────────────────────────── */
const TOP_UP_METHODS: { id: string; label: string; icon: IconName; iconColor: string; fee: string }[] = [
  { id: 'bank',   label: 'Bank Transfer',       icon: 'bank',          iconColor: '#0057c2', fee: 'Free' },
  { id: 'card',   label: 'Debit / Credit Card', icon: 'card-coin',     iconColor: '#49dbc8', fee: '1.5%' },
  { id: 'crypto', label: 'Crypto Deposit',       icon: 'bitcoin-(btc)', iconColor: '#f7931a', fee: 'Gas only' },
];

export function AddBalanceScreen({ onBack, onConfirm }: { onBack?: () => void; onConfirm?: () => void }) {
  const [method, setMethod] = useState('bank');
  const [amount, setAmount] = useState('');
  const PRESETS = ['100', '500', '1000', '2000'];

  return (
    <div style={S.screen}>
      <button style={S.backBtn} onClick={onBack}>←</button>
      <h1 style={S.pageTitle}>Add Balance</h1>

      {/* Amount input */}
      <div style={S.amountWrap}>
        <span style={S.amountCurrency}>USD</span>
        <input
          style={S.amountInput}
          type="number"
          inputMode="decimal"
          placeholder="0.00"
          value={amount}
          onChange={(e) => setAmount(e.target.value)}
          aria-label="Amount to add"
        />
      </div>
      <div style={{ display: 'flex', gap: 8, marginBottom: 24 }}>
        {PRESETS.map((p) => (
          <button key={p} style={S.presetBtn} onClick={() => setAmount(p)}>{p}</button>
        ))}
      </div>

      {/* Payment method */}
      <p style={{ fontSize: 14, fontWeight: 700, marginBottom: 12, color: '#1f2328' }}>Payment Method</p>
      {TOP_UP_METHODS.map((m) => (
        <div
          key={m.id}
          style={{ ...S.methodRow, border: method === m.id ? '1.5px solid #0095ff' : '1.5px solid #e4e9f2', background: method === m.id ? '#f2f8ff' : '#fff' }}
          role="button"
          tabIndex={0}
          onClick={() => setMethod(m.id)}
          aria-pressed={method === m.id}
        >
          <Icon name={m.icon} size={24} color={method === m.id ? m.iconColor : '#57606a'} aria-hidden />
          <div style={{ flex: 1 }}>
            <p style={{ fontSize: 14, fontWeight: 600, margin: 0, color: '#1f2328' }}>{m.label}</p>
            <p style={{ fontSize: 12, color: '#57606a', margin: 0 }}>Fee: {m.fee}</p>
          </div>
          {method === m.id && <Icon name="tick-circle" size={18} color="#0095ff" aria-hidden />}
        </div>
      ))}

      <button style={{ ...S.primaryBtn, marginTop: 24 }} disabled={!amount} onClick={onConfirm}>
        Continue → Review
      </button>
    </div>
  );
}

/* ─────────────────────────────────────────────────────────
   CardSettingsScreen
   ───────────────────────────────────────────────────────── */
export function CardSettingsScreen({ onBack }: { onBack?: () => void }) {
  const [frozen, setFrozen] = useState(false);
  const [online, setOnline] = useState(true);
  const [intl, setIntl] = useState(false);

  return (
    <div style={S.screen}>
      <button style={S.backBtn} onClick={onBack}>←</button>
      <h1 style={S.pageTitle}>Card Settings</h1>
      <p style={{ fontSize: 13, color: '#57606a', marginBottom: 20 }}>Virtual •••• 4291</p>

      {[
        { label: 'Freeze Card',            desc: 'Block all transactions instantly',      val: frozen, set: setFrozen },
        { label: 'Online Purchases',       desc: 'Allow card on websites & apps',         val: online, set: setOnline },
        { label: 'International Payments', desc: 'Allow transactions outside your region', val: intl,   set: setIntl },
      ].map((row) => (
        <div key={row.label} style={S.settingRow}>
          <div>
            <p style={S.settingLabel}>{row.label}</p>
            <p style={S.settingDesc}>{row.desc}</p>
          </div>
          <button
            style={{ ...S.toggle, background: row.val ? '#49dbc8' : '#e4e9f2' }}
            onClick={() => row.set((v: boolean) => !v)}
            role="switch"
            aria-checked={row.val}
            aria-label={row.label}
          >
            <div style={{ ...S.toggleKnob, transform: row.val ? 'translateX(20px)' : 'translateX(2px)' }} />
          </button>
        </div>
      ))}

      <div style={{ height: 1, background: '#e4e9f2', margin: '16px 0' }} />

      {([
        { icon: 'eye'          as IconName, label: 'Reveal Card Details (Face ID)', action: () => {},              danger: false },
        { icon: 'key'          as IconName, label: 'Change ATM PIN',                action: () => {},              danger: false },
        { icon: 'chart-1'      as IconName, label: 'Spending Limits',               action: () => {},              danger: false },
        { icon: 'shield-cross' as IconName, label: 'Report Lost / Stolen',          action: () => {},              danger: true  },
      ] as const).map((item) => (
        <button
          key={item.label}
          style={{ ...S.settingLink, color: item.danger ? '#ff3d71' : '#1f2328' }}
          onClick={item.action}
        >
          <span style={{ display: 'inline-flex', alignItems: 'center', gap: 8 }}>
            <Icon name={item.icon} size={18} color={item.danger ? '#ff3d71' : '#57606a'} aria-hidden />
            {item.label}
          </span>
          <span style={{ color: '#8f9bb3' }}>›</span>
        </button>
      ))}
    </div>
  );
}

const S: Record<string, React.CSSProperties> = {
  screen:        { minHeight: '100svh', padding: '16px 20px 80px', background: '#fff', fontFamily: 'system-ui,sans-serif', overflowY: 'auto' },
  pageTitle:     { fontSize: 22, fontWeight: 700, color: '#1f2328', margin: '0 0 16px' },
  backBtn:       { background: 'none', border: 'none', fontSize: 20, cursor: 'pointer', marginBottom: 12, padding: 0, color: '#1f2328' },
  cardVisual:    { borderRadius: 16, padding: '20px 24px', minHeight: 180, color: '#fff', position: 'relative', cursor: 'pointer', marginBottom: 8 },
  cardPAN:       { fontSize: 18, letterSpacing: '0.12em', fontFamily: 'monospace', margin: '40px 0 16px' },
  cardName:      { fontSize: 13, fontWeight: 700, letterSpacing: '0.08em', margin: 0 },
  cardExpiry:    { fontSize: 12, color: 'rgba(255,255,255,0.6)', margin: '4px 0 0' },
  cardVariant:   { position: 'absolute', top: 16, right: 20, fontSize: 10, fontWeight: 700, letterSpacing: '0.12em', color: 'rgba(255,255,255,0.5)' },
  frozenBadge:   { position: 'absolute', top: '50%', left: '50%', transform: 'translate(-50%,-50%)', fontSize: 14, fontWeight: 700, letterSpacing: '0.2em', background: 'rgba(0,0,0,0.5)', padding: '6px 16px', borderRadius: 8 },
  magStripe:     { height: 40, background: 'rgba(0,0,0,0.6)', marginBottom: 16 },
  cvvLabel:      { fontSize: 13, color: 'rgba(255,255,255,0.7)', margin: 0 },
  cardActions:   { display: 'flex', gap: 8 },
  cardActionBtn: { flex: 1, height: 40, background: '#f7f8fa', border: 'none', borderRadius: 9999, fontSize: 12, fontWeight: 600, cursor: 'pointer' },
  addCardBtn:    { width: '100%', height: 56, border: '2px dashed #e4e9f2', background: 'none', borderRadius: 12, fontSize: 15, fontWeight: 600, color: '#57606a', cursor: 'pointer' },
  segmentedControl: { display: 'flex', background: '#f7f8fa', borderRadius: 12, padding: 4, marginBottom: 20 },
  segmentBtn:    { flex: 1, height: 40, border: 'none', borderRadius: 8, background: 'none', fontSize: 14, fontWeight: 600, color: '#57606a', cursor: 'pointer' },
  segmentBtnActive: { background: '#fff', color: '#1f2328', boxShadow: '0 1px 3px rgba(0,0,0,0.08)' },
  formField:     { marginBottom: 16 },
  formLabel:     { fontSize: 13, fontWeight: 600, color: '#57606a', display: 'block', marginBottom: 6 },
  formInput:     { width: '100%', height: 52, border: '1.5px solid #e4e9f2', borderRadius: 12, padding: '0 16px', fontSize: 15, background: '#fff', boxSizing: 'border-box' },
  amountWrap:    { display: 'flex', alignItems: 'center', gap: 8, marginBottom: 16, borderBottom: '2px solid #0d1117', paddingBottom: 8 },
  amountCurrency:{ fontSize: 18, fontWeight: 700, color: '#57606a' },
  amountInput:   { flex: 1, border: 'none', fontSize: 40, fontWeight: 700, color: '#1f2328', outline: 'none', background: 'none', minWidth: 0 },
  presetBtn:     { flex: 1, height: 40, background: '#f7f8fa', border: 'none', borderRadius: 9999, fontSize: 13, fontWeight: 600, cursor: 'pointer' },
  methodRow:     { display: 'flex', alignItems: 'center', gap: 12, padding: '14px 16px', borderRadius: 12, marginBottom: 10, cursor: 'pointer' },
  primaryBtn:    { width: '100%', height: 56, background: '#0d1117', color: '#fff', border: 'none', borderRadius: 9999, fontSize: 16, fontWeight: 700, cursor: 'pointer' },
  settingRow:    { display: 'flex', alignItems: 'center', justifyContent: 'space-between', padding: '14px 0', borderBottom: '1px solid #f0f2f4' },
  settingLabel:  { fontSize: 15, fontWeight: 600, color: '#1f2328', margin: 0 },
  settingDesc:   { fontSize: 12, color: '#57606a', margin: '2px 0 0' },
  toggle:        { width: 48, height: 28, borderRadius: 9999, border: 'none', cursor: 'pointer', position: 'relative', transition: 'background 0.2s', flexShrink: 0 },
  toggleKnob:    { position: 'absolute', top: 4, width: 20, height: 20, borderRadius: '50%', background: '#fff', transition: 'transform 0.2s', boxShadow: '0 1px 3px rgba(0,0,0,0.2)' },
  settingLink:   { display: 'flex', justifyContent: 'space-between', alignItems: 'center', width: '100%', padding: '14px 0', background: 'none', border: 'none', fontSize: 15, fontWeight: 600, cursor: 'pointer', borderBottom: '1px solid #f0f2f4' },
};
