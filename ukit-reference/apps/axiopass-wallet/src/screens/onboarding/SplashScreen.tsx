/**
 * Splash — Main
 * Checks auth token → routes to Onboarding or Home
 */
'use client';
import React, { useEffect, useState } from 'react';
import styles from './Splash.module.css';
import { Icon } from '@axioledger/axio-design-system';

type SplashState = 'loading' | 'force_update' | 'maintenance' | 'done';

export interface SplashScreenProps {
  onDone?: () => void;
  /** Inject these from your app bootstrap logic */
  appState?: SplashState;
}

export function SplashScreen({ onDone, appState = 'loading' }: SplashScreenProps) {
  const [state, setState] = useState<SplashState>(appState);

  useEffect(() => {
    if (state === 'loading') {
      const t = setTimeout(() => {
        // In production: call version-check API here
        setState('done');
        onDone?.();
      }, 1800);
      return () => clearTimeout(t);
    }
  }, [state, onDone]);

  if (state === 'force_update') {
    return (
      <div className={styles.root}>
        <div className={styles.logo}>AXQ</div>
        <div className={styles.modal}>
          <Icon name="direct-up" size={48} color="#49dbc8" aria-hidden />
          <h2 className={styles.modalTitle}>Update Required</h2>
          <p className={styles.modalBody}>
            A new version of Axiopass is available. Please update to continue.
          </p>
          <a
            className={styles.updateBtn}
            href="https://apps.apple.com"
            target="_blank"
            rel="noreferrer"
            aria-label="Update on App Store"
          >
            Update Now
          </a>
        </div>
      </div>
    );
  }

  if (state === 'maintenance') {
    return (
      <div className={styles.root}>
        <div className={styles.logo}>AXQ</div>
        <div className={styles.modal}>
          <Icon name="setting" size={48} color="#fc7339" aria-hidden />
          <h2 className={styles.modalTitle}>Under Maintenance</h2>
          <p className={styles.modalBody}>
            We're upgrading our systems. Please check back in a few minutes.
          </p>
        </div>
      </div>
    );
  }

  return (
    <div className={styles.root} aria-label="Loading Axiopass" role="status">
      <div className={styles.logo} aria-hidden="true">AXQ</div>
      <div className={styles.tagline}>Your passkey. Your wallet.</div>
      <div className={styles.spinner} aria-hidden="true" />
    </div>
  );
}
