/**
 * PasskeyScreen — Passkey + Biometric setup
 * WebAuthn registration, iCloud Keychain, Face ID / Touch ID
 * Fallback prompt when biometrics fail
 */
'use client';
import React, { useState } from 'react';
import styles from './PasskeyScreen.module.css';
import { Icon } from '@axioledger/axio-design-system';

export type PasskeySetupState =
  | 'idle'
  | 'registering'
  | 'done'
  | 'error'
  | 'fallback';

export interface PasskeyScreenProps {
  onSuccess?: (credentialId: string) => void;
  onSkip?: () => void;
  onFallbackToPIN?: () => void;
  /** Inject credential options from server */
  getCredentialOptions?: () => Promise<PublicKeyCredentialCreationOptions>;
}

export function PasskeyScreen({
  onSuccess,
  onSkip,
  onFallbackToPIN,
  getCredentialOptions,
}: PasskeyScreenProps) {
  const [state, setState] = useState<PasskeySetupState>('idle');
  const [errorMsg, setErrorMsg] = useState('');
  const [failCount, setFailCount] = useState(0);

  const isSupported =
    typeof window !== 'undefined' && !!window.PublicKeyCredential;

  const handleRegister = async () => {
    if (!isSupported) {
      setState('fallback');
      return;
    }
    setState('registering');
    setErrorMsg('');
    try {
      const options = getCredentialOptions
        ? await getCredentialOptions()
        : buildDefaultOptions();

      const credential = (await navigator.credentials.create({
        publicKey: options,
      })) as PublicKeyCredential | null;

      if (!credential) throw new Error('No credential returned');
      setState('done');
      onSuccess?.(credential.id);
    } catch (err) {
      const msg = err instanceof Error ? err.message : 'Biometric setup failed';
      setErrorMsg(msg);
      const next = failCount + 1;
      setFailCount(next);
      if (next >= 3) {
        setState('fallback');
      } else {
        setState('error');
      }
    }
  };

  if (state === 'fallback') {
    return (
      <div className={styles.root}>
        <div className={styles.fallbackWrap}>
          <Icon name="lock" size={56} color="#ff3d71" aria-hidden />
          <h2 className={styles.title}>Biometrics unavailable</h2>
          <p className={styles.body}>
            Face ID / Touch ID could not be verified. Continue with PIN instead.
          </p>
          <button type="button" className={styles.primaryBtn} onClick={onFallbackToPIN}>
            Continue with PIN
          </button>
        </div>
      </div>
    );
  }

  if (state === 'done') {
    return (
      <div className={styles.root}>
        <div className={styles.successWrap}>
          <Icon name="tick-circle" size={72} color="#00d68f" aria-label="Passkey created" />
          <h2 className={styles.title}>Passkey created!</h2>
          <p className={styles.body}>
            Your wallet is secured with Face ID / Touch ID via iCloud Keychain.
          </p>
        </div>
      </div>
    );
  }

  return (
    <div className={styles.root}>
      <div className={styles.iconWrap} aria-hidden="true">
        <Icon name="key" size={80} color="#49dbc8" aria-hidden />
      </div>

      <h1 className={styles.title}>Set up your Passkey</h1>
      <p className={styles.body}>
        Use Face ID or Touch ID to create your wallet. No seed phrase. No password.
        Stored securely in iCloud Keychain.
      </p>

      <ul className={styles.bullets} aria-label="Passkey benefits">
        <li>✓ Phishing-resistant — only works on axiopass.io</li>
        <li>✓ Syncs across your Apple devices</li>
        <li>✓ Recoverable if you lose your device</li>
      </ul>

      {state === 'error' && (
        <p className={styles.errorMsg} role="alert">
          {errorMsg} — Try again ({3 - failCount} attempt{3 - failCount !== 1 ? 's' : ''} left)
        </p>
      )}

      <div className={styles.actions}>
        <button
          type="button"
          className={styles.primaryBtn}
          onClick={handleRegister}
          disabled={state === 'registering'}
          aria-busy={state === 'registering'}
        >
          {state === 'registering' ? (
            <><span className={styles.spinner} aria-hidden="true" /> Waiting for Face ID…</>
          ) : (
            'Create with Face ID / Touch ID'
          )}
        </button>
        {onSkip && (
          <button type="button" className={styles.skipBtn} onClick={onSkip}>
            Skip for now
          </button>
        )}
      </div>
    </div>
  );
}

function buildDefaultOptions(): PublicKeyCredentialCreationOptions {
  return {
    challenge: crypto.getRandomValues(new Uint8Array(32)),
    rp: { name: 'Axiopass', id: window.location.hostname },
    user: {
      id: crypto.getRandomValues(new Uint8Array(16)),
      name: 'axiopass-user',
      displayName: 'Axiopass User',
    },
    pubKeyCredParams: [
      { type: 'public-key', alg: -7 },
      { type: 'public-key', alg: -257 },
    ],
    authenticatorSelection: {
      authenticatorAttachment: 'platform',
      userVerification: 'required',
      residentKey: 'required',
    },
    timeout: 60000,
  };
}
