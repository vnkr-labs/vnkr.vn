/**
 * Zone 5 — Crypto & Web3 screens
 * CryptoPortfolioScreen | SwapScreen | WalletScreen
 * ReceiveScreen | SendScreen | AddressBookScreen
 */
'use client';
import React, { useState } from 'react';
import { QRDisplay, Icon, GasFeeSelector } from '@axioledger/axio-design-system';
import type { GasOption, GasSpeed } from '@axioledger/axio-design-system';

const S: Record<string, React.CSSProperties> = {
  screen:      { minHeight: '100svh', padding: '16px 20px 80px', background: '#fff', fontFamily: 'system-ui,sans-serif', overflowY: 'auto' },
  pageTitle:   { fontSize: 22, fontWeight: 700, color: '#1f2328', margin: '0 0 4px' },
  subtitle:    { fontSize: 13, color: '#57606a', margin: '0 0 20px' },
  backBtn:     { background: 'none', border: 'none', fontSize: 20, cursor: 'pointer', marginBottom: 12, padding: 0, color: '#1f2328' },
  primaryBtn:  { width: '100%', height: 56, background: '#0d1117', color: '#fff', border: 'none', borderRadius: 9999, fontSize: 16, fontWeight: 700, cursor: 'pointer', marginTop: 16 },
  ghostBtn:    { width: '100%', height: 48, background: 'none', color: '#57606a', border: '1.5px solid #e4e9f2', borderRadius: 9999, fontSize: 14, fontWeight: 600, cursor: 'pointer', marginTop: 10 },
  card:        { background: '#f7f8fa', borderRadius: 14, padding: '16px', marginBottom: 12 },
  row:         { display: 'flex', alignItems: 'center', gap: 12, padding: '12px 0', borderBottom: '1px solid #f0f2f4' },
  input:       { width: '100%', height: 52, border: '1.5px solid #e4e9f2', borderRadius: 12, padding: '0 16px', fontSize: 15, background: '#fff', boxSizing: 'border-box', marginBottom: 12 },
  label:       { fontSize: 13, fontWeight: 600, color: '#57606a', marginBottom: 6, display: 'block' },
};

/* ─────────────────────────────────────────────────────────
   CryptoPortfolioScreen
   ───────────────────────────────────────────────────────── */
const ASSETS = [
  { symbol: 'BTC',  name: 'Bitcoin',  balance: '0.234',  usd: '$15,420', change: '+3.2%',  color: '#f7931a', pos: true },
  { symbol: 'ETH',  name: 'Ethereum', balance: '2.18',   usd: '$5,890',  change: '+1.8%',  color: '#627eea', pos: true },
  { symbol: 'AXQ',  name: 'AXQ',      balance: '10,000', usd: '$2,100',  change: '+12.4%', color: '#49dbc8', pos: true },
  { symbol: 'USDT', name: 'Tether',   balance: '1,200',  usd: '$1,200',  change: '0.0%',   color: '#26a17b', pos: false },
];

export function CryptoPortfolioScreen({ onSelectAsset }: { onSelectAsset?: (symbol: string) => void } = {}) {
  return (
    <div style={S.screen}>
      <h1 style={S.pageTitle}>Portfolio</h1>
      <div style={{ ...S.card, background: '#0d1117', color: '#fff' }}>
        <p style={{ fontSize: 13, color: 'rgba(255,255,255,0.5)', margin: 0 }}>Total Value</p>
        <h2 style={{ fontSize: 32, fontWeight: 700, margin: '4px 0' }}>$24,610</h2>
        <p style={{ fontSize: 13, color: '#49dbc8', margin: 0 }}>▲ +4.7% today</p>
      </div>
      {ASSETS.map((a) => (
        <div
          key={a.symbol}
          style={{ ...S.row, cursor: 'pointer' }}
          role="button"
          tabIndex={0}
          onClick={() => onSelectAsset?.(a.symbol)}
          aria-label={`View ${a.name} wallet details`}
        >
          <div style={{ width: 40, height: 40, borderRadius: '50%', background: a.color, display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#fff', fontWeight: 700, fontSize: 13, flexShrink: 0 }}>{a.symbol.slice(0,2)}</div>
          <div style={{ flex: 1 }}>
            <p style={{ fontSize: 14, fontWeight: 600, color: '#1f2328', margin: 0 }}>{a.name}</p>
            <p style={{ fontSize: 12, color: '#57606a', margin: 0 }}>{a.balance} {a.symbol}</p>
          </div>
          <div style={{ textAlign: 'right' }}>
            <p style={{ fontSize: 14, fontWeight: 700, color: '#1f2328', margin: 0 }}>{a.usd}</p>
            <p style={{ fontSize: 12, fontWeight: 600, color: a.pos ? '#00d68f' : '#57606a', margin: 0 }}>{a.change}</p>
          </div>
          <Icon name="arrow-square" size={16} color="#8f9bb3" aria-hidden />
        </div>
      ))}
    </div>
  );
}

/* ─────────────────────────────────────────────────────────
   SwapScreen
   ───────────────────────────────────────────────────────── */
const GAS_OPTIONS: GasOption[] = [
  { speed: 'slow',    label: 'Eco',     eta: '~5 min',  gwei: 12,  usdCost: '~$0.80' },
  { speed: 'average', label: 'Normal',  eta: '~1 min',  gwei: 20,  usdCost: '~$2.40' },
  { speed: 'fast',    label: 'Fast',    eta: '<30 sec', gwei: 35,  usdCost: '~$4.10' },
];

export function SwapScreen({ onBack }: { onBack?: () => void }) {
  const [fromAmt, setFromAmt] = useState('');
  const [gasSpeed, setGasSpeed] = useState<GasSpeed>('average');
  const [slippage, setSlippage] = useState(0.5);

  return (
    <div style={S.screen}>
      <button style={S.backBtn} onClick={onBack}>←</button>
      <h1 style={S.pageTitle}>Swap</h1>

      <div style={S.card}>
        <label style={S.label}>From</label>
        <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
          <div style={{ background: '#f7931a', borderRadius: 9999, padding: '4px 10px', color: '#fff', fontSize: 13, fontWeight: 700 }}>BTC</div>
          <input style={{ ...S.input, marginBottom: 0, flex: 1 }} type="number" placeholder="0.0" value={fromAmt} onChange={(e) => setFromAmt(e.target.value)} aria-label="Amount from" />
          <button style={{ background: 'none', border: 'none', color: '#0057c2', fontSize: 13, fontWeight: 600, cursor: 'pointer' }}>MAX</button>
        </div>
      </div>

      <div style={{ textAlign: 'center', margin: '4px 0', display: 'flex', justifyContent: 'center' }}>
        <Icon name="convertshape" size={22} color="#57606a" aria-hidden />
      </div>

      <div style={S.card}>
        <label style={S.label}>To</label>
        <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
          <div style={{ background: '#627eea', borderRadius: 9999, padding: '4px 10px', color: '#fff', fontSize: 13, fontWeight: 700 }}>ETH</div>
          <p style={{ flex: 1, fontSize: 22, fontWeight: 700, color: '#57606a', margin: 0 }}>≈ {fromAmt ? (parseFloat(fromAmt) * 13.2).toFixed(4) : '0.0'}</p>
        </div>
      </div>

      <GasFeeSelector
        options={GAS_OPTIONS}
        value={gasSpeed}
        onChange={setGasSpeed}
        feeToken="ETH"
        showSlippage
        slippage={slippage}
        onSlippageChange={setSlippage}
      />

      <button style={S.primaryBtn} disabled={!fromAmt}>Review Swap</button>
    </div>
  );
}

/* ─────────────────────────────────────────────────────────
   ReceiveScreen — QR + Network + Copy
   ───────────────────────────────────────────────────────── */
const NETWORKS = ['ERC-20', 'BEP-20', 'TRC-20', 'Solana'];
const WALLET_ADDRESS = '0x1A2b3C4d5E6f7A8B9C0D1E2F3A4B5C6D7E8F9A0B';

export function ReceiveScreen({ onBack }: { onBack?: () => void }) {
  const [network, setNetwork] = useState('ERC-20');

  return (
    <div style={S.screen}>
      <button style={S.backBtn} onClick={onBack}>←</button>
      <h1 style={S.pageTitle}>Receive</h1>

      {/* Network picker */}
      <div style={{ display: 'flex', gap: 8, marginBottom: 20, overflowX: 'auto' }}>
        {NETWORKS.map((n) => (
          <button key={n} style={{ padding: '6px 14px', border: `1.5px solid ${network === n ? '#0095ff' : '#e4e9f2'}`, borderRadius: 9999, background: network === n ? '#f2f8ff' : 'none', fontSize: 13, fontWeight: 600, cursor: 'pointer', color: network === n ? '#0057c2' : '#57606a', whiteSpace: 'nowrap' }} onClick={() => setNetwork(n)} aria-pressed={network === n}>{n}</button>
        ))}
      </div>

      <div style={{ marginBottom: 16 }}>
        <QRDisplay
          value={WALLET_ADDRESS}
          network={network}
          label={`Your ${network} Address`}
          sublabel={`${WALLET_ADDRESS.slice(0, 10)}…${WALLET_ADDRESS.slice(-8)}`}
          showCopy
          showShare
        />
      </div>
    </div>
  );
}

/* ─────────────────────────────────────────────────────────
   SendScreen
   ───────────────────────────────────────────────────────── */
const SEND_GAS_OPTIONS: GasOption[] = [
  { speed: 'slow',    label: 'Eco',    eta: '~5 min',  gwei: 12, usdCost: '~$0.80' },
  { speed: 'average', label: 'Normal', eta: '~1 min',  gwei: 20, usdCost: '~$2.10' },
  { speed: 'fast',    label: 'Fast',   eta: '<30 sec', gwei: 35, usdCost: '~$4.10' },
];

export function SendScreen({ onBack, onReview }: { onBack?: () => void; onReview?: () => void }) {
  const [to, setTo] = useState('');
  const [amount, setAmount] = useState('');
  const [network, setNetwork] = useState('ERC-20');
  const [gasSpeed, setGasSpeed] = useState<GasSpeed>('average');

  const isANS = to.endsWith('.axq') || to.endsWith('.vrq');
  const selectedGas = SEND_GAS_OPTIONS.find((o) => o.speed === gasSpeed)!;

  return (
    <div style={S.screen}>
      <button style={S.backBtn} onClick={onBack} type="button" aria-label="Go back">←</button>
      <h1 style={S.pageTitle}>Send</h1>

      <label style={S.label}>Recipient Address or ANS Domain</label>
      <div style={{ position: 'relative', marginBottom: 12 }}>
        <input style={S.input} placeholder="0x… or alice.axq" value={to} onChange={(e) => setTo(e.target.value)} aria-label="Recipient address" />
        {isANS && (
          <span style={{ position: 'absolute', right: 12, top: '50%', transform: 'translateY(-50%)', fontSize: 12, color: '#00997a', fontWeight: 700 }}>
            ✓ ANS
          </span>
        )}
      </div>

      <label style={S.label}>Network</label>
      <select style={{ ...S.input, marginBottom: 12 }} value={network} onChange={(e) => setNetwork(e.target.value)} aria-label="Network">
        {NETWORKS.map((n) => <option key={n} value={n}>{n}</option>)}
      </select>

      <label style={S.label}>Amount (ETH)</label>
      <input style={S.input} type="number" inputMode="decimal" placeholder="0.0" value={amount} onChange={(e) => setAmount(e.target.value)} aria-label="Send amount" />

      {/* Gas fee selector — shown once address + amount are filled */}
      {to && amount && (
        <GasFeeSelector
          options={SEND_GAS_OPTIONS}
          value={gasSpeed}
          onChange={setGasSpeed}
        />
      )}

      <button style={S.primaryBtn} disabled={!to || !amount} onClick={onReview} type="button">
        Review Transaction →{to && amount ? ` (${selectedGas.usdCost} gas)` : ''}
      </button>
    </div>
  );
}

/* ─────────────────────────────────────────────────────────
   AddressBookScreen
   ───────────────────────────────────────────────────────── */
const SAVED_ADDRESSES = [
  { id: 1, name: 'Bob.axq',    addr: '0xBob…',  tlp: 'safe',    badge: '#00d68f' },
  { id: 2, name: 'Exchange',   addr: '0xExch…', tlp: 'caution', badge: '#ffaa00' },
  { id: 3, name: 'Unknown',    addr: '0xUnk…',  tlp: 'blocked', badge: '#ff3d71' },
];

export function AddressBookScreen({ onBack, onSelect }: { onBack?: () => void; onSelect?: (addr: string) => void }) {
  const [query, setQuery] = useState('');
  const filtered = SAVED_ADDRESSES.filter((a) => a.name.toLowerCase().includes(query.toLowerCase()));

  return (
    <div style={S.screen}>
      <button style={S.backBtn} onClick={onBack}>←</button>
      <h1 style={S.pageTitle}>Address Book</h1>
      <div style={{ position: 'relative' }}>
        <span style={{ position: 'absolute', left: 14, top: '50%', transform: 'translateY(-50%)', pointerEvents: 'none' }}>
          <Icon name="search-normal" size={16} color="#8f9bb3" aria-hidden />
        </span>
        <input style={{ ...S.input, paddingLeft: 40 }} placeholder="Search contacts…" value={query} onChange={(e) => setQuery(e.target.value)} aria-label="Search address book" />
      </div>
      {filtered.map((a) => (
        <div key={a.id} style={S.row}>
          <div style={{ width: 40, height: 40, borderRadius: '50%', background: a.badge + '22', border: `2px solid ${a.badge}`, display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 13, fontWeight: 700, color: a.badge, flexShrink: 0 }}>{a.name[0]}</div>
          <div style={{ flex: 1 }}>
            <p style={{ fontSize: 14, fontWeight: 600, margin: 0 }}>{a.name}</p>
            <p style={{ fontSize: 11, fontFamily: 'monospace', color: '#57606a', margin: 0 }}>{a.addr}</p>
          </div>
          <span style={{ fontSize: 10, fontWeight: 700, padding: '3px 8px', borderRadius: 9999, background: a.badge + '22', color: a.badge }}>{a.tlp.toUpperCase()}</span>
          <button style={{ background: 'none', border: 'none', color: '#0057c2', fontSize: 13, fontWeight: 600, cursor: 'pointer' }} onClick={() => onSelect?.(a.addr)}>Send →</button>
        </div>
      ))}
      <button style={S.ghostBtn}>＋ Add New Address</button>
    </div>
  );
}
