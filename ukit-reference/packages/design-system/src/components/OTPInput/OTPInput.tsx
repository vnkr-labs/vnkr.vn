import React, {
  useRef,
  useState,
  useCallback,
  type ClipboardEvent,
  type KeyboardEvent,
} from 'react';
import styles from './OTPInput.module.css';

export interface OTPInputProps {
  /** Number of digits. Default: 6 */
  length?: 4 | 6;
  /** Called when all digits are filled */
  onComplete?: (otp: string) => void;
  onChange?: (otp: string) => void;
  disabled?: boolean;
  /** Mask digits like a password field */
  masked?: boolean;
  /** Error state */
  error?: boolean;
  'aria-label'?: string;
}

/**
 * OTPInput
 *
 * Accessible one-time-password input.
 * Auto-focuses next field on digit entry, supports paste.
 */
export function OTPInput({
  length = 6,
  onComplete,
  onChange,
  disabled = false,
  masked = false,
  error = false,
  'aria-label': ariaLabel = 'One-time password',
}: OTPInputProps) {
  const [digits, setDigits] = useState<string[]>(Array(length).fill(''));
  const refs = useRef<(HTMLInputElement | null)[]>([]);

  const update = useCallback(
    (next: string[]) => {
      setDigits(next);
      const value = next.join('');
      onChange?.(value);
      if (value.length === length && !next.includes('')) {
        onComplete?.(value);
      }
    },
    [length, onChange, onComplete]
  );

  const handleChange = (index: number, value: string) => {
    const digit = value.replace(/\D/, '').slice(-1);
    const next = [...digits];
    next[index] = digit;
    update(next);
    if (digit && index < length - 1) {
      refs.current[index + 1]?.focus();
    }
  };

  const handleKeyDown = (index: number, e: KeyboardEvent<HTMLInputElement>) => {
    if (e.key === 'Backspace' && !digits[index] && index > 0) {
      refs.current[index - 1]?.focus();
    }
    if (e.key === 'ArrowLeft' && index > 0) refs.current[index - 1]?.focus();
    if (e.key === 'ArrowRight' && index < length - 1) refs.current[index + 1]?.focus();
  };

  const handlePaste = (e: ClipboardEvent<HTMLInputElement>) => {
    e.preventDefault();
    const pasted = e.clipboardData.getData('text').replace(/\D/g, '').slice(0, length);
    const next = Array(length).fill('');
    pasted.split('').forEach((ch, i) => (next[i] = ch));
    update(next);
    const focusIndex = Math.min(pasted.length, length - 1);
    refs.current[focusIndex]?.focus();
  };

  return (
    <div
      className={styles.root}
      role="group"
      aria-label={ariaLabel}
      data-error={error || undefined}
      data-disabled={disabled || undefined}
    >
      {digits.map((digit, i) => (
        <input
          key={i}
          ref={(el) => { refs.current[i] = el; }}
          className={styles.cell}
          type={masked ? 'password' : 'text'}
          inputMode="numeric"
          pattern="[0-9]*"
          maxLength={1}
          value={digit}
          disabled={disabled}
          aria-label={`Digit ${i + 1} of ${length}`}
          autoComplete={i === 0 ? 'one-time-code' : 'off'}
          onChange={(e) => handleChange(i, e.target.value)}
          onKeyDown={(e) => handleKeyDown(i, e)}
          onPaste={i === 0 ? handlePaste : undefined}
        />
      ))}
    </div>
  );
}
