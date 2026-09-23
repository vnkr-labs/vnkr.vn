import React, { forwardRef } from 'react';
import styles from './Button.module.css';

export type ButtonType = 'filled' | 'outlined' | 'ghost';
export type ButtonColor =
  | 'black' | 'blue' | 'green' | 'yellow' | 'orange' | 'error' | 'navy' | 'white';
export type ButtonSize = 'giant' | 'large' | 'medium' | 'small';

export interface ButtonProps extends React.ButtonHTMLAttributes<HTMLButtonElement> {
  variant?: ButtonType;
  color?: ButtonColor;
  size?: ButtonSize;
  loading?: boolean;
  fullWidth?: boolean;
  iconLeft?: React.ReactNode;
  iconRight?: React.ReactNode;
  iconOnly?: boolean;
}

/**
 * Button
 *
 * A11y: when `iconOnly=true`, `aria-label` is required.
 * Contrast: yellow / green / orange buttons use dark text (WCAG 2.1 AA).
 */
export const Button = forwardRef<HTMLButtonElement, ButtonProps>(
  (
    {
      variant = 'filled',
      color = 'black',
      size = 'large',
      loading = false,
      fullWidth = false,
      iconLeft,
      iconRight,
      iconOnly = false,
      disabled,
      children,
      className,
      ...rest
    },
    ref
  ) => {
    const isDisabled = disabled || loading;

    return (
      <button
        ref={ref}
        className={[
          styles.root,
          styles[variant],
          styles[color],
          styles[size],
          fullWidth ? styles.fullWidth : '',
          iconOnly ? styles.iconOnly : '',
          className ?? '',
        ]
          .filter(Boolean)
          .join(' ')}
        disabled={isDisabled}
        aria-busy={loading || undefined}
        aria-disabled={isDisabled || undefined}
        {...rest}
      >
        {loading && <span className={styles.spinner} aria-hidden="true" />}
        {!loading && iconLeft && (
          <span className={styles.icon} aria-hidden="true">{iconLeft}</span>
        )}
        {!iconOnly && children && <span className={styles.label}>{children}</span>}
        {iconOnly && !loading && children}
        {!loading && iconRight && (
          <span className={styles.icon} aria-hidden="true">{iconRight}</span>
        )}
      </button>
    );
  }
);

Button.displayName = 'Button';
