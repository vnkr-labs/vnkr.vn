/**
 * KYCScanScreen — Camera scan for document front/back
 * Auto-capture with alignment frame + quality check
 */
'use client';
import React, { useState } from 'react';
import styles from './KYCScanScreen.module.css';
import { Icon } from '@axioledger/axio-design-system';

export type ScanSide = 'front' | 'back';
export type ScanState = 'align' | 'capturing' | 'quality_check' | 'done' | 'error';

export interface KYCScanScreenProps {
  side?: ScanSide;
  onCapture?: (dataUrl: string) => void;
  onRetry?: () => void;
  onBack?: () => void;
}

const QUALITY_ERRORS = [
  { code: 'blurry',    label: 'Image is blurry — hold still' },
  { code: 'glare',     label: 'Glare detected — adjust lighting' },
  { code: 'cropped',   label: 'Document is cropped — move further back' },
  { code: 'dark',      label: 'Too dark — improve lighting' },
];

export function KYCScanScreen({
  side = 'front',
  onCapture,
  onRetry,
  onBack,
}: KYCScanScreenProps) {
  const [scanState, setScanState] = useState<ScanState>('align');
  const [qualityError, setQualityError] = useState<string | null>(null);
  const [previewUrl, setPreviewUrl] = useState<string | null>(null);

  // Simulate capture for scaffold
  const handleCapture = () => {
    setScanState('capturing');
    setTimeout(() => {
      // Simulate quality check pass
      setScanState('quality_check');
      setTimeout(() => {
        setScanState('done');
        // In production: real image dataURL from camera stream
        setPreviewUrl('');
        onCapture?.('');
      }, 800);
    }, 600);
  };

  const handleRetry = () => {
    setScanState('align');
    setQualityError(null);
    setPreviewUrl(null);
    onRetry?.();
  };

  return (
    <div className={styles.root}>
      <div className={styles.topBar}>
        <button type="button" className={styles.back} onClick={onBack}>✕</button>
        <span className={styles.sideLabel}>
          Scan {side === 'front' ? 'Front' : 'Back'} of Document
        </span>
        <button type="button" className={styles.torchBtn} aria-label="Toggle torch">
          <Icon name="flash" size={20} color="#fff" aria-hidden />
        </button>
      </div>

      {/* Camera viewport */}
      <div className={styles.viewport}>
        {/* Document frame overlay */}
        <div
          className={[
            styles.docFrame,
            scanState === 'done' ? styles.docFrameDone : '',
            scanState === 'error' ? styles.docFrameError : '',
          ].filter(Boolean).join(' ')}
          aria-label="Align document within frame"
        >
          {/* Corner markers */}
          {['tl', 'tr', 'bl', 'br'].map((c) => (
            <span key={c} className={[styles.corner, styles[c]].join(' ')} aria-hidden="true" />
          ))}
        </div>

        {/* Scanning animation */}
        {scanState === 'align' && (
          <div className={styles.scanLine} aria-hidden="true" />
        )}

        {/* Done preview */}
        {scanState === 'done' && previewUrl !== null && (
          <div className={styles.capturedBadge}>✓ Captured</div>
        )}
      </div>

      {/* Instruction */}
      <div className={styles.instruction}>
        {scanState === 'align' && (
          <p>Position the {side} of your document inside the frame</p>
        )}
        {scanState === 'capturing' && (
          <p style={{ display: 'flex', alignItems: 'center', gap: 6 }}>
            <Icon name="timer" size={16} color="#49dbc8" aria-hidden /> Capturing…
          </p>
        )}
        {scanState === 'quality_check' && (
          <p style={{ display: 'flex', alignItems: 'center', gap: 6 }}>
            <Icon name="scan" size={16} color="#49dbc8" aria-hidden /> Checking image quality…
          </p>
        )}
        {scanState === 'done' && (
          <p className={styles.success} style={{ display: 'flex', alignItems: 'center', gap: 6 }}>
            <Icon name="tick-circle" size={16} color="#00d68f" aria-hidden /> Image looks great!
          </p>
        )}
        {scanState === 'error' && qualityError && (
          <p className={styles.errorTxt}>{qualityError}</p>
        )}
      </div>

      {/* CTA */}
      <div className={styles.ctas}>
        {(scanState === 'align') && (
          <button type="button" className={styles.captureBtn} onClick={handleCapture}>
            <span className={styles.captureRing} />
            <span className={styles.captureInner} />
          </button>
        )}
        {(scanState === 'error') && (
          <>
            <button type="button" className={styles.primaryBtn} onClick={handleRetry}>Try Again</button>
            <button type="button" className={styles.secondaryBtn} onClick={() => {}}>
              Upload manually
            </button>
          </>
        )}
        {scanState === 'done' && (
          <button
            type="button"
            className={styles.primaryBtn}
            onClick={() => onCapture?.('')}
          >
            Use This Photo
          </button>
        )}
      </div>
    </div>
  );
}
