/**
 * Zone 3 — Home & Dashboard screens
 * HomeScreen | CashbackScreen | AnalyticsScreen
 */
'use client';
import React, { useState } from 'react';
import { Icon } from '@axioledger/axio-design-system';

/* ─────────────────────────────────────────────────────────
   HomeScreen
   ───────────────────────────────────────────────────────── */
export interface HomeScreenProps {
  name?: string;
  balanceFiat?: number;
  currency?: string;
  onSend?: () => void;
  onReceive?: () => void;
  onTopUp?: () => void;
  onSwap?: () => void;
  onCard?: () => void;
  onCashback?: () => void;
}

const QUICK_ACTIONS: { id: string; label: string; svg: React.ReactNode }[] = [
  { id: 'send',     label: 'Send',     svg: <svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round"/></svg> },
  { id: 'receive',  label: 'Receive',  svg: <svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M12 3v12m0 0l-4-4m4 4l4-4M3 17v2a2 2 0 002 2h14a2 2 0 002-2v-2" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round"/></svg> },
  { id: 'topup',    label: 'Top Up',   svg: <svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round"/></svg> },
  { id: 'swap',     label: 'Swap',     svg: <svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M7 16V4m0 0L3 8m4-4l4 4M17 8v12m0 0l4-4m-4 4l-4-4" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round"/></svg> },
  { id: 'card',     label: 'Card',     svg: <svg width="22" height="22" viewBox="0 0 24 24" fill="none"><rect x="2" y="5" width="20" height="14" rx="2" stroke="currentColor" strokeWidth="1.6"/><path d="M2 10h20" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round"/><rect x="5" y="14" width="4" height="2" rx="0.5" fill="currentColor"/></svg> },
  { id: 'cashback', label: 'Cashback', svg: <svg width="22" height="22" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="currentColor" strokeWidth="1.6"/><path d="M12 7v1m0 8v1m-3-5.5h4a1.5 1.5 0 010 3H9m3-3a1.5 1.5 0 000-3H9v3" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round"/></svg> },
];

const TX_ICONS: Record<string, React.ReactNode> = {
  netflix: <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><rect x="4" y="2" width="16" height="20" rx="2" stroke="currentColor" strokeWidth="1.5"/><path d="M8 6v12M16 6v12" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round"/></svg>,
  salary:  <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M21 7H3a1 1 0 00-1 1v8a1 1 0 001 1h18a1 1 0 001-1V8a1 1 0 00-1-1z" stroke="currentColor" strokeWidth="1.5"/><circle cx="12" cy="12" r="2" stroke="currentColor" strokeWidth="1.5"/></svg>,
  btc:     <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="currentColor" strokeWidth="1.5"/><path d="M9 8h4.5a2 2 0 010 4H9m0 0h5a2 2 0 010 4H9M9 8V6m0 10v2" stroke="currentColor" strokeWidth="1.4" strokeLinecap="round"/></svg>,
};

const SAMPLE_TXS = [
  { id: 1, label: 'Netflix',      amount: -15.99, currency: 'USD', date: 'Today',     iconKey: 'netflix' },
  { id: 2, label: 'Salary',       amount: 3200,   currency: 'USD', date: 'Yesterday', iconKey: 'salary'  },
  { id: 3, label: 'BTC Purchase', amount: -500,   currency: 'USD', date: 'Mon',       iconKey: 'btc'     },
];

export function HomeScreen({
  name = 'Alex',
  balanceFiat = 12_480.50,
  currency = 'USD',
  onSend, onReceive, onTopUp, onSwap, onCard, onCashback,
}: HomeScreenProps) {
  const [hidden, setHidden] = useState(false);
  const actionMap: Record<string, (() => void) | undefined> = {
    send: onSend, receive: onReceive, topup: onTopUp,
    swap: onSwap, card: onCard, cashback: onCashback,
  };

  return (
    <div style={S.screen}>
      {/* Greeting */}
      <div style={S.greeting}>
        <div>
          <p style={S.greetSub}>Good morning,</p>
          <h1 style={S.greetName}>{name} 👋</h1>
        </div>
        <button style={S.avatarBtn} aria-label="Profile">
          <span style={S.avatar}>{name[0]}</span>
        </button>
      </div>

      {/* Balance card */}
      <div style={S.balanceCard}>
        <p style={S.balanceLabel}>Total Balance</p>
        <div style={S.balanceRow}>
          <h2 style={S.balanceAmt}>
            {hidden ? '•••••' : `${currency} ${balanceFiat.toLocaleString('en-US', { minimumFractionDigits: 2 })}`}
          </h2>
          <button
            style={S.hideBtn}
            onClick={() => setHidden((h) => !h)}
            aria-label={hidden ? 'Show balance' : 'Hide balance'}
          >
            {hidden
              ? <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" strokeWidth="1.6"/><circle cx="12" cy="12" r="3" stroke="currentColor" strokeWidth="1.6"/></svg>
              : <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19M1 1l22 22" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round"/></svg>
            }
          </button>
        </div>
        <p style={S.balanceChange}>▲ 2.4% this month</p>
      </div>

      {/* Quick Actions */}
      <div style={S.quickGrid}>
        {QUICK_ACTIONS.map((a) => (
          <button key={a.id} style={S.quickItem} onClick={actionMap[a.id]} aria-label={a.label}>
            <span style={{ ...S.quickIcon, color: '#1f2328' }}>{a.svg}</span>
            <span style={S.quickLabel}>{a.label}</span>
          </button>
        ))}
      </div>

      {/* Recent transactions */}
      <div style={S.section}>
        <div style={S.sectionHeader}>
          <span style={S.sectionTitle}>Recent</span>
          <button style={S.seeAll}>See all</button>
        </div>
        {SAMPLE_TXS.map((tx) => (
          <div key={tx.id} style={S.txRow}>
            <span style={{ ...S.txIcon, color: '#57606a' }}>{TX_ICONS[tx.iconKey]}</span>
            <div style={{ flex: 1 }}>
              <p style={S.txLabel}>{tx.label}</p>
              <p style={S.txDate}>{tx.date}</p>
            </div>
            <p style={{ ...S.txAmt, color: tx.amount < 0 ? '#ff3d71' : '#00d68f' }}>
              {tx.amount > 0 ? '+' : ''}{tx.currency} {Math.abs(tx.amount).toFixed(2)}
            </p>
          </div>
        ))}
      </div>
    </div>
  );
}

/* ─────────────────────────────────────────────────────────
   CashbackScreen
   ───────────────────────────────────────────────────────── */
export function CashbackScreen() {
  return (
    <div style={S.screen}>
      <h1 style={S.pageTitle}>Cashback & Rewards</h1>
      <div style={S.balanceCard}>
        <p style={S.balanceLabel}>Available Cashback</p>
        <h2 style={S.balanceAmt}>USD 48.20</h2>
        <p style={S.balanceChange}>From 23 transactions this month</p>
      </div>
      <div style={S.tierRow}>
        <span style={{ ...S.tierBadge, display: 'inline-flex', alignItems: 'center', gap: 5 }}>
          <Icon name="medal-star" size={16} color="#ffd672" aria-hidden /> Gold Member
        </span>
        <p style={S.tierProgress}>340 / 500 pts to Platinum</p>
      </div>
      <div style={{ height: 8, background: '#e4e9f2', borderRadius: 4 }}>
        <div style={{ width: '68%', height: '100%', background: '#49dbc8', borderRadius: 4 }} />
      </div>
      <button style={S.primaryBtn}>Redeem Cashback →</button>
      <button style={S.ghostBtn}>Browse Voucher Store</button>
    </div>
  );
}

/* ─────────────────────────────────────────────────────────
   AnalyticsScreen
   ───────────────────────────────────────────────────────── */
const SPEND_CATS = [
  { label: 'Food & Drink',  pct: 32, color: '#fc7339' },
  { label: 'Shopping',      pct: 24, color: '#af96fb' },
  { label: 'Transport',     pct: 18, color: '#49dbc8' },
  { label: 'Entertainment', pct: 14, color: '#ffd672' },
  { label: 'Other',         pct: 12, color: '#8f9bb3' },
];

export function AnalyticsScreen() {
  return (
    <div style={S.screen}>
      <h1 style={S.pageTitle}>Spending Summary</h1>
      <p style={S.subtitle}>November 2025</p>
      <div style={S.balanceCard}>
        <p style={S.balanceLabel}>Total Spent</p>
        <h2 style={S.balanceAmt}>USD 1,842.60</h2>
      </div>
      {SPEND_CATS.map((c) => (
        <div key={c.label} style={S.catRow}>
          <div style={{ ...S.catDot, background: c.color }} />
          <span style={S.catLabel}>{c.label}</span>
          <div style={S.catBarWrap}>
            <div style={{ ...S.catBar, width: `${c.pct}%`, background: c.color }} />
          </div>
          <span style={S.catPct}>{c.pct}%</span>
        </div>
      ))}
    </div>
  );
}

/* ─────────────────────────────────────────────────────────
   Shared inline styles (no CSS module needed for scaffolds)
   ───────────────────────────────────────────────────────── */
const S: Record<string, React.CSSProperties> = {
  screen:       { minHeight: '100svh', padding: '16px 20px 80px', background: '#fff', fontFamily: 'system-ui,sans-serif', overflowY: 'auto' },
  greeting:     { display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', marginBottom: 20 },
  greetSub:     { fontSize: 13, color: '#57606a', margin: 0 },
  greetName:    { fontSize: 22, fontWeight: 700, margin: '4px 0 0', color: '#1f2328' },
  avatarBtn:    { background: 'none', border: 'none', cursor: 'pointer', padding: 0 },
  avatar:       { display: 'flex', alignItems: 'center', justifyContent: 'center', width: 40, height: 40, borderRadius: '50%', background: '#49dbc8', color: '#000', fontWeight: 700, fontSize: 18 },
  balanceCard:  { background: '#0d1117', borderRadius: 16, padding: '20px 24px', marginBottom: 20 },
  balanceLabel: { fontSize: 12, color: 'rgba(255,255,255,0.5)', margin: '0 0 6px' },
  balanceRow:   { display: 'flex', alignItems: 'center', gap: 8 },
  balanceAmt:   { fontSize: 28, fontWeight: 700, color: '#fff', margin: 0, flex: 1 },
  hideBtn:      { background: 'none', border: 'none', cursor: 'pointer', fontSize: 18 },
  balanceChange:{ fontSize: 12, color: '#49dbc8', margin: '6px 0 0' },
  quickGrid:    { display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: 12, marginBottom: 24 },
  quickItem:    { display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 6, padding: '14px 8px', background: '#f7f8fa', borderRadius: 12, border: 'none', cursor: 'pointer' },
  quickIcon:    { fontSize: 22 },
  quickLabel:   { fontSize: 12, fontWeight: 600, color: '#1f2328' },
  section:      { display: 'flex', flexDirection: 'column', gap: 0 },
  sectionHeader:{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 12 },
  sectionTitle: { fontSize: 16, fontWeight: 700, color: '#1f2328' },
  seeAll:       { background: 'none', border: 'none', color: '#0057c2', fontSize: 13, fontWeight: 600, cursor: 'pointer' },
  txRow:        { display: 'flex', alignItems: 'center', gap: 12, padding: '10px 0', borderBottom: '1px solid #f0f2f4' },
  txIcon:       { width: 40, height: 40, background: '#f7f8fa', borderRadius: '50%', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 18, flexShrink: 0 },
  txLabel:      { fontSize: 14, fontWeight: 600, color: '#1f2328', margin: 0 },
  txDate:       { fontSize: 12, color: '#57606a', margin: 0 },
  txAmt:        { fontSize: 14, fontWeight: 700, margin: 0 },
  pageTitle:    { fontSize: 22, fontWeight: 700, color: '#1f2328', margin: '0 0 4px' },
  subtitle:     { fontSize: 13, color: '#57606a', margin: '0 0 20px' },
  tierRow:      { display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 8 },
  tierBadge:    { fontSize: 14, fontWeight: 700, color: '#1f2328' },
  tierProgress: { fontSize: 12, color: '#57606a', margin: 0 },
  primaryBtn:   { width: '100%', height: 56, background: '#0d1117', color: '#fff', border: 'none', borderRadius: 9999, fontSize: 16, fontWeight: 700, cursor: 'pointer', marginTop: 20 },
  ghostBtn:     { width: '100%', height: 48, background: 'none', color: '#57606a', border: '1.5px solid #e4e9f2', borderRadius: 9999, fontSize: 14, fontWeight: 600, cursor: 'pointer', marginTop: 10 },
  catRow:       { display: 'flex', alignItems: 'center', gap: 10, marginBottom: 14 },
  catDot:       { width: 10, height: 10, borderRadius: '50%', flexShrink: 0 },
  catLabel:     { fontSize: 13, color: '#1f2328', minWidth: 110 },
  catBarWrap:   { flex: 1, height: 8, background: '#f0f2f4', borderRadius: 4, overflow: 'hidden' },
  catBar:       { height: '100%', borderRadius: 4 },
  catPct:       { fontSize: 13, fontWeight: 600, color: '#57606a', minWidth: 36, textAlign: 'right' },
};
