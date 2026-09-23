/**
 * KYCFaceScreen — Active liveness detection
 * Uses LivenessFrame DS component
 */
'use client';
import React, { useEffect, useState } from 'react';
import styles from './KYCFaceScreen.module.css';
import { LivenessFrame } from '@axioledger/axio-design-system';
import type { LivenessStep as _LivenessStep } from '@axioledger/axio-design-system';

/** Re-exported for consumers that import from the kyc barrel */
export type LivenessStep = _LivenessStep;

export interface KYCFaceScreenProps {
  onComplete?: () => void;
  onRetry?: () => void;
  onBack?: () => void;
}

const STEPS: LivenessStep[] = ['align', 'blink', 'turn_left', 'smile', 'done'];

export function KYCFaceScreen({ onComplete, onRetry, onBack }: KYCFaceScreenProps) {
  const [stepIndex, setStepIndex] = useState(0);
  const [progress, setProgress] = useState(0);
  const [failed, setFailed] = useState(false);

  const currentStep = STEPS[stepIndex];

  useEffect(() => {
    if (currentStep === 'done') {
      onComplete?.();
      return;
    }
    if (currentStep === 'error' || failed) return;

    // Simulate auto-advance per step
    const progressInterval = setInterval(() => {
      setProgress((p) => {
        if (p >= 100) {
          clearInterval(progressInterval);
          setStepIndex((i) => Math.min(i + 1, STEPS.length - 1));
          setProgress(0);
          return 100;
        }
        return p + 4;
      });
    }, 80);

    return () => clearInterval(progressInterval);
  }, [stepIndex, currentStep, failed, onComplete]);

  if (failed) {
    return (
      <div className={styles.root}>
        <div className={styles.errorState}>
          <span className={styles.errorIcon}>
            <svg width="56" height="56" viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <circle cx="12" cy="12" r="10" stroke="#ff3d71" strokeWidth="1.6" />
              <line x1="8" y1="8" x2="16" y2="16" stroke="#ff3d71" strokeWidth="2" strokeLinecap="round" />
              <line x1="16" y1="8" x2="8" y2="16" stroke="#ff3d71" strokeWidth="2" strokeLinecap="round" />
            </svg>
          </span>
          <h2 className={styles.errorTitle}>Liveness check failed</h2>
          <p className={styles.errorBody}>
            We couldn't verify your identity. Please try again in good lighting.
          </p>
          <button type="button" className={styles.primaryBtn} onClick={() => { setFailed(false); setStepIndex(0); setProgress(0); onRetry?.(); }}>
            Try Again
          </button>
          <button type="button" className={styles.backBtn} onClick={onBack}>Go Back</button>
        </div>
      </div>
    );
  }

  return (
    <div className={styles.root}>
      <button type="button" className={styles.backBtn} onClick={onBack}>✕</button>

      <LivenessFrame
        step={currentStep}
        progress={progress}
        scanning={currentStep !== 'done' && currentStep !== 'error'}
      />

      {/* Step dots */}
      <div className={styles.stepDots} role="status" aria-label={`Step ${stepIndex + 1} of ${STEPS.length - 1}`}>
        {STEPS.slice(0, -1).map((_, i) => (
          <span
            key={i}
            className={[styles.stepDot, i < stepIndex ? styles.stepDotDone : i === stepIndex ? styles.stepDotActive : ''].filter(Boolean).join(' ')}
          />
        ))}
      </div>

      <button
        type="button"
        className={styles.failBtn}
        onClick={() => setFailed(true)}
      >
        Having trouble?
      </button>
    </div>
  );
}
