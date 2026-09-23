/**
 * OnboardingScreen — 3-slide carousel
 * Supports dynamic content from marketing/referral params
 */
'use client';
import React, { useState } from 'react';
import styles from './OnboardingScreen.module.css';
import { Icon } from '@axioledger/axio-design-system';
import type { IconName } from '@axioledger/axio-design-system';

interface OnboardingSlide {
  title: string;
  body: string;
  illustration: IconName;
  accent: string;
}

const DEFAULT_SLIDES: OnboardingSlide[] = [
  {
    title: 'No Seed Phrase',
    body: 'Create your wallet with Face ID or Touch ID. Zero passwords to remember.',
    illustration: 'finger-scan',
    accent: 'var(--color-accent-teal)',
  },
  {
    title: 'Your Domain = Your Wallet',
    body: 'Register a .axq domain. Send and receive with a human-readable name.',
    illustration: 'global',
    accent: 'var(--color-accent-purple)',
  },
  {
    title: 'Bank + Crypto in One',
    body: 'Virtual card, crypto portfolio, and transfers — all in a single app.',
    illustration: 'card-coin',
    accent: 'var(--color-accent-green)',
  },
];

export interface OnboardingScreenProps {
  slides?: OnboardingSlide[];
  onComplete?: () => void;
  onSkip?: () => void;
  /** Show Terms & Privacy modal trigger */
  onShowTerms?: () => void;
}

export function OnboardingScreen({
  slides = DEFAULT_SLIDES,
  onComplete,
  onSkip,
  onShowTerms,
}: OnboardingScreenProps) {
  const [current, setCurrent] = useState(0);
  const isLast = current === slides.length - 1;
  const slide = slides[current];

  const handleNext = () => {
    if (isLast) {
      onComplete?.();
    } else {
      setCurrent((c) => c + 1);
    }
  };

  return (
    <div className={styles.root}>
      {/* Skip */}
      {!isLast && (
        <button
          type="button"
          className={styles.skip}
          onClick={onSkip ?? onComplete}
          aria-label="Skip onboarding"
        >
          Skip
        </button>
      )}

      {/* Slide */}
      <div className={styles.slide} key={current}>
        <div
          className={styles.illustration}
          style={{ background: `color-mix(in srgb, ${slide.accent} 12%, transparent)` }}
          aria-hidden="true"
        >
          <Icon name={slide.illustration} size={80} color={slide.accent} aria-hidden />
        </div>
        <h1 className={styles.title}>{slide.title}</h1>
        <p className={styles.body}>{slide.body}</p>
      </div>

      {/* Dot indicator */}
      <div className={styles.dots} role="tablist" aria-label="Slide indicator">
        {slides.map((_, i) => (
          <button
            key={i}
            role="tab"
            aria-selected={i === current}
            aria-label={`Slide ${i + 1}`}
            className={[styles.dot, i === current ? styles.dotActive : ''].filter(Boolean).join(' ')}
            style={i === current ? { background: slide.accent } : undefined}
            onClick={() => setCurrent(i)}
          />
        ))}
      </div>

      {/* CTA */}
      <div className={styles.actions}>
        <button
          type="button"
          className={styles.primaryBtn}
          style={{ background: slide.accent }}
          onClick={handleNext}
          aria-label={isLast ? 'Get started' : 'Next slide'}
        >
          {isLast ? 'Get Started' : 'Next'}
        </button>

        {isLast && (
          <p className={styles.terms}>
            By continuing you agree to our{' '}
            <button type="button" className={styles.termsLink} onClick={onShowTerms}>
              Terms & Privacy Policy
            </button>
          </p>
        )}
      </div>
    </div>
  );
}
