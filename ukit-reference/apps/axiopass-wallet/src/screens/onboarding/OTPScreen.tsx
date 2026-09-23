/**
 * OTPScreen — SMS / WhatsApp / Email OTP verification
 * Handles resend, countdown, error states, fallback to call
 */
'use client';
import React, { useEffect, useState, useCallback } from 'react';
import styles from './OTPScreen.module.css';
import { Icon } from '@axioledger/axio-design-system';

export type OTPChannel = 'sms' | 'whatsapp' | 'email';

export interface OTPScreenProps {
  /** Masked identifier, e.g. "+84 *** *** 789" or "j***@mail.com" */
  maskedIdentifier: string;
  channel?: OTPChannel;
  onComplete?: (otp: string) => void;
  onResend?: (channel: OTPChannel) => void;
  onCallSupport?: () => void;
  onBack?: () => void;
  /** Simulate error for testing */
  error?: boolean;
}

const RESEND_SECONDS = 60;
const CHANNEL_LABELS: Record<OTPChannel, string> = {
  sms:      'SMS',
  whatsapp: 'WhatsApp',
  email:    'Email',
};

const CHANNEL_ICONS: Record<OTPChannel, React.ReactNode> = {
  sms:      <Icon name="sms" size={14} color="currentColor" aria-hidden />,
  whatsapp: <Icon name="message-circle" size={14} color="currentColor" aria-hidden />,
  email:    <Icon name="direct" size={14} color="currentColor" aria-hidden />,
};

export function OTPScreen({
  maskedIdentifier,
  channel = 'sms',
  onComplete,
  onResend,
  onCallSupport,
  onBack,
  error = false,
}: OTPScreenProps) {
  const [otp, setOtp] = useState('');
  const [countdown, setCountdown] = useState(RESEND_SECONDS);
  const [activeChannel, setActiveChannel] = useState<OTPChannel>(channel);
  const [hasError, setHasError] = useState(error);

  // Countdown timer
  useEffect(() => {
    if (countdown <= 0) return;
    const t = setInterval(() => setCountdown((c) => c - 1), 1000);
    return () => clearInterval(t);
  }, [countdown]);

  // Propagate external error
  useEffect(() => {
    setHasError(error);
    if (error) setOtp('');
  }, [error]);

  const handleOtpChange = useCallback((val: string) => {
    const clean = val.replace(/\D/g, '').slice(0, 6);
    setOtp(clean);
    setHasError(false);
    if (clean.length === 6) onComplete?.(clean);
  }, [onComplete]);

  const handleResend = () => {
    setCountdown(RESEND_SECONDS);
    setOtp('');
    setHasError(false);
    onResend?.(activeChannel);
  };

  return (
    <div className={styles.root}>
      {/* Back */}
      <button type="button" className={styles.back} onClick={onBack} aria-label="Go back">
        ←
      </button>

      <div className={styles.content}>
        <h1 className={styles.title}>Enter verification code</h1>
        <p className={styles.subtitle}>
          We sent a 6-digit code via{' '}
          <strong>{CHANNEL_LABELS[activeChannel]}</strong> to{' '}
          <strong>{maskedIdentifier}</strong>
        </p>

        {/* OTP boxes — rendered as controlled individual inputs */}
        <div
          className={[styles.otpRow, hasError ? styles.otpRowError : ''].filter(Boolean).join(' ')}
          role="group"
          aria-label="6-digit verification code"
          data-error={hasError || undefined}
        >
          {Array.from({ length: 6 }).map((_, i) => (
            <input
              key={i}
              type="text"
              inputMode="numeric"
              maxLength={1}
              className={styles.otpCell}
              value={otp[i] ?? ''}
              aria-label={`Digit ${i + 1}`}
              readOnly
            />
          ))}
        </div>
        {/* Hidden actual input to capture keyboard */}
        <input
          type="text"
          inputMode="numeric"
          pattern="[0-9]*"
          className={styles.hiddenInput}
          value={otp}
          onChange={(e) => handleOtpChange(e.target.value)}
          maxLength={6}
          autoComplete="one-time-code"
          aria-hidden="true"
          tabIndex={-1}
        />

        {hasError && (
          <p className={styles.errorMsg} role="alert">
            Incorrect code. Please try again.
          </p>
        )}

        {/* Resend row */}
        <div className={styles.resendRow}>
          {countdown > 0 ? (
            <span className={styles.countdown}>
              Resend in <strong>{countdown}s</strong>
            </span>
          ) : (
            <button type="button" className={styles.resendBtn} onClick={handleResend}>
              Resend code
            </button>
          )}
        </div>

        {/* Channel switcher */}
        <div className={styles.channels} role="group" aria-label="Delivery channel">
          {(['sms', 'whatsapp', 'email'] as OTPChannel[]).map((ch) => (
            <button
              key={ch}
              type="button"
              className={[styles.channelBtn, activeChannel === ch ? styles.channelBtnActive : ''].filter(Boolean).join(' ')}
              onClick={() => { setActiveChannel(ch); handleResend(); }}
              aria-pressed={activeChannel === ch}
            >
              <span style={{ display: 'inline-flex', alignItems: 'center', gap: 5 }}>
                {CHANNEL_ICONS[ch]}{CHANNEL_LABELS[ch]}
              </span>
            </button>
          ))}
        </div>

        {/* Call support fallback */}
        <button type="button" className={styles.callBtn} onClick={onCallSupport}>
          Didn't receive a code? Call support
        </button>
      </div>
    </div>
  );
}
