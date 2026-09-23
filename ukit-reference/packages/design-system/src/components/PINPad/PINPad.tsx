import React, { useState, useCallback } from 'react';
import styles from './PINPad.module.css';

export type PINPadMode = 'create' | 'confirm' | 'enter';

export interface PINPadProps {
  /** PIN length. Default: 6 */
  length?: 4 | 6;
  mode?: PINPadMode;
  /** Called when all digits are entered */
  onComplete?: (pin: string) => void;
  /** Called on every digit change */
  onChange?: (pin: string) => void;
  /** Trigger shake + clear animation for wrong PIN */
  error?: boolean;
  disabled?: boolean;
  /** Custom label above the dot row */
  label?: string;
  /** Sub-label / hint text */
  hint?: string;
  /** Show biometric shortcut key */
  showBiometric?: boolean;
  onBiometric?: () => void;
}

const KEYS = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '', '0', 'del'] as const;

/**
 * PINPad
 *
 * Full-screen PIN entry with dot indicator and custom numpad.
 * WCAG 2.1 AA: each key has aria-label. Shake animation on error.
 */
export function PINPad({
  length = 6,
  mode = 'enter',
  onComplete,
  onChange,
  error = false,
  disabled = false,
  label,
  hint,
  showBiometric = false,
  onBiometric,
}: PINPadProps) {
  const [digits, setDigits] = useState<string[]>([]);

  const defaultLabel =
    mode === 'create'
      ? 'Create your PIN'
      : mode === 'confirm'
      ? 'Confirm your PIN'
      : 'Enter your PIN';

  const handleKey = useCallback(
    (key: string) => {
      if (disabled) return;
      setDigits((prev) => {
        let next = [...prev];
        if (key === 'del') {
          next = next.slice(0, -1);
        } else if (next.length < length) {
          next = [...next, key];
        }
        const value = next.join('');
        onChange?.(value);
        if (next.length === length) {
          onComplete?.(value);
        }
        return next;
      });
    },
    [disabled, length, onChange, onComplete]
  );

  // Allow parent to reset: when error prop flips true→false, digits won't clear
  // Parent calls onComplete → inspects result → sets error → PINPad clears via key
  React.useEffect(() => {
    if (error) {
      const t = setTimeout(() => setDigits([]), 600);
      return () => clearTimeout(t);
    }
  }, [error]);

  return (
    <div
      className={styles.root}
      data-disabled={disabled || undefined}
      aria-disabled={disabled}
    >
      {/* Label */}
      <div className={styles.labelBlock}>
        <span className={styles.label}>{label ?? defaultLabel}</span>
        {hint && <span className={styles.hint}>{hint}</span>}
      </div>

      {/* Dot row */}
      <div
        className={[styles.dots, error ? styles.shake : ''].filter(Boolean).join(' ')}
        role="status"
        aria-label={`${digits.length} of ${length} digits entered`}
        aria-live="polite"
      >
        {Array.from({ length }).map((_, i) => (
          <span
            key={i}
            className={[
              styles.dot,
              i < digits.length ? styles.dotFilled : '',
              error ? styles.dotError : '',
            ]
              .filter(Boolean)
              .join(' ')}
          />
        ))}
      </div>

      {/* Numpad grid */}
      <div className={styles.grid} role="group" aria-label="PIN numpad">
        {KEYS.map((key) => {
          if (key === '') {
            return showBiometric ? (
              <button
                key="bio"
                type="button"
                className={[styles.key, styles.keySpecial].join(' ')}
                onClick={onBiometric}
                disabled={disabled}
                aria-label="Use biometrics"
              >
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                  <path
                    d="M12 2C8.13 2 5 5.13 5 9v3c0 .55.45 1 1 1s1-.45 1-1V9c0-2.76 2.24-5 5-5s5 2.24 5 5v1h-1c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h1v1c0 1.1-.9 2-2 2h-4"
                    stroke="currentColor"
                    strokeWidth="1.6"
                    strokeLinecap="round"
                  />
                  <circle cx="12" cy="12" r="2" fill="currentColor" />
                </svg>
              </button>
            ) : (
              <span key="empty" className={styles.keyEmpty} aria-hidden="true" />
            );
          }

          if (key === 'del') {
            return (
              <button
                key="del"
                type="button"
                className={[styles.key, styles.keySpecial].join(' ')}
                onClick={() => handleKey('del')}
                disabled={disabled || digits.length === 0}
                aria-label="Delete last digit"
              >
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                  <path
                    d="M21 4H8l-7 8 7 8h13a2 2 0 002-2V6a2 2 0 00-2-2z"
                    stroke="currentColor"
                    strokeWidth="1.8"
                    strokeLinecap="round"
                    strokeLinejoin="round"
                  />
                  <line x1="18" y1="9" x2="12" y2="15" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" />
                  <line x1="12" y1="9" x2="18" y2="15" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" />
                </svg>
              </button>
            );
          }

          return (
            <button
              key={key}
              type="button"
              className={styles.key}
              onClick={() => handleKey(key)}
              disabled={disabled || digits.length >= length}
              aria-label={`Digit ${key}`}
            >
              <span className={styles.keyNum}>{key}</span>
            </button>
          );
        })}
      </div>
    </div>
  );
}
