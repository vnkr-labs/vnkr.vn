import React, { forwardRef, useId } from 'react';
import styles from './SearchBar.module.css';

export interface SearchBarProps {
  value?: string;
  onChange?: (value: string) => void;
  onClear?: () => void;
  placeholder?: string;
  disabled?: boolean;
  size?: 'large' | 'medium' | 'small';
  'aria-label'?: string;
}

/**
 * SearchBar — pill-shaped search input with clear button.
 */
export const SearchBar = forwardRef<HTMLInputElement, SearchBarProps>(
  (
    {
      value = '',
      onChange,
      onClear,
      placeholder = 'Search…',
      disabled = false,
      size = 'medium',
      'aria-label': ariaLabel = 'Search',
    },
    ref
  ) => {
    const id = useId();

    return (
      <div
        className={styles.root}
        data-size={size}
        data-disabled={disabled || undefined}
      >
        <label htmlFor={id} className={styles.srOnly}>{ariaLabel}</label>
        {/* Search icon */}
        <span className={styles.icon} aria-hidden="true">
          <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
            <circle cx="7" cy="7" r="5" stroke="currentColor" strokeWidth="1.5"/>
            <path d="M11 11L14 14" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round"/>
          </svg>
        </span>
        <input
          ref={ref}
          id={id}
          type="search"
          className={styles.input}
          value={value}
          placeholder={placeholder}
          disabled={disabled}
          onChange={(e) => onChange?.(e.target.value)}
          aria-label={ariaLabel}
        />
        {value && !disabled && (
          <button
            type="button"
            className={styles.clearBtn}
            onClick={onClear ?? (() => onChange?.(''))}
            aria-label="Clear search"
          >
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
              <path d="M2 2L12 12M12 2L2 12" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round"/>
            </svg>
          </button>
        )}
      </div>
    );
  }
);

SearchBar.displayName = 'SearchBar';
