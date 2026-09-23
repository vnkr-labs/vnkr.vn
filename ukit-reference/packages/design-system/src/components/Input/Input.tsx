import React, { forwardRef, useId } from 'react';
import styles from './Input.module.css';
import type { TLPLevel } from '../../types/tlp';

export type InputSize = 'large' | 'medium' | 'small';
export type InputType = 'text' | 'password' | 'search' | 'number' | 'email' | 'readonly';

export interface InputProps {
  type?: InputType;
  size?: InputSize;
  label?: string;
  placeholder?: string;
  helperText?: string;
  errorText?: string;
  value?: string;
  defaultValue?: string;
  disabled?: boolean;
  readOnly?: boolean;
  multiline?: boolean;
  rows?: number;
  iconLeft?: React.ReactNode;
  iconRight?: React.ReactNode;
  /** Enable ANS resolver on this field */
  ansResolve?: boolean;
  onResolve?: (address: string, tlpLevel: TLPLevel) => void;
  onChange?: React.ChangeEventHandler<HTMLInputElement | HTMLTextAreaElement>;
  onBlur?: React.FocusEventHandler<HTMLInputElement | HTMLTextAreaElement>;
  id?: string;
  name?: string;
  autoComplete?: string;
  'aria-label'?: string;
  'aria-describedby'?: string;
}

/**
 * Input
 * States: default / hover / focus / error / disabled / readonly
 */
export const Input = forwardRef<HTMLInputElement | HTMLTextAreaElement, InputProps>(
  (
    {
      type = 'text',
      size = 'medium',
      label,
      placeholder,
      helperText,
      errorText,
      value,
      defaultValue,
      disabled = false,
      readOnly = false,
      multiline = false,
      rows = 3,
      iconLeft,
      iconRight,
      onChange,
      onBlur,
      id: idProp,
      name,
      autoComplete,
      'aria-label': ariaLabel,
      'aria-describedby': ariaDescribedby,
    },
    ref
  ) => {
    const genId = useId();
    const id = idProp ?? genId;
    const helperId = `${id}-helper`;
    const errorId = `${id}-error`;
    const hasError = Boolean(errorText);

    const describedBy = [
      ariaDescribedby,
      helperText && !hasError ? helperId : undefined,
      hasError ? errorId : undefined,
    ]
      .filter(Boolean)
      .join(' ') || undefined;

    const sharedProps = {
      id,
      name,
      value,
      defaultValue,
      disabled,
      readOnly: readOnly || type === 'readonly',
      placeholder,
      autoComplete,
      'aria-label': !label ? ariaLabel : undefined,
      'aria-describedby': describedBy,
      'aria-invalid': hasError || undefined,
      onChange,
      onBlur,
    };

    const state = hasError ? 'error' : disabled ? 'disabled' : undefined;

    return (
      <div className={styles.wrapper} data-size={size} data-state={state}>
        {label && (
          <label htmlFor={id} className={styles.label}>
            {label}
          </label>
        )}
        <div className={styles.field}>
          {iconLeft && (
            <span className={styles.iconLeft} aria-hidden="true">{iconLeft}</span>
          )}
          {multiline ? (
            <textarea
              ref={ref as React.Ref<HTMLTextAreaElement>}
              className={styles.input}
              rows={rows}
              {...sharedProps}
            />
          ) : (
            <input
              ref={ref as React.Ref<HTMLInputElement>}
              className={styles.input}
              type={type === 'readonly' ? 'text' : type}
              {...sharedProps}
            />
          )}
          {iconRight && (
            <span className={styles.iconRight} aria-hidden="true">{iconRight}</span>
          )}
        </div>
        {helperText && !hasError && (
          <span id={helperId} className={styles.helper}>{helperText}</span>
        )}
        {hasError && (
          <span id={errorId} className={styles.error} role="alert">{errorText}</span>
        )}
      </div>
    );
  }
);

Input.displayName = 'Input';
