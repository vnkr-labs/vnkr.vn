/**
 * KYCDocScreen — Document type selection + guidelines modal
 */
'use client';
import React, { useState } from 'react';
import styles from './KYCDocScreen.module.css';
import { Icon } from '@axioledger/axio-design-system';
import type { IconName } from '@axioledger/axio-design-system';

export type DocType = 'national_id' | 'passport' | 'drivers_license';

const DOC_OPTIONS: { type: DocType; label: string; icon: IconName; hint: string }[] = [
  { type: 'national_id',     label: 'National ID (CCCD)', icon: 'personalcard',   hint: 'Front & back required' },
  { type: 'passport',        label: 'Passport',           icon: 'document-text',  hint: 'Photo page only' },
  { type: 'drivers_license', label: "Driver's License",   icon: 'document-normal',hint: 'Front & back required' },
];

export interface KYCDocScreenProps {
  onSelect?: (type: DocType) => void;
  onBack?: () => void;
}

export function KYCDocScreen({ onSelect, onBack }: KYCDocScreenProps) {
  const [selected, setSelected] = useState<DocType | null>(null);
  const [showGuide, setShowGuide] = useState(false);

  return (
    <div className={styles.root}>
      <button type="button" className={styles.back} onClick={onBack}>←</button>
      <h1 className={styles.title}>Choose document type</h1>
      <p className={styles.subtitle}>Select a government-issued ID to verify your identity.</p>

      <div className={styles.options}>
        {DOC_OPTIONS.map((opt) => (
          <button
            key={opt.type}
            type="button"
            className={[styles.option, selected === opt.type ? styles.optionSelected : ''].filter(Boolean).join(' ')}
            onClick={() => setSelected(opt.type)}
            aria-pressed={selected === opt.type}
          >
            <span className={styles.optionIcon}>
              <Icon name={opt.icon} size={28} color="#1f2328" aria-hidden />
            </span>
            <div>
              <p className={styles.optionLabel}>{opt.label}</p>
              <p className={styles.optionHint}>{opt.hint}</p>
            </div>
            {selected === opt.type && <span className={styles.check}>✓</span>}
          </button>
        ))}
      </div>

      <button type="button" className={styles.guideBtn} onClick={() => setShowGuide(true)}>
        <Icon name="clipboard-text" size={16} color="#0057c2" aria-hidden />
        {' '}Photo guidelines
      </button>

      <button
        type="button"
        className={styles.primaryBtn}
        disabled={!selected}
        onClick={() => selected && onSelect?.(selected)}
      >
        Continue
      </button>

      {/* Guidelines modal */}
      {showGuide && (
        <div className={styles.overlay} role="dialog" aria-modal="true" aria-label="Photo guidelines">
          <div className={styles.modal}>
            <button
              type="button"
              className={styles.modalClose}
              onClick={() => setShowGuide(false)}
              aria-label="Close guidelines"
            >
              ✕
            </button>
            <h2 className={styles.modalTitle}>
              <Icon name="clipboard-text" size={20} color="#1f2328" aria-hidden />
              {' '}Document Guidelines
            </h2>
            <ul className={styles.modalList}>
              <li><Icon name="tick-circle" size={14} color="#00d68f" aria-hidden /> Good lighting — avoid shadows or glare</li>
              <li><Icon name="tick-circle" size={14} color="#00d68f" aria-hidden /> Document fully visible — no cut corners</li>
              <li><Icon name="tick-circle" size={14} color="#00d68f" aria-hidden /> Hold steady — no blurry images</li>
              <li><Icon name="close-circle" size={14} color="#ff3d71" aria-hidden /> No photocopies or screenshots</li>
              <li><Icon name="close-circle" size={14} color="#ff3d71" aria-hidden /> No expired documents</li>
              <li><Icon name="close-circle" size={14} color="#ff3d71" aria-hidden /> No covered information</li>
            </ul>
            <button
              type="button"
              className={styles.primaryBtn}
              onClick={() => setShowGuide(false)}
            >
              Got it
            </button>
          </div>
        </div>
      )}
    </div>
  );
}
