/**
 * PINScreen — Create / Confirm / Enter PIN
 * Uses PINPad component from design system
 */
'use client';
import React, { useState } from 'react';
import styles from './PINScreen.module.css';
import { PINPad } from '@axioledger/axio-design-system';

export type PINScreenMode = 'create' | 'confirm' | 'enter' | 'change' | 'reset';

export interface PINScreenProps {
  mode?: PINScreenMode;
  /** Required for 'confirm' mode — must match this */
  targetPIN?: string;
  onComplete?: (pin: string) => void;
  onForgot?: () => void;
  onBiometric?: () => void;
  onBack?: () => void;
  /** Max retries before lockout */
  maxRetries?: number;
}

const MODE_CONFIG: Record<PINScreenMode, { label: string; hint?: string }> = {
  create:  { label: 'Create your PIN',  hint: 'Choose a 6-digit PIN you can remember' },
  confirm: { label: 'Confirm your PIN', hint: 'Enter the same PIN again' },
  enter:   { label: 'Enter your PIN',   hint: undefined },
  change:  { label: 'New PIN',          hint: 'Enter your new 6-digit PIN' },
  reset:   { label: 'Reset PIN',        hint: 'Set a new PIN after identity verification' },
};

export function PINScreen({
  mode = 'enter',
  targetPIN,
  onComplete,
  onForgot,
  onBiometric,
  onBack,
  maxRetries = 5,
}: PINScreenProps) {
  const [error, setError] = useState(false);
  const [retries, setRetries] = useState(0);
  const [locked, setLocked] = useState(false);

  const { label, hint } = MODE_CONFIG[mode];
  const showBiometric = mode === 'enter' && !!onBiometric;

  const handleComplete = (pin: string) => {
    if (mode === 'confirm' && targetPIN && pin !== targetPIN) {
      setError(true);
      const next = retries + 1;
      setRetries(next);
      if (next >= maxRetries) setLocked(true);
      return;
    }
    setError(false);
    onComplete?.(pin);
  };

  if (locked) {
    return (
      <div className={styles.root}>
        <div className={styles.lockout}>
          <span className={styles.lockoutIcon}>
            <svg width="56" height="56" viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <rect x="3" y="11" width="18" height="11" rx="2" stroke="#ff3d71" strokeWidth="1.6" />
              <path d="M7 11V7a5 5 0 0110 0v4" stroke="#ff3d71" strokeWidth="1.6" strokeLinecap="round" />
              <circle cx="12" cy="16" r="1.5" fill="#ff3d71" />
            </svg>
          </span>
          <h2 className={styles.lockoutTitle}>Account Locked</h2>
          <p className={styles.lockoutBody}>
            Too many failed attempts. Please reset your PIN via identity verification.
          </p>
          <button type="button" className={styles.primaryBtn} onClick={onForgot}>
            Reset PIN
          </button>
        </div>
      </div>
    );
  }

  return (
    <div className={styles.root}>
      {onBack && (
        <button type="button" className={styles.back} onClick={onBack} aria-label="Go back">
          ←
        </button>
      )}

      <div className={styles.padWrap}>
        {error && (
          <p className={styles.errorMsg} role="alert">
            PINs don't match. {maxRetries - retries} attempt{maxRetries - retries !== 1 ? 's' : ''} left.
          </p>
        )}
        <PINPad
          length={6}
          mode={mode === 'confirm' ? 'confirm' : mode === 'create' ? 'create' : 'enter'}
          label={label}
          hint={hint}
          onComplete={handleComplete}
          error={error}
          showBiometric={showBiometric}
          onBiometric={onBiometric}
        />
      </div>

      {mode === 'enter' && onForgot && (
        <button type="button" className={styles.forgotBtn} onClick={onForgot}>
          Forgot PIN?
        </button>
      )}
    </div>
  );
}
