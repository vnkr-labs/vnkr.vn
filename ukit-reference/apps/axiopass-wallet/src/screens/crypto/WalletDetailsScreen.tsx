/**
 * WalletDetailsScreen — Single asset deep-dive
 * Balance, price chart, period selector, recent transactions, Send/Receive/Swap/Buy CTAs
 */
'use client';
import React, { useState } from 'react';
import styles from './WalletDetailsScreen.module.css';
import { Icon } from '@axioledger/axio-design-system';

export interface WalletDetailsScreenProps {
  symbol?: string;
  name?: string;
  balance?: string;
  usdValue?: string;
  change24h?: string;
  changePositive?: boolean;
  onBack?: () => void;
  onSend?: () => void;
  onReceive?: () => void;
  onSwap?: () => void;
}

type Period = '1D' | '1W' | '1M' | '3M' | '1Y';

const COIN_COLOR: Record<string, string> = {
  BTC:  '#f7931a',
  ETH:  '#627eea',
  AXQ:  '#49dbc8',
  USDT: '#26a17b',
};

const SAMPLE_TXS = [
  { id: 1, type: 'receive', label: 'Received from alice.axq',  amount: '+0.012 BTC', usd: '+$780',   date: 'Today, 14:32' },
  { id: 2, type: 'send',    label: 'Sent to bob.axq',           amount: '-0.005 BTC', usd: '-$325',   date: 'Yesterday' },
  { id: 3, type: 'swap',    label: 'Swapped BTC → ETH',         amount: '-0.008 BTC', usd: '-$520',   date: 'Mon' },
  { id: 4, type: 'receive', label: 'Received from exchange',    amount: '+0.1 BTC',   usd: '+$6,500', date: 'Last week' },
];

const PERIOD_CHART: Record<Period, number[]> = {
  '1D': [42, 44, 43, 47, 46, 48, 50, 49, 52, 54, 53, 56],
  '1W': [38, 40, 42, 39, 44, 46, 54],
  '1M': [30, 34, 32, 38, 36, 42, 44, 48, 46, 52, 50, 56, 54, 58, 60, 56, 62, 65, 63, 68, 66, 70, 68, 72, 70, 74, 72, 76, 74, 78],
  '3M': [25, 30, 28, 35, 33, 40, 38, 45, 42, 50, 48, 55],
  '1Y': [20, 25, 22, 30, 28, 35, 32, 40, 38, 45, 50, 54],
};

function MiniChart({ data, color }: { data: number[]; color: string }) {
  const max = Math.max(...data);
  const min = Math.min(...data);
  const range = max - min || 1;
  const w = 340;
  const h = 100;
  const pts = data.map((v, i) => {
    const x = (i / (data.length - 1)) * w;
    const y = h - ((v - min) / range) * (h - 10) - 5;
    return `${x},${y}`;
  });
  const areaPath = `M${pts[0]} L${pts.join(' L')} L${w},${h} L0,${h} Z`;

  return (
    <svg viewBox={`0 0 ${w} ${h}`} width="100%" height={h} aria-hidden="true" preserveAspectRatio="none">
      <defs>
        <linearGradient id="chartGrad" x1="0" y1="0" x2="0" y2="1">
          <stop offset="0%" stopColor={color} stopOpacity="0.18" />
          <stop offset="100%" stopColor={color} stopOpacity="0" />
        </linearGradient>
      </defs>
      <path d={areaPath} fill="url(#chartGrad)" />
      <polyline points={pts.join(' ')} fill="none" stroke={color} strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" />
    </svg>
  );
}

export function WalletDetailsScreen({
  symbol = 'BTC',
  name = 'Bitcoin',
  balance = '0.234 BTC',
  usdValue = '$15,420.00',
  change24h = '+3.2%',
  changePositive = true,
  onBack,
  onSend,
  onReceive,
  onSwap,
}: WalletDetailsScreenProps) {
  const [period, setPeriod] = useState<Period>('1W');
  const coinColor = COIN_COLOR[symbol] ?? '#49dbc8';

  return (
    <div className={styles.screen}>
      {/* Top bar */}
      <div className={styles.header}>
        <button className={styles.backBtn} onClick={onBack} aria-label="Go back" type="button">
          <Icon name="arrow-square" size={22} color="#1f2328" aria-hidden />
        </button>
        <p className={styles.headerTitle}>{name}</p>
        <Icon name="more-circle" size={22} color="#57606a" aria-hidden />
      </div>

      {/* Hero */}
      <div className={styles.heroSection}>
        <div className={styles.coinBadge} style={{ background: coinColor }}>
          {symbol.slice(0, 2)}
        </div>
        <h1 className={styles.balance}>{balance}</h1>
        <p className={styles.usdValue}>{usdValue}</p>
        <p className={[styles.change, changePositive ? styles.changePos : styles.changeNeg].join(' ')}>
          {changePositive ? '▲' : '▼'} {change24h} today
        </p>
      </div>

      {/* Action buttons */}
      <div className={styles.actions}>
        {[
          { label: 'Send',    icon: 'send'               as const, action: onSend    },
          { label: 'Receive', icon: 'directbox-receive'  as const, action: onReceive },
          { label: 'Swap',    icon: 'convertshape'       as const, action: onSwap    },
          { label: 'Buy',     icon: 'buy-crypto'         as const, action: undefined },
        ].map((a) => (
          <button
            key={a.label}
            className={styles.actionBtn}
            onClick={a.action}
            disabled={!a.action}
            aria-label={a.label}
            type="button"
          >
            <Icon name={a.icon} size={22} color={coinColor} aria-hidden />
            {a.label}
          </button>
        ))}
      </div>

      {/* Price chart */}
      <div style={{ padding: '0 20px' }}>
        <div className={styles.chartArea}>
          <div className={styles.chartPlaceholder}>
            <MiniChart data={PERIOD_CHART[period]} color={coinColor} />
          </div>
          <div className={styles.periods} role="group" aria-label="Chart period">
            {(['1D', '1W', '1M', '3M', '1Y'] as Period[]).map((p) => (
              <button
                key={p}
                className={[styles.periodBtn, period === p ? styles.periodBtnActive : ''].filter(Boolean).join(' ')}
                style={period === p ? { color: coinColor, borderBottomColor: coinColor } : {}}
                onClick={() => setPeriod(p)}
                aria-pressed={period === p}
                type="button"
              >
                {p}
              </button>
            ))}
          </div>
        </div>
      </div>

      {/* Recent transactions */}
      <div className={styles.section}>
        <p className={styles.sectionTitle}>Recent</p>
        {SAMPLE_TXS.map((tx) => (
          <div key={tx.id} className={styles.txRow}>
            <div className={[
              styles.txIconWrap,
              tx.type === 'receive' ? styles.txIconReceive : tx.type === 'send' ? styles.txIconSend : styles.txIconSwap,
            ].join(' ')}>
              <Icon
                name={tx.type === 'receive' ? 'directbox-receive' : tx.type === 'send' ? 'send' : 'convertshape'}
                size={18}
                color={tx.type === 'receive' ? '#00d68f' : tx.type === 'send' ? '#ff3d71' : '#0057c2'}
                aria-hidden
              />
            </div>
            <div style={{ flex: 1 }}>
              <p className={styles.txLabel}>{tx.label}</p>
              <p className={styles.txDate}>{tx.date}</p>
            </div>
            <div style={{ textAlign: 'right' }}>
              <p className={[styles.txAmount, tx.type === 'receive' ? styles.txAmountPos : styles.txAmountNeg].join(' ')}>
                {tx.amount}
              </p>
              <p className={styles.txUsd}>{tx.usd}</p>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}
