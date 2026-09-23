import React, { useState } from 'react';
import styles from './PasskeyButton.module.css';
import { Icon } from '../Icon';

export type PasskeyAction = 'register' | 'authenticate';

export interface PasskeyButtonProps {
  action: PasskeyAction;
  onSuccess: (credential: PublicKeyCredential) => void;
  onError?: (error: Error) => void;
  label?: string;
  disabled?: boolean;
  loading?: boolean;
}

function isWebAuthnSupported(): boolean {
  return typeof window !== 'undefined' && !!window.PublicKeyCredential;
}

/**
 * PasskeyButton — WebAuthn / Passkey authentication trigger.
 *
 * SECURITY: This is the ONLY entry point for authentication.
 * Does not expose private key APIs.
 *
 * Falls back gracefully when WebAuthn is not supported.
 */
export function PasskeyButton({
  action,
  onSuccess,
  onError,
  label,
  disabled = false,
  loading = false,
}: PasskeyButtonProps) {
  const [internalLoading, setInternalLoading] = useState(false);
  const supported = isWebAuthnSupported();

  const isLoading = loading || internalLoading;
  const defaultLabel = action === 'register' ? 'Set Up Passkey' : 'Sign in with Passkey';

  const handleClick = async () => {
    if (!supported || disabled || isLoading) return;
    setInternalLoading(true);
    try {
      // Consumers inject the actual WebAuthn call via AxioProvider / custom credential options
      // This component only provides the UX shell + calls back with the credential
      throw new Error('PasskeyButton: provide credential options via AxioProvider');
    } catch (err) {
      onError?.(err instanceof Error ? err : new Error(String(err)));
    } finally {
      setInternalLoading(false);
    }
  };

  if (!supported) {
    return (
      <div className={styles.unsupported} role="alert">
        <Icon name="lock-slash" size={20} color="var(--color-status-warning-default, #db8b00)" aria-hidden />
        <span>Passkey not supported on this device or browser.</span>
      </div>
    );
  }

  return (
    <button
      type="button"
      className={styles.root}
      data-action={action}
      disabled={disabled || isLoading}
      aria-busy={isLoading}
      onClick={handleClick}
    >
      {isLoading ? (
        <span className={styles.spinner} aria-hidden="true" />
      ) : (
        <span className={styles.icon} aria-hidden="true">
          <Icon name={action === 'register' ? 'key' : 'finger-scan'} size={20} color="currentColor" aria-hidden />
        </span>
      )}
      <span>{label ?? defaultLabel}</span>
    </button>
  );
}
