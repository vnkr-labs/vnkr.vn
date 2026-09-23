import React from 'react';
import styles from './Skeleton.module.css';

export interface SkeletonProps {
  /** Shape variant */
  variant?: 'text' | 'circle' | 'rect';
  width?: number | string;
  height?: number | string;
  /** Repeat n lines (text variant) */
  lines?: number;
  className?: string;
}

/**
 * Skeleton — loading placeholder with pulse animation.
 */
export function Skeleton({
  variant = 'rect',
  width,
  height,
  lines = 1,
  className,
}: SkeletonProps) {
  if (variant === 'text' && lines > 1) {
    return (
      <div className={styles.lines} aria-busy="true" aria-label="Loading…">
        {Array.from({ length: lines }).map((_, i) => (
          <span
            key={i}
            className={[styles.root, styles.text, className ?? ''].filter(Boolean).join(' ')}
            style={{ width: i === lines - 1 ? '70%' : width ?? '100%' }}
          />
        ))}
      </div>
    );
  }

  return (
    <span
      className={[styles.root, styles[variant], className ?? ''].filter(Boolean).join(' ')}
      style={{ width, height }}
      aria-busy="true"
      aria-label="Loading…"
    />
  );
}

/** Pre-composed card skeleton */
export function SkeletonCard() {
  return (
    <div className={styles.card}>
      <div className={styles.cardHeader}>
        <Skeleton variant="circle" width={40} height={40} />
        <div className={styles.cardHeaderText}>
          <Skeleton variant="text" width="60%" height={14} />
          <Skeleton variant="text" width="40%" height={12} />
        </div>
      </div>
      <Skeleton variant="rect" height={120} />
      <div className={styles.cardFooter}>
        <Skeleton variant="text" lines={2} />
      </div>
    </div>
  );
}
