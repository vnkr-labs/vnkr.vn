import React from 'react';
import styles from './GasFeeSelector.module.css';
import { Icon } from '../Icon';

export type GasSpeed = 'slow' | 'average' | 'fast';

export interface GasOption {
  speed: GasSpeed;
  /** Display label */
  label: string;
  /** Estimated time string */
  eta: string;
  /** Gwei value */
  gwei: number;
  /** USD cost estimate */
  usdCost: string;
}

export interface GasFeeSelectorProps {
  options: GasOption[];
  value: GasSpeed;
  onChange: (speed: GasSpeed) => void;
  /** Token symbol for fee display */
  feeToken?: string;
  disabled?: boolean;
  /** Show advanced slippage row */
  showSlippage?: boolean;
  slippage?: number;
  onSlippageChange?: (value: number) => void;
}

const SPEED_ICON: Record<GasSpeed, React.ReactNode> = {
  slow:    <Icon name="timer-pause"   size={18} color="currentColor" aria-hidden />,
  average: <Icon name="timer"         size={18} color="currentColor" aria-hidden />,
  fast:    <Icon name="flash-circle"  size={18} color="currentColor" aria-hidden />,
};

const SPEED_COLOR: Record<GasSpeed, string> = {
  slow:    'var(--color-status-info-default)',
  average: 'var(--color-status-warning-default)',
  fast:    'var(--primitive-brand-orange)',
};

const SLIPPAGE_PRESETS = [0.1, 0.5, 1.0, 3.0];

/**
 * GasFeeSelector
 *
 * Slow / Average / Fast gas tier picker + optional slippage control.
 * Used on Send (crypto), Swap, and Staking confirmation screens.
 */
export function GasFeeSelector({
  options,
  value,
  onChange,
  feeToken = 'ETH',
  disabled = false,
  showSlippage = false,
  slippage = 0.5,
  onSlippageChange,
}: GasFeeSelectorProps) {
  return (
    <div
      className={styles.root}
      data-disabled={disabled || undefined}
      aria-disabled={disabled}
    >
      <div className={styles.header}>
        <span className={styles.title}>Network Fee</span>
        <span className={styles.token}>{feeToken}</span>
      </div>

      {/* Speed options */}
      <div className={styles.grid} role="radiogroup" aria-label="Gas fee speed">
        {options.map((opt) => {
          const selected = value === opt.speed;
          return (
            <button
              key={opt.speed}
              type="button"
              role="radio"
              aria-checked={selected}
              className={[styles.option, selected ? styles.selected : ''].filter(Boolean).join(' ')}
              style={selected ? { borderColor: SPEED_COLOR[opt.speed] } as React.CSSProperties : undefined}
              onClick={() => !disabled && onChange(opt.speed)}
              disabled={disabled}
              aria-label={`${opt.label} — ~${opt.eta}, ${opt.usdCost}`}
            >
              <span className={styles.optionIcon} aria-hidden="true" style={{ color: SPEED_COLOR[opt.speed] }}>
                {SPEED_ICON[opt.speed]}
              </span>
              <span className={styles.optionLabel}>{opt.label}</span>
              <span className={styles.optionEta}>{opt.eta}</span>
              <span className={styles.optionGwei}>{opt.gwei} Gwei</span>
              <span
                className={[styles.optionCost, selected ? styles.costHighlight : ''].filter(Boolean).join(' ')}
                style={selected ? { color: SPEED_COLOR[opt.speed] } as React.CSSProperties : undefined}
              >
                {opt.usdCost}
              </span>
            </button>
          );
        })}
      </div>

      {/* Slippage tolerance */}
      {showSlippage && (
        <div className={styles.slippage}>
          <div className={styles.slippageHeader}>
            <span className={styles.slippageLabel}>Slippage Tolerance</span>
            <span className={styles.slippageValue}>{slippage}%</span>
          </div>
          <div className={styles.slippagePresets} role="group" aria-label="Slippage preset">
            {SLIPPAGE_PRESETS.map((preset) => (
              <button
                key={preset}
                type="button"
                className={[
                  styles.slippageBtn,
                  slippage === preset ? styles.slippageBtnActive : '',
                ]
                  .filter(Boolean)
                  .join(' ')}
                onClick={() => onSlippageChange?.(preset)}
                disabled={disabled}
                aria-pressed={slippage === preset}
                aria-label={`Set slippage to ${preset}%`}
              >
                {preset}%
              </button>
            ))}
          </div>
          {slippage > 1 && (
            <p className={styles.slippageWarning} role="alert" style={{ display: 'flex', alignItems: 'center', gap: 6 }}>
              <Icon name="warning-2" size={14} color="currentColor" aria-hidden />
              High slippage — your trade may result in an unfavorable price
            </p>
          )}
        </div>
      )}
    </div>
  );
}
