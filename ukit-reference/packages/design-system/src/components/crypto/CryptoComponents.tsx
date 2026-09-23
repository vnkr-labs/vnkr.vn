import React from 'react';
import { Badge } from '../Badge/Badge';
import styles from './CryptoComponents.module.css';

/* ── CryptoAssetCard ──────────────────────────────────── */
export interface CryptoAssetCardProps {
  coinName: string;
  ticker: string;
  price: string;
  change: number;        // percent, signed
  balance?: string;
  iconUrl?: string;
  onClick?: () => void;
}

export function CryptoAssetCard({
  coinName, ticker, price, change, balance, iconUrl, onClick,
}: CryptoAssetCardProps) {
  const positive = change >= 0;
  const Tag = onClick ? 'button' : 'div';

  return (
    <Tag
      className={styles.assetCard}
      onClick={onClick}
      type={onClick ? 'button' : undefined}
      aria-label={onClick ? `${coinName} — ${price}` : undefined}
    >
      <div className={styles.assetLeft}>
        {iconUrl ? (
          <img src={iconUrl} alt={ticker} className={styles.coinIcon} />
        ) : (
          <span className={styles.coinInitial}>{ticker.slice(0, 2)}</span>
        )}
        <div>
          <div className={styles.coinName}>{coinName}</div>
          <div className={styles.coinTicker}>{ticker}</div>
        </div>
      </div>
      <div className={styles.assetRight}>
        <div className={styles.price}>{price}</div>
        <div className={styles.change} data-positive={positive || undefined} data-negative={!positive || undefined}>
          {positive ? '▲' : '▼'} {Math.abs(change).toFixed(2)}%
        </div>
        {balance && <div className={styles.balance}>{balance}</div>}
      </div>
    </Tag>
  );
}

/* ── TransactionItem ──────────────────────────────────── */
export type TxType = 'send' | 'receive' | 'swap' | 'stake' | 'unstake';
export type TxStatus = 'pending' | 'confirmed' | 'failed';

export interface TransactionItemProps {
  type: TxType;
  amount: string;
  ticker: string;
  status: TxStatus;
  timestamp: string;
  counterparty?: string;
  fiatValue?: string;
  onClick?: () => void;
}

const TX_ICONS: Record<TxType, string> = {
  send: '↑', receive: '↓', swap: '⇄', stake: '⬡', unstake: '⬡',
};

export function TransactionItem({
  type, amount, ticker, status, timestamp, counterparty, fiatValue, onClick,
}: TransactionItemProps) {
  return (
    <div
      className={styles.txItem}
      onClick={onClick}
      role={onClick ? 'button' : undefined}
      tabIndex={onClick ? 0 : undefined}
      onKeyDown={onClick ? (e) => { if (e.key === 'Enter') onClick(); } : undefined}
    >
      <span className={styles.txIcon} data-type={type} aria-hidden="true">
        {TX_ICONS[type]}
      </span>
      <div className={styles.txMiddle}>
        <div className={styles.txType}>{type.charAt(0).toUpperCase() + type.slice(1)}</div>
        {counterparty && <div className={styles.txParty}>{counterparty}</div>}
        <div className={styles.txTime}>{timestamp}</div>
      </div>
      <div className={styles.txRight}>
        <div className={styles.txAmount} data-type={type}>
          {type === 'receive' ? '+' : '-'}{amount} {ticker}
        </div>
        {fiatValue && <div className={styles.txFiat}>{fiatValue}</div>}
        <Badge variant={status === 'confirmed' ? 'success' : status === 'failed' ? 'error' : 'default'} size="sm">
          {status}
        </Badge>
      </div>
    </div>
  );
}

/* ── BalanceDisplay ───────────────────────────────────── */
export interface BalanceDisplayProps {
  fiatAmount: string;
  cryptoEquiv?: string;
  ticker?: string;
  change?: number;
  changePeriod?: string;
  actions?: React.ReactNode;
}

export function BalanceDisplay({
  fiatAmount, cryptoEquiv, ticker, change, changePeriod = '24h', actions,
}: BalanceDisplayProps) {
  const positive = change !== undefined ? change >= 0 : null;
  return (
    <div className={styles.balance}>
      <div className={styles.balanceLabel}>Total Balance</div>
      <div className={styles.balanceFiat}>{fiatAmount}</div>
      {cryptoEquiv && (
        <div className={styles.balanceCrypto}>{cryptoEquiv} {ticker}</div>
      )}
      {change !== undefined && (
        <div className={styles.balanceChange} data-positive={positive || undefined} data-negative={!positive || undefined}>
          {positive ? '▲' : '▼'} {Math.abs(change).toFixed(2)}% ({changePeriod})
        </div>
      )}
      {actions && <div className={styles.balanceActions}>{actions}</div>}
    </div>
  );
}

/* ── PriceTicker ──────────────────────────────────────── */
export interface PriceTickerProps {
  ticker: string;
  value: string;
  change: number;
  size?: 'sm' | 'md' | 'lg';
}

export function PriceTicker({ ticker, value, change, size = 'md' }: PriceTickerProps) {
  const positive = change >= 0;
  return (
    <div className={styles.ticker} data-size={size}>
      <span className={styles.tickerSymbol}>{ticker}</span>
      <span className={styles.tickerValue}>{value}</span>
      <span className={styles.tickerChange} data-positive={positive || undefined} data-negative={!positive || undefined}>
        {positive ? '+' : ''}{change.toFixed(2)}%
      </span>
    </div>
  );
}
