import React, { useId } from 'react';
import styles from './Checkbox.module.css';

export interface RadioButtonProps {
  checked?: boolean;
  onChange?: (value: string) => void;
  disabled?: boolean;
  label?: string;
  helperText?: string;
  value: string;
  name: string;
  id?: string;
  'aria-label'?: string;
}

export function RadioButton({
  checked = false,
  onChange,
  disabled = false,
  label,
  helperText,
  value,
  name,
  id: idProp,
  'aria-label': ariaLabel,
}: RadioButtonProps) {
  const generatedId = useId();
  const id = idProp ?? generatedId;
  const helperId = `${id}-helper`;

  return (
    <div
      className={styles.root}
      data-disabled={disabled || undefined}
    >
      <div className={styles.control}>
        <input
          id={id}
          className={styles.input}
          type="radio"
          checked={checked}
          disabled={disabled}
          name={name}
          value={value}
          aria-label={!label ? ariaLabel : undefined}
          aria-describedby={helperText ? helperId : undefined}
          onChange={() => onChange?.(value)}
        />
        <span className={styles.radioBox} aria-hidden="true">
          {checked && <span className={styles.radioDot} />}
        </span>
      </div>
      {label && (
        <div className={styles.labelGroup}>
          <label htmlFor={id} className={styles.label}>{label}</label>
          {helperText && (
            <span id={helperId} className={styles.helper}>{helperText}</span>
          )}
        </div>
      )}
    </div>
  );
}
