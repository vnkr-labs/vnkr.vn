/**
 * KYCStatusScreen — Processing / Approved / Rejected
 */
'use client';
import React from 'react';
import styles from './KYCStatusScreen.module.css';
import { Icon } from '@axioledger/axio-design-system';

export type KYCStatus = 'processing' | 'approved' | 'rejected';

export interface KYCRejectionReason {
  code: string;
  label: string;
  /** Deep-link back to the specific step */
  retryStep: 'docs' | 'face' | 'address' | 'overview';
}

const REJECTION_REASONS: KYCRejectionReason[] = [
  { code: 'doc_blur',      label: 'Document image was blurry',        retryStep: 'docs' },
  { code: 'face_mismatch', label: 'Face did not match document photo', retryStep: 'face' },
  { code: 'doc_expired',   label: 'Document is expired',               retryStep: 'docs' },
  { code: 'address_fail',  label: 'Address proof was unreadable',      retryStep: 'address' },
];

export interface KYCStatusScreenProps {
  status?: KYCStatus;
  /** For rejected state — which reason codes apply */
  rejectionCodes?: string[];
  estimatedMinutes?: number;
  onGoHome?: () => void;
  onRetry?: (step: KYCRejectionReason['retryStep']) => void;
  onContactSupport?: () => void;
}

export function KYCStatusScreen({
  status = 'processing',
  rejectionCodes = [],
  estimatedMinutes = 10,
  onGoHome,
  onRetry,
  onContactSupport,
}: KYCStatusScreenProps) {
  const activeReasons = REJECTION_REASONS.filter((r) => rejectionCodes.includes(r.code));

  if (status === 'approved') {
    return (
      <div className={styles.root}>
        <div className={styles.icon}>
          <Icon name="tick-circle" size={72} color="#00d68f" aria-label="Identity verified" />
        </div>
        <h1 className={styles.title}>Identity Verified!</h1>
        <p className={styles.body}>
          Your account is now fully verified. Enjoy unlimited access to all features.
        </p>
        <button type="button" className={styles.primaryBtn} onClick={onGoHome}>
          Go to Home →
        </button>
      </div>
    );
  }

  if (status === 'rejected') {
    return (
      <div className={styles.root}>
        <div className={styles.icon}>
          <Icon name="close-circle" size={72} color="#ff3d71" aria-label="Verification failed" />
        </div>
        <h1 className={styles.title}>Verification Unsuccessful</h1>
        <p className={styles.body}>
          We couldn't verify your identity for the following reason{activeReasons.length !== 1 ? 's' : ''}:
        </p>

        {activeReasons.length > 0 ? (
          <div className={styles.reasons}>
            {activeReasons.map((r) => (
              <div key={r.code} className={styles.reasonCard}>
                <p className={styles.reasonLabel}>
                  <Icon name="warning-2" size={16} color="#db8b00" aria-hidden />
                  {' '}{r.label}
                </p>
                <button
                  type="button"
                  className={styles.retryBtn}
                  onClick={() => onRetry?.(r.retryStep)}
                >
                  Fix this →
                </button>
              </div>
            ))}
          </div>
        ) : (
          <p className={styles.body}>Please try again or contact support.</p>
        )}

        <button type="button" className={styles.supportBtn} onClick={onContactSupport}>
          Contact Support
        </button>
      </div>
    );
  }

  // Processing
  return (
    <div className={styles.root}>
      <div className={styles.processingIcon} aria-label="Processing">
        <Icon name="timer" size={56} color="#49dbc8" aria-hidden />
      </div>
      <h1 className={styles.title}>Reviewing your documents</h1>
      <p className={styles.body}>
        This usually takes about{' '}
        <strong>{estimatedMinutes} minutes</strong>. We'll notify you when done.
      </p>
      <div className={styles.eta}>
        <span className={styles.etaDot} aria-hidden="true" />
        Estimated: ~{estimatedMinutes} min
      </div>
      <p className={styles.hint}>You can close the app — we'll send a push notification.</p>
      <button type="button" className={styles.ghostBtn} onClick={onGoHome}>
        Back to Home
      </button>
    </div>
  );
}
