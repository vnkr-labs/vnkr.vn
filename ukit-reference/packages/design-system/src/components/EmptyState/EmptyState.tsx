import React, { type ReactNode } from 'react';
import styles from './EmptyState.module.css';
import { Icon } from '../Icon';

export type EmptyStateVariant =
  | 'no-data'
  | 'no-results'
  | 'error'
  | 'no-connection'
  | 'no-activity'
  | 'no-wallet'
  | 'custom';

export interface EmptyStateProps {
  variant?: EmptyStateVariant;
  title?: string;
  description?: string;
  action?: { label: string; onClick: () => void };
  icon?: ReactNode;
}

const DEFAULTS: Record<EmptyStateVariant, { icon: ReactNode; title: string; description: string }> = {
  'no-data':       { icon: <Icon name="directbox-receive"  size={56} color="var(--color-icon-muted, #8f9bb3)" aria-hidden />, title: 'No data yet',          description: 'Nothing to show here yet. Check back later.' },
  'no-results':    { icon: <Icon name="search-normal"      size={56} color="var(--color-icon-muted, #8f9bb3)" aria-hidden />, title: 'No results found',     description: 'Try adjusting your search or filters.' },
  'error':         { icon: <Icon name="warning-2"          size={56} color="var(--color-status-error-default, #ff3d71)" aria-hidden />, title: 'Something went wrong', description: 'An error occurred. Please try again.' },
  'no-connection': { icon: <Icon name="wifi"               size={56} color="var(--color-icon-muted, #8f9bb3)" aria-hidden />, title: 'No connection',         description: 'Check your internet connection and retry.' },
  'no-activity':   { icon: <Icon name="receipt-square"     size={56} color="var(--color-icon-muted, #8f9bb3)" aria-hidden />, title: 'No activity',           description: 'Your transaction history will appear here.' },
  'no-wallet':     { icon: <Icon name="briefcase"          size={56} color="var(--color-icon-muted, #8f9bb3)" aria-hidden />, title: 'No wallet connected',   description: 'Connect a wallet to get started.' },
  'custom':        { icon: <Icon name="box"                size={56} color="var(--color-icon-muted, #8f9bb3)" aria-hidden />, title: '',                      description: '' },
};

/**
 * EmptyState — 7 variants for zero-state screens.
 */
export function EmptyState({
  variant = 'no-data',
  title,
  description,
  action,
  icon,
}: EmptyStateProps) {
  const defaults = DEFAULTS[variant];

  return (
    <div className={styles.root} role="status">
      <div className={styles.icon} aria-hidden="true">
        {icon ?? defaults.icon}
      </div>
      <h3 className={styles.title}>{title ?? defaults.title}</h3>
      {(description ?? defaults.description) && (
        <p className={styles.description}>{description ?? defaults.description}</p>
      )}
      {action && (
        <button type="button" className={styles.action} onClick={action.onClick}>
          {action.label}
        </button>
      )}
    </div>
  );
}
