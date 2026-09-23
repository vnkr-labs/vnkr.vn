import React from 'react';
import styles from './ProgressBar.module.css';

export type ProgressVariant = 'default' | 'success' | 'warning' | 'error';

export interface ProgressBarProps {
  /** 0–100 */
  value: number;
  variant?: ProgressVariant;
  size?: 'sm' | 'md' | 'lg';
  showLabel?: boolean;
  label?: string;
  'aria-label'?: string;
}

export interface StepIndicatorProps {
  steps: string[];
  currentStep: number;
  /** 0-indexed */
  orientation?: 'horizontal' | 'vertical';
}

/**
 * ProgressBar — linear progress indicator.
 */
export function ProgressBar({
  value,
  variant = 'default',
  size = 'md',
  showLabel = false,
  label,
  'aria-label': ariaLabel,
}: ProgressBarProps) {
  const clamped = Math.min(100, Math.max(0, value));

  return (
    <div className={styles.wrapper}>
      {(label || showLabel) && (
        <div className={styles.header}>
          {label && <span className={styles.label}>{label}</span>}
          {showLabel && <span className={styles.percent}>{clamped}%</span>}
        </div>
      )}
      <div
        className={styles.track}
        data-size={size}
        role="progressbar"
        aria-valuenow={clamped}
        aria-valuemin={0}
        aria-valuemax={100}
        aria-label={ariaLabel ?? label ?? 'Progress'}
      >
        <div
          className={styles.fill}
          data-variant={variant}
          style={{ width: `${clamped}%` }}
        />
      </div>
    </div>
  );
}

/**
 * StepIndicator — multi-step progress dots with labels.
 */
export function StepIndicator({
  steps,
  currentStep,
  orientation = 'horizontal',
}: StepIndicatorProps) {
  return (
    <nav
      className={styles.steps}
      data-orientation={orientation}
      aria-label="Progress steps"
    >
      {steps.map((label, index) => {
        const state =
          index < currentStep ? 'done' :
          index === currentStep ? 'active' : 'pending';

        return (
          <div key={index} className={styles.step} data-state={state}>
            <div className={styles.dot} aria-hidden="true">
              {state === 'done' ? (
                <svg width="10" height="8" viewBox="0 0 10 8" fill="none">
                  <path d="M1 4L3.5 6.5L9 1" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/>
                </svg>
              ) : (
                <span>{index + 1}</span>
              )}
            </div>
            {orientation === 'horizontal' && index < steps.length - 1 && (
              <div className={styles.connector} aria-hidden="true" />
            )}
            <span className={styles.stepLabel}>{label}</span>
          </div>
        );
      })}
    </nav>
  );
}
