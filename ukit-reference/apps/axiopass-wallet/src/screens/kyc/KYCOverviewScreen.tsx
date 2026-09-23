/**
 * KYCOverviewScreen — Tier levels, limits, CTA to start
 */
'use client';
import React from 'react';
import styles from './KYCOverviewScreen.module.css';
import { Icon } from '@axioledger/axio-design-system';

export type KYCTier = 'basic' | 'verified' | 'premium';

interface TierInfo {
  tier: KYCTier;
  label: string;
  limit: string;
  features: string[];
  color: string;
}

const TIERS: TierInfo[] = [
  {
    tier: 'basic',
    label: 'Basic',
    limit: 'Up to $500/month',
    features: ['Virtual card', 'Crypto receive', 'App transfers'],
    color: 'var(--color-status-info-default)',
  },
  {
    tier: 'verified',
    label: 'Verified',
    limit: 'Up to $10,000/month',
    features: ['Physical card', 'Bank transfers', 'Buy / Sell crypto'],
    color: 'var(--color-accent-teal)',
  },
  {
    tier: 'premium',
    label: 'Premium',
    limit: 'Unlimited',
    features: ['SWIFT transfers', 'Staking', 'Dedicated support'],
    color: 'var(--color-accent-purple)',
  },
];

export interface KYCOverviewScreenProps {
  currentTier?: KYCTier;
  onStart?: () => void;
  onSelectCountry?: () => void;
}

export function KYCOverviewScreen({
  currentTier = 'basic',
  onStart,
  onSelectCountry,
}: KYCOverviewScreenProps) {
  return (
    <div className={styles.root}>
      <header className={styles.header}>
        <h1 className={styles.title}>Verify your identity</h1>
        <p className={styles.subtitle}>
          Complete KYC to unlock higher limits and all features.
        </p>
      </header>

      {/* Tier cards */}
      <div className={styles.tiers}>
        {TIERS.map((t) => (
          <div
            key={t.tier}
            className={[styles.tierCard, t.tier === currentTier ? styles.tierCurrent : ''].filter(Boolean).join(' ')}
            style={t.tier === currentTier ? { borderColor: t.color } : undefined}
          >
            <div className={styles.tierTop}>
              <span className={styles.tierLabel} style={{ color: t.color }}>{t.label}</span>
              {t.tier === currentTier && (
                <span className={styles.tierBadge} style={{ background: t.color }}>
                  Current
                </span>
              )}
            </div>
            <p className={styles.tierLimit}>{t.limit}</p>
            <ul className={styles.tierFeatures}>
              {t.features.map((f) => (
                <li key={f}>✓ {f}</li>
              ))}
            </ul>
          </div>
        ))}
      </div>

      <div className={styles.actions}>
        <button type="button" className={styles.countryBtn} onClick={onSelectCountry}>
          <span style={{ display: 'inline-flex', alignItems: 'center', gap: 6 }}>
            <Icon name="global" size={16} color="#0057c2" aria-hidden />
            Select your country / residency
          </span>
        </button>
        <button type="button" className={styles.primaryBtn} onClick={onStart}>
          Start Verification →
        </button>
      </div>
    </div>
  );
}
