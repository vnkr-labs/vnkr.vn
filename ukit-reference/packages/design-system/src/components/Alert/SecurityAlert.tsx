import React from 'react';
import { TLP_CONFIG, type TLPLevel } from '../../types/tlp';
import styles from './SecurityAlert.module.css';

export interface SecurityAlertProps {
  level: TLPLevel;
  /** The name or address being shown */
  name: string;
  /** Human-readable reason for the alert */
  reason: string;
  /** Primary action label. Default: "Sign Anyway" — disabled when blocked */
  actionLabel?: string;
  onAction?: () => void;
  onDismiss?: () => void;
}

/**
 * SecurityAlert — CRITICAL SECURITY COMPONENT
 *
 * Rules (non-overridable):
 * 1. When level === 'blocked': action button is ALWAYS disabled.
 * 2. Cannot be styled to hide or remove the TLP indicator.
 * 3. onDismiss is always available regardless of level.
 */
export function SecurityAlert({
  level,
  name,
  reason,
  actionLabel = 'Sign Anyway',
  onAction,
  onDismiss,
}: SecurityAlertProps) {
  const config = TLP_CONFIG[level];
  const isBlocked = level === 'blocked';

  return (
    <div
      className={styles.root}
      data-level={level}
      role="alertdialog"
      aria-modal="true"
      aria-labelledby="security-alert-title"
      aria-describedby="security-alert-desc"
    >
      {/* TLP badge — always visible */}
      <div className={styles.badge} aria-label={config.ariaDescription}>
        <span className={styles.badgeIndicator} aria-hidden="true" />
        <span className={styles.badgeLabel}>{config.label}</span>
      </div>

      <div className={styles.body}>
        <h2 id="security-alert-title" className={styles.title}>
          {isBlocked ? '⚠️ Unverified Address' : '⚠️ Proceed with Caution'}
        </h2>

        <p className={styles.nameDisplay}>
          <code className={styles.nameCode}>{name}</code>
        </p>

        <p id="security-alert-desc" className={styles.reason}>
          {reason}
        </p>
      </div>

      <div className={styles.actions}>
        <button
          type="button"
          className={styles.dismissBtn}
          onClick={onDismiss}
        >
          Cancel
        </button>
        <button
          type="button"
          className={styles.actionBtn}
          onClick={onAction}
          /* SECURITY RULE: blocked level → always disabled, cannot be overridden */
          disabled={isBlocked}
          aria-disabled={isBlocked}
          data-blocked={isBlocked || undefined}
        >
          {actionLabel}
        </button>
      </div>
    </div>
  );
}
