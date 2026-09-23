import React from 'react';
import styles from './Badge.module.css';

export type BadgeVariant = 'info' | 'success' | 'warning' | 'error' | 'default';

export interface BadgeProps {
  variant?: BadgeVariant;
  children: React.ReactNode;
  dot?: boolean;
  size?: 'sm' | 'md';
}

export interface ChipProps {
  label: string;
  active?: boolean;
  disabled?: boolean;
  onRemove?: () => void;
  onClick?: () => void;
  icon?: React.ReactNode;
}

/**
 * Badge — inline status label.
 */
export function Badge({ variant = 'default', children, dot = false, size = 'md' }: BadgeProps) {
  return (
    <span className={styles.badge} data-variant={variant} data-size={size}>
      {dot && <span className={styles.dot} aria-hidden="true" />}
      {children}
    </span>
  );
}

/**
 * Chip — selectable/removable tag. Pill shape.
 */
export function Chip({ label, active = false, disabled = false, onRemove, onClick, icon }: ChipProps) {
  return (
    <span
      className={styles.chip}
      data-active={active || undefined}
      data-disabled={disabled || undefined}
      role={onClick ? 'button' : undefined}
      tabIndex={onClick && !disabled ? 0 : undefined}
      aria-pressed={onClick ? active : undefined}
      aria-disabled={disabled || undefined}
      onClick={!disabled ? onClick : undefined}
      onKeyDown={onClick && !disabled ? (e) => {
        if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); onClick(); }
      } : undefined}
    >
      {icon && <span className={styles.chipIcon} aria-hidden="true">{icon}</span>}
      <span className={styles.chipLabel}>{label}</span>
      {onRemove && !disabled && (
        <button
          type="button"
          className={styles.chipRemove}
          onClick={(e) => { e.stopPropagation(); onRemove(); }}
          aria-label={`Remove ${label}`}
        >
          <svg width="10" height="10" viewBox="0 0 10 10" fill="none">
            <path d="M2 2L8 8M8 2L2 8" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" />
          </svg>
        </button>
      )}
    </span>
  );
}
