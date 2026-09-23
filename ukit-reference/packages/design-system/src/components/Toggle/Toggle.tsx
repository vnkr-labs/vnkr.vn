import React, { useId } from 'react';
import styles from './Toggle.module.css';

export interface ToggleProps {
  checked?: boolean;
  onChange?: (checked: boolean) => void;
  disabled?: boolean;
  label?: string;
  labelPosition?: 'left' | 'right';
  size?: 'sm' | 'md';
  id?: string;
  'aria-label'?: string;
}

/**
 * Toggle (Switch)
 * States: on / off / disabled-on / disabled-off + focus ring
 */
export function Toggle({
  checked = false,
  onChange,
  disabled = false,
  label,
  labelPosition = 'right',
  size = 'md',
  id: idProp,
  'aria-label': ariaLabel,
}: ToggleProps) {
  const genId = useId();
  const id = idProp ?? genId;

  return (
    <label
      htmlFor={id}
      className={styles.root}
      data-size={size}
      data-label-pos={labelPosition}
      data-disabled={disabled || undefined}
    >
      {label && labelPosition === 'left' && (
        <span className={styles.label}>{label}</span>
      )}
      <span className={styles.track} data-checked={checked || undefined}>
        <input
          id={id}
          type="checkbox"
          role="switch"
          className={styles.input}
          checked={checked}
          disabled={disabled}
          aria-label={!label ? ariaLabel : undefined}
          aria-checked={checked}
          onChange={(e) => onChange?.(e.target.checked)}
        />
        <span className={styles.thumb} aria-hidden="true" />
      </span>
      {label && labelPosition === 'right' && (
        <span className={styles.label}>{label}</span>
      )}
    </label>
  );
}
