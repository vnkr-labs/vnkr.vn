import React, { forwardRef, useId } from 'react';
import styles from './Checkbox.module.css';

export type CheckboxState = 'unchecked' | 'checked' | 'indeterminate';

export interface CheckboxProps {
  checked?: boolean;
  indeterminate?: boolean;
  onChange?: (checked: boolean) => void;
  disabled?: boolean;
  label?: string;
  helperText?: string;
  error?: boolean;
  errorText?: string;
  id?: string;
  name?: string;
  value?: string;
  'aria-label'?: string;
}

/**
 * Checkbox
 *
 * 5 states: unchecked / checked / indeterminate / disabled-unchecked / disabled-checked
 * WCAG 2.1 AA compliant — uses native <input type="checkbox"> with custom styling.
 */
export const Checkbox = forwardRef<HTMLInputElement, CheckboxProps>(
  (
    {
      checked = false,
      indeterminate = false,
      onChange,
      disabled = false,
      label,
      helperText,
      error = false,
      errorText,
      id: idProp,
      name,
      value,
      'aria-label': ariaLabel,
    },
    ref
  ) => {
    const generatedId = useId();
    const id = idProp ?? generatedId;
    const helperId = `${id}-helper`;
    const errorId = `${id}-error`;

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
      onChange?.(e.target.checked);
    };

    // Apply indeterminate via ref callback
    const setRef = (el: HTMLInputElement | null) => {
      if (el) el.indeterminate = indeterminate;
      if (typeof ref === 'function') ref(el);
      else if (ref) (ref as React.MutableRefObject<HTMLInputElement | null>).current = el;
    };

    return (
      <div
        className={styles.root}
        data-disabled={disabled || undefined}
        data-error={error || undefined}
      >
        <div className={styles.control}>
          <input
            ref={setRef}
            id={id}
            className={styles.input}
            type="checkbox"
            checked={checked}
            disabled={disabled}
            name={name}
            value={value}
            aria-label={!label ? ariaLabel : undefined}
            aria-describedby={
              [helperText && helperId, (error && errorText) && errorId]
                .filter(Boolean)
                .join(' ') || undefined
            }
            aria-invalid={error || undefined}
            aria-checked={indeterminate ? 'mixed' : checked}
            onChange={handleChange}
          />
          <span className={styles.box} aria-hidden="true">
            {checked && !indeterminate && (
              <svg width="10" height="8" viewBox="0 0 10 8" fill="none">
                <path d="M1 4L3.5 6.5L9 1" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
              </svg>
            )}
            {indeterminate && (
              <svg width="10" height="2" viewBox="0 0 10 2" fill="none">
                <path d="M1 1H9" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" />
              </svg>
            )}
          </span>
        </div>
        {label && (
          <div className={styles.labelGroup}>
            <label htmlFor={id} className={styles.label}>{label}</label>
            {helperText && !error && (
              <span id={helperId} className={styles.helper}>{helperText}</span>
            )}
            {error && errorText && (
              <span id={errorId} className={styles.error} role="alert">{errorText}</span>
            )}
          </div>
        )}
      </div>
    );
  }
);

Checkbox.displayName = 'Checkbox';
