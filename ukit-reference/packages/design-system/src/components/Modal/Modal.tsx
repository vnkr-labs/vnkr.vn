import React, {
  useEffect,
  useRef,
  useId,
  type ReactNode,
} from 'react';
import styles from './Modal.module.css';

export type ModalVariant = 'info' | 'confirm' | 'form' | 'success' | 'error' | 'custom';

export interface ModalAction {
  label: string;
  onClick: () => void;
  loading?: boolean;
  disabled?: boolean;
}

export interface ModalProps {
  open: boolean;
  onClose: () => void;
  variant?: ModalVariant;
  title?: string;
  description?: string;
  primaryAction?: ModalAction;
  secondaryAction?: ModalAction;
  /** Hide the X close button */
  hideClose?: boolean;
  /** Prevent closing by clicking overlay */
  persistent?: boolean;
  children?: ReactNode;
}

/**
 * Modal — WCAG 2.1 AA compliant dialog.
 * Focus trap on open. Escape closes. Restores focus on close.
 */
export function Modal({
  open,
  onClose,
  title,
  description,
  primaryAction,
  secondaryAction,
  hideClose = false,
  persistent = false,
  children,
}: ModalProps) {
  const titleId = useId();
  const descId = useId();
  const firstFocusRef = useRef<HTMLButtonElement>(null);
  const triggerRef = useRef<Element | null>(null);

  // Save trigger + restore on close
  useEffect(() => {
    if (open) {
      triggerRef.current = document.activeElement;
      setTimeout(() => firstFocusRef.current?.focus(), 50);
    } else {
      (triggerRef.current as HTMLElement | null)?.focus();
    }
  }, [open]);

  // Escape key
  useEffect(() => {
    if (!open) return;
    const handler = (e: KeyboardEvent) => {
      if (e.key === 'Escape' && !persistent) onClose();
    };
    document.addEventListener('keydown', handler);
    return () => document.removeEventListener('keydown', handler);
  }, [open, persistent, onClose]);

  // Trap focus within dialog
  useEffect(() => {
    if (!open) return;
    const dialog = document.getElementById(titleId + '-dialog');
    if (!dialog) return;
    const focusable = dialog.querySelectorAll<HTMLElement>(
      'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
    );
    const first = focusable[0];
    const last = focusable[focusable.length - 1];
    const trap = (e: KeyboardEvent) => {
      if (e.key !== 'Tab') return;
      if (e.shiftKey) {
        if (document.activeElement === first) { e.preventDefault(); last?.focus(); }
      } else {
        if (document.activeElement === last) { e.preventDefault(); first?.focus(); }
      }
    };
    document.addEventListener('keydown', trap);
    return () => document.removeEventListener('keydown', trap);
  }, [open, titleId]);

  if (!open) return null;

  return (
    <div
      className={styles.backdrop}
      onClick={persistent ? undefined : (e) => { if (e.target === e.currentTarget) onClose(); }}
      aria-hidden="false"
    >
      <div
        id={titleId + '-dialog'}
        className={styles.dialog}
        role="dialog"
        aria-modal="true"
        aria-labelledby={title ? titleId : undefined}
        aria-describedby={description ? descId : undefined}
      >
        {/* Header */}
        {(title || !hideClose) && (
          <div className={styles.header}>
            {title && <h2 id={titleId} className={styles.title}>{title}</h2>}
            {!hideClose && (
              <button
                ref={firstFocusRef}
                type="button"
                className={styles.closeBtn}
                onClick={onClose}
                aria-label="Close dialog"
              >
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                  <path d="M3 3L13 13M13 3L3 13" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round"/>
                </svg>
              </button>
            )}
          </div>
        )}

        {/* Body */}
        <div className={styles.body}>
          {description && (
            <p id={descId} className={styles.description}>{description}</p>
          )}
          {children}
        </div>

        {/* Footer */}
        {(primaryAction || secondaryAction) && (
          <div className={styles.footer}>
            {secondaryAction && (
              <button
                type="button"
                className={styles.secondaryBtn}
                onClick={secondaryAction.onClick}
                disabled={secondaryAction.disabled}
              >
                {secondaryAction.label}
              </button>
            )}
            {primaryAction && (
              <button
                ref={!title && hideClose ? firstFocusRef : undefined}
                type="button"
                className={styles.primaryBtn}
                onClick={primaryAction.onClick}
                disabled={primaryAction.disabled || primaryAction.loading}
                aria-busy={primaryAction.loading}
              >
                {primaryAction.loading && <span className={styles.spinner} aria-hidden="true" />}
                {primaryAction.label}
              </button>
            )}
          </div>
        )}
      </div>
    </div>
  );
}

/* ── Bottom Sheet ────────────────────────────────────────────── */
export type BottomSheetVariant = 'list' | 'confirm' | 'form' | 'info' | 'otp';

export interface BottomSheetProps {
  open: boolean;
  onClose: () => void;
  title?: string;
  variant?: BottomSheetVariant;
  children?: ReactNode;
}

export function BottomSheet({ open, onClose, title, children }: BottomSheetProps) {
  const titleId = useId();

  useEffect(() => {
    if (!open) return;
    const handler = (e: KeyboardEvent) => { if (e.key === 'Escape') onClose(); };
    document.addEventListener('keydown', handler);
    return () => document.removeEventListener('keydown', handler);
  }, [open, onClose]);

  if (!open) return null;

  return (
    <div
      className={styles.backdrop}
      onClick={(e) => { if (e.target === e.currentTarget) onClose(); }}
    >
      <div
        className={styles.sheet}
        role="dialog"
        aria-modal="true"
        aria-labelledby={title ? titleId : undefined}
      >
        <div className={styles.sheetHandle} aria-hidden="true" />
        {title && (
          <div className={styles.sheetHeader}>
            <h2 id={titleId} className={styles.title}>{title}</h2>
            <button type="button" className={styles.closeBtn} onClick={onClose} aria-label="Close">
              <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                <path d="M3 3L13 13M13 3L3 13" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round"/>
              </svg>
            </button>
          </div>
        )}
        <div className={styles.sheetBody}>{children}</div>
      </div>
    </div>
  );
}
