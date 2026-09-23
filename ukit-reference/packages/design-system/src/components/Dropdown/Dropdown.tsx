import React, {
  useState,
  useRef,
  useEffect,
  useId,
} from 'react';
import styles from './Dropdown.module.css';

export interface DropdownOption {
  value: string;
  label: string;
  disabled?: boolean;
  icon?: React.ReactNode;
}

export interface DropdownProps {
  options: DropdownOption[];
  value?: string | string[];
  onChange?: (value: string | string[]) => void;
  placeholder?: string;
  label?: string;
  multiple?: boolean;
  disabled?: boolean;
  error?: boolean;
  errorText?: string;
  searchable?: boolean;
  size?: 'large' | 'medium' | 'small';
  id?: string;
  'aria-label'?: string;
}

/**
 * Dropdown — single and multi-select with keyboard navigation.
 */
export function Dropdown({
  options,
  value,
  onChange,
  placeholder = 'Select…',
  label,
  multiple = false,
  disabled = false,
  error = false,
  errorText,
  searchable = false,
  size = 'medium',
  id: idProp,
  'aria-label': ariaLabel,
}: DropdownProps) {
  const genId = useId();
  const id = idProp ?? genId;
  const listId = `${id}-list`;
  const errorId = `${id}-error`;

  const [open, setOpen] = useState(false);
  const [query, setQuery] = useState('');
  const rootRef = useRef<HTMLDivElement>(null);
  const inputRef = useRef<HTMLInputElement>(null);

  // Close on outside click
  useEffect(() => {
    if (!open) return;
    const handler = (e: MouseEvent) => {
      if (!rootRef.current?.contains(e.target as Node)) setOpen(false);
    };
    document.addEventListener('mousedown', handler);
    return () => document.removeEventListener('mousedown', handler);
  }, [open]);

  // Close on Escape
  useEffect(() => {
    if (!open) return;
    const handler = (e: KeyboardEvent) => { if (e.key === 'Escape') setOpen(false); };
    document.addEventListener('keydown', handler);
    return () => document.removeEventListener('keydown', handler);
  }, [open]);

  const selected = multiple
    ? (Array.isArray(value) ? value : [])
    : (typeof value === 'string' ? [value] : []);

  const displayLabel = selected.length
    ? options.filter((o) => selected.includes(o.value)).map((o) => o.label).join(', ')
    : '';

  const filtered = searchable && query
    ? options.filter((o) => o.label.toLowerCase().includes(query.toLowerCase()))
    : options;

  const toggle = (optValue: string) => {
    if (multiple) {
      const next = selected.includes(optValue)
        ? selected.filter((v) => v !== optValue)
        : [...selected, optValue];
      onChange?.(next);
    } else {
      onChange?.(optValue);
      setOpen(false);
    }
  };

  return (
    <div
      ref={rootRef}
      className={styles.wrapper}
      data-size={size}
      data-error={error || undefined}
      data-disabled={disabled || undefined}
    >
      {label && <label htmlFor={id} className={styles.label}>{label}</label>}
      <button
        id={id}
        type="button"
        className={styles.trigger}
        disabled={disabled}
        aria-haspopup="listbox"
        aria-expanded={open}
        aria-controls={listId}
        aria-label={!label ? ariaLabel : undefined}
        aria-describedby={error && errorText ? errorId : undefined}
        aria-invalid={error || undefined}
        onClick={() => { setOpen((p) => !p); setTimeout(() => inputRef.current?.focus(), 10); }}
      >
        <span className={styles.triggerText}>{displayLabel || <span className={styles.placeholder}>{placeholder}</span>}</span>
        <svg className={[styles.chevron, open ? styles.chevronOpen : ''].join(' ')} width="16" height="16" viewBox="0 0 16 16" fill="none">
          <path d="M4 6L8 10L12 6" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/>
        </svg>
      </button>

      {open && (
        <div className={styles.menu} role="listbox" id={listId} aria-multiselectable={multiple}>
          {searchable && (
            <div className={styles.search}>
              <input
                ref={inputRef}
                type="text"
                className={styles.searchInput}
                placeholder="Search…"
                value={query}
                onChange={(e) => setQuery(e.target.value)}
                aria-label="Search options"
              />
            </div>
          )}
          {filtered.length === 0 && (
            <div className={styles.empty}>No options found</div>
          )}
          {filtered.map((opt) => {
            const isSelected = selected.includes(opt.value);
            return (
              <div
                key={opt.value}
                className={styles.option}
                role="option"
                aria-selected={isSelected}
                aria-disabled={opt.disabled || undefined}
                data-selected={isSelected || undefined}
                data-disabled={opt.disabled || undefined}
                onClick={() => !opt.disabled && toggle(opt.value)}
              >
                {opt.icon && <span className={styles.optIcon} aria-hidden="true">{opt.icon}</span>}
                <span>{opt.label}</span>
                {isSelected && (
                  <svg className={styles.check} width="14" height="14" viewBox="0 0 14 14" fill="none">
                    <path d="M2.5 7L5.5 10L11.5 4" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/>
                  </svg>
                )}
              </div>
            );
          })}
        </div>
      )}
      {error && errorText && (
        <span id={errorId} className={styles.error} role="alert">{errorText}</span>
      )}
    </div>
  );
}
