import React from 'react';
import { getTLPConfig, resolveTLPLevel, type TLPLevel } from '../../types/tlp';
import styles from './NamespaceBadge.module.css';

export interface NamespaceBadgeProps {
  /** Full namespace name e.g. "alice.axq", "pool.kpx" */
  name: string;
  /** Override resolved TLP level */
  level?: TLPLevel;
  size?: 'sm' | 'md';
}

/**
 * NamespaceBadge — displays a namespace with its TLP trust indicator.
 *
 * Always shows the trust level — cannot be hidden via props.
 */
export function NamespaceBadge({ name, level, size = 'md' }: NamespaceBadgeProps) {
  const resolvedLevel = level ?? resolveTLPLevel(name);
  const config = getTLPConfig(name);
  const effectiveConfig = level ? getTLPConfig(level) : config;

  return (
    <span
      className={styles.root}
      data-level={resolvedLevel}
      data-size={size}
      title={effectiveConfig.ariaDescription}
      aria-label={`${name} — ${effectiveConfig.label}: ${effectiveConfig.ariaDescription}`}
    >
      <span className={styles.dot} aria-hidden="true" />
      <span className={styles.name}>{name}</span>
      <span className={styles.level} aria-hidden="true">{effectiveConfig.label}</span>
    </span>
  );
}
