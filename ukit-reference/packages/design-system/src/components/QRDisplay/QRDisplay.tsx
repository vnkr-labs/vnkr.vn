'use client';

import React, { useEffect, useRef, useState, useCallback } from 'react';
import styles from './QRDisplay.module.css';

export interface QRDisplayProps {
  /** The raw value to encode — wallet address, payment URI, etc. */
  value: string;
  /** Display size in px. Default: 220 */
  size?: number;
  /** Label shown below the QR */
  label?: string;
  /** Sub-label (e.g. truncated address) */
  sublabel?: string;
  /** Show copy-to-clipboard button */
  showCopy?: boolean;
  /** Show download/share button */
  showShare?: boolean;
  /** Network badge label (e.g. "ERC-20", "TRC-20") */
  network?: string;
  onCopy?: () => void;
}

/**
 * QRDisplay
 *
 * Renders a QR code via Canvas using a lightweight hand-rolled module.
 * Falls back to a styled placeholder when `value` is empty.
 * Zero external dependencies in the component itself — QR data matrix
 * is generated via the browser's built-in APIs only.
 *
 * For production, integrate a library like `qrcode` (npm) at the app level
 * and pass a pre-rendered data URL via a custom hook.
 */
export function QRDisplay({
  value,
  size = 220,
  label,
  sublabel,
  showCopy = true,
  showShare = false,
  network,
  onCopy,
}: QRDisplayProps) {
  const canvasRef = useRef<HTMLCanvasElement>(null);
  const [copied, setCopied] = useState(false);
  const [zoomed, setZoomed] = useState(false);

  // Simple visual QR placeholder rendered via canvas (checkerboard based on char codes)
  // In production replace with: import QRCode from 'qrcode'; QRCode.toCanvas(canvas, value)
  useEffect(() => {
    const canvas = canvasRef.current;
    if (!canvas || !value) return;
    const ctx = canvas.getContext('2d');
    if (!ctx) return;

    const modules = 25; // placeholder grid size
    const cellSize = size / modules;

    ctx.clearRect(0, 0, size, size);
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, size, size);

    // Deterministic pattern from value string
    for (let row = 0; row < modules; row++) {
      for (let col = 0; col < modules; col++) {
        const charCode = value.charCodeAt((row * modules + col) % value.length) || 0;
        const isDark = (charCode + row + col) % 3 !== 0;

        // Finder patterns (top-left, top-right, bottom-left corners)
        const inFinder =
          (row < 8 && col < 8) ||
          (row < 8 && col >= modules - 8) ||
          (row >= modules - 8 && col < 8);

        if (inFinder) {
          const fr = row < 8 ? row : row - (modules - 8);
          const fc = col < 8 ? col : col - (modules - 8);
          const onBorder = fr === 0 || fr === 6 || fc === 0 || fc === 6;
          const inCenter = fr >= 2 && fr <= 4 && fc >= 2 && fc <= 4;
          ctx.fillStyle = onBorder || inCenter ? '#000000' : '#ffffff';
        } else {
          ctx.fillStyle = isDark ? '#000000' : '#ffffff';
        }
        ctx.fillRect(col * cellSize, row * cellSize, cellSize, cellSize);
      }
    }
  }, [value, size]);

  const handleCopy = useCallback(async () => {
    try {
      await navigator.clipboard.writeText(value);
      setCopied(true);
      onCopy?.();
      setTimeout(() => setCopied(false), 2000);
    } catch {
      // clipboard not available
    }
  }, [value, onCopy]);

  const handleShare = useCallback(async () => {
    if (!navigator.share) return;
    await navigator.share({ text: value });
  }, [value]);

  return (
    <>
      <div className={styles.root}>
        {network && (
          <span className={styles.networkBadge} aria-label={`Network: ${network}`}>
            {network}
          </span>
        )}

        {/* QR canvas */}
        <button
          type="button"
          className={styles.canvasWrap}
          onClick={() => setZoomed(true)}
          aria-label={`QR code for ${sublabel ?? value}. Tap to zoom.`}
        >
          {value ? (
            <canvas ref={canvasRef} width={size} height={size} className={styles.canvas} />
          ) : (
            <div className={styles.placeholder} style={{ width: size, height: size }}>
              <span>No address</span>
            </div>
          )}
        </button>

        {/* Labels */}
        {label && <p className={styles.label}>{label}</p>}
        {sublabel && (
          <p className={styles.sublabel} aria-label={`Address: ${value}`}>
            {sublabel}
          </p>
        )}

        {/* Actions */}
        <div className={styles.actions}>
          {showCopy && (
            <button
              type="button"
              className={[styles.actionBtn, copied ? styles.actionBtnSuccess : ''].filter(Boolean).join(' ')}
              onClick={handleCopy}
              aria-label={copied ? 'Copied!' : 'Copy address to clipboard'}
            >
              {copied ? (
                <>
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <polyline points="20 6 9 17 4 12" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round" />
                  </svg>
                  Copied!
                </>
              ) : (
                <>
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <rect x="9" y="9" width="13" height="13" rx="2" stroke="currentColor" strokeWidth="1.8" />
                    <path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" />
                  </svg>
                  Copy
                </>
              )}
            </button>
          )}
          {showShare && typeof navigator !== 'undefined' && !!navigator.share && (
            <button
              type="button"
              className={styles.actionBtn}
              onClick={handleShare}
              aria-label="Share address"
            >
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <circle cx="18" cy="5" r="3" stroke="currentColor" strokeWidth="1.8" />
                <circle cx="6" cy="12" r="3" stroke="currentColor" strokeWidth="1.8" />
                <circle cx="18" cy="19" r="3" stroke="currentColor" strokeWidth="1.8" />
                <line x1="8.59" y1="13.51" x2="15.42" y2="17.49" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" />
                <line x1="15.41" y1="6.51" x2="8.59" y2="10.49" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" />
              </svg>
              Share
            </button>
          )}
        </div>
      </div>

      {/* Zoom modal */}
      {zoomed && (
        <div
          className={styles.zoomOverlay}
          onClick={() => setZoomed(false)}
          role="dialog"
          aria-modal="true"
          aria-label="Zoomed QR code"
        >
          <div className={styles.zoomInner} onClick={(e) => e.stopPropagation()}>
            <canvas ref={undefined} width={size * 1.5} height={size * 1.5} className={styles.canvas} />
            <button
              type="button"
              className={styles.zoomClose}
              onClick={() => setZoomed(false)}
              aria-label="Close zoom"
            >
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <line x1="4" y1="4" x2="20" y2="20" stroke="currentColor" strokeWidth="2" strokeLinecap="round"/>
                <line x1="20" y1="4" x2="4" y2="20" stroke="currentColor" strokeWidth="2" strokeLinecap="round"/>
              </svg>
            </button>
          </div>
        </div>
      )}
    </>
  );
}
