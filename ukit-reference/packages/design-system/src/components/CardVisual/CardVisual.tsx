import React, { useState } from 'react';
import styles from './CardVisual.module.css';
import { Icon } from '../Icon';

export type CardVariant = 'virtual' | 'physical';
export type CardScheme = 'visa' | 'mastercard' | 'axq';
export type CardSkin = 'dark' | 'teal' | 'purple' | 'green';

export interface CardVisualProps {
  /** Card holder name */
  name: string;
  /** Last 4 digits only — never pass full PAN */
  last4: string;
  /** Expiry in MM/YY format */
  expiry: string;
  /** Network scheme */
  scheme?: CardScheme;
  variant?: CardVariant;
  skin?: CardSkin;
  /** Whether to show the back face (CVV side) */
  flipped?: boolean;
  /** Controlled flip toggle; omit for uncontrolled */
  onFlip?: () => void;
  /** CVV — only revealed after biometric auth */
  cvv?: string;
  /** Frozen state dims the card */
  frozen?: boolean;
  className?: string;
}

const SCHEME_LABEL: Record<CardScheme, string> = {
  visa: 'VISA',
  mastercard: 'MC',
  axq: 'AXQ',
};

/**
 * CardVisual
 *
 * 3D CSS flip card showing front (number + name) and back (CVV strip).
 * PAN is never stored/displayed in full — only last4.
 * Use `flipped` prop to reveal the back after biometric auth.
 */
export function CardVisual({
  name,
  last4,
  expiry,
  scheme = 'axq',
  variant = 'virtual',
  skin = 'dark',
  flipped = false,
  onFlip,
  cvv,
  frozen = false,
  className,
}: CardVisualProps) {
  const [localFlipped, setLocalFlipped] = useState(false);

  const isFlipped = onFlip !== undefined ? flipped : localFlipped;
  const handleClick = () => {
    if (onFlip) onFlip();
    else setLocalFlipped((f) => !f);
  };

  return (
    <div
      className={[styles.scene, className ?? ''].filter(Boolean).join(' ')}
      onClick={handleClick}
      role="button"
      tabIndex={0}
      aria-label={`${variant} card ending ${last4}${isFlipped ? ', showing back' : ', showing front'}. Click to flip.`}
      onKeyDown={(e) => (e.key === 'Enter' || e.key === ' ') && handleClick()}
    >
      <div
        className={[
          styles.card,
          styles[skin],
          isFlipped ? styles.flipped : '',
          frozen ? styles.frozen : '',
        ]
          .filter(Boolean)
          .join(' ')}
      >
        {/* ── Front ── */}
        <div className={[styles.face, styles.front].join(' ')} aria-hidden={isFlipped}>
          <div className={styles.topRow}>
            <div className={styles.brandMark}>
              <div className={styles.brandDot} />
              <div className={styles.brandDot} />
            </div>
            {variant === 'virtual' && (
              <span className={styles.variantTag}>VIRTUAL</span>
            )}
          </div>

          {/* Chip */}
          <div className={styles.chip} aria-hidden="true">
            <div className={styles.chipLines} />
          </div>

          {/* PAN masked */}
          <div className={styles.pan} aria-label={`Card number ending in ${last4}`}>
            <span>••••</span>
            <span>••••</span>
            <span>••••</span>
            <span>{last4}</span>
          </div>

          <div className={styles.bottomRow}>
            <div>
              <div className={styles.cardMeta}>CARD HOLDER</div>
              <div className={styles.cardValue}>{name.toUpperCase()}</div>
            </div>
            <div>
              <div className={styles.cardMeta}>EXPIRES</div>
              <div className={styles.cardValue}>{expiry}</div>
            </div>
            <div className={styles.scheme} aria-label={`${scheme} card`}>
              {SCHEME_LABEL[scheme]}
            </div>
          </div>

          {frozen && (
            <div className={styles.frozenOverlay} aria-label="Card is frozen">
              <Icon name="slash" size={20} color="#fff" aria-hidden />
              <span className={styles.frozenText}>FROZEN</span>
            </div>
          )}
        </div>

        {/* ── Back ── */}
        <div className={[styles.face, styles.back].join(' ')} aria-hidden={!isFlipped}>
          <div className={styles.stripe} aria-hidden="true" />
          <div className={styles.cvvRow}>
            <span className={styles.cvvLabel}>CVV</span>
            <span className={styles.cvvValue}>
              {cvv ?? '•••'}
            </span>
          </div>
          <div className={styles.backScheme}>{SCHEME_LABEL[scheme]}</div>
        </div>
      </div>
    </div>
  );
}
