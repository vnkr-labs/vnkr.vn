import React from 'react';
import styles from './LivenessFrame.module.css';

export type LivenessStep = 'align' | 'blink' | 'turn_left' | 'turn_right' | 'smile' | 'done' | 'error';

export interface LivenessFrameProps {
  /** Current liveness step instruction */
  step?: LivenessStep;
  /** 0–100 progress for the circular ring */
  progress?: number;
  /** Show scanning line animation */
  scanning?: boolean;
  /** Error/fail message */
  errorMessage?: string;
  /** Custom instruction override */
  instruction?: string;
}

const STEP_CONFIG: Record<
  LivenessStep,
  { label: string; icon: React.ReactNode; color: string }
> = {
  align:      { label: 'Position your face in the frame', icon: <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M12 2a10 10 0 100 20A10 10 0 0012 2zm0 4a4 4 0 110 8 4 4 0 010-8z" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round"/></svg>, color: 'var(--color-border-focus)' },
  blink:      { label: 'Blink your eyes',                  icon: <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" strokeWidth="1.5"/><circle cx="12" cy="12" r="3" stroke="currentColor" strokeWidth="1.5"/></svg>, color: 'var(--color-accent-teal)' },
  turn_left:  { label: 'Turn your head left',              icon: <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M15 18l-6-6 6-6" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/></svg>, color: 'var(--color-accent-teal)' },
  turn_right: { label: 'Turn your head right',             icon: <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M9 18l6-6-6-6" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/></svg>, color: 'var(--color-accent-teal)' },
  smile:      { label: 'Smile',                            icon: <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="1.5"/><path d="M8 14s1.5 2 4 2 4-2 4-2" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round"/><circle cx="9" cy="10" r="1" fill="currentColor"/><circle cx="15" cy="10" r="1" fill="currentColor"/></svg>, color: 'var(--color-accent-teal)' },
  done:       { label: 'Liveness verified!',               icon: <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="1.5"/><polyline points="7 12 10.5 15.5 17 8.5" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/></svg>, color: 'var(--color-status-success-default)' },
  error:      { label: 'Verification failed',              icon: <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="1.5"/><line x1="8" y1="8" x2="16" y2="16" stroke="currentColor" strokeWidth="2" strokeLinecap="round"/><line x1="16" y1="8" x2="8" y2="16" stroke="currentColor" strokeWidth="2" strokeLinecap="round"/></svg>, color: 'var(--color-status-error-default)' },
};

/**
 * LivenessFrame
 *
 * Camera overlay UI for eKYC active liveness detection.
 * This is a pure UI component — wire up actual camera/liveness SDK behind it.
 * Shows: oval face guide, animated ring progress, step instruction, scanning beam.
 */
export function LivenessFrame({
  step = 'align',
  progress = 0,
  scanning = false,
  errorMessage,
  instruction,
}: LivenessFrameProps) {
  const config = STEP_CONFIG[step];
  const circumference = 2 * Math.PI * 110; // r=110
  const strokeDashoffset = circumference - (progress / 100) * circumference;

  return (
    <div className={styles.root} role="status" aria-live="polite" aria-label="Liveness check">
      {/* Dimmed background corners */}
      <div className={styles.dimOverlay} aria-hidden="true" />

      {/* Oval cutout */}
      <div className={styles.ovalWrap} aria-hidden="true">
        <svg
          className={styles.ovalSvg}
          viewBox="0 0 260 320"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          {/* Progress ring around oval */}
          <ellipse
            cx="130" cy="160" rx="110" ry="140"
            stroke="var(--color-border-default)"
            strokeWidth="2"
            fill="none"
            opacity="0.4"
          />
          <ellipse
            cx="130" cy="160" rx="110" ry="140"
            stroke={config.color}
            strokeWidth="3"
            fill="none"
            strokeDasharray={`${circumference}`}
            strokeDashoffset={`${strokeDashoffset}`}
            strokeLinecap="round"
            style={{ transform: 'rotate(-90deg)', transformOrigin: '130px 160px', transition: 'stroke-dashoffset 0.4s ease, stroke 0.3s ease' }}
          />

          {/* Corner markers */}
          {[
            'M 30 80 L 30 40 L 70 40',
            'M 190 40 L 230 40 L 230 80',
            'M 30 240 L 30 280 L 70 280',
            'M 190 280 L 230 280 L 230 240',
          ].map((d, i) => (
            <path key={i} d={d} stroke={config.color} strokeWidth="3" strokeLinecap="round" strokeLinejoin="round" />
          ))}
        </svg>

        {/* Scanning beam */}
        {scanning && step !== 'done' && step !== 'error' && (
          <div className={styles.scanBeam} aria-hidden="true" />
        )}

        {/* Done checkmark overlay */}
        {step === 'done' && (
          <div className={styles.doneOverlay} aria-hidden="true">
            <svg width="56" height="56" viewBox="0 0 56 56" fill="none">
              <circle cx="28" cy="28" r="28" fill="var(--color-status-success-default)" fillOpacity="0.15" />
              <polyline
                points="16,28 24,36 40,20"
                stroke="var(--color-status-success-default)"
                strokeWidth="3"
                strokeLinecap="round"
                strokeLinejoin="round"
              />
            </svg>
          </div>
        )}
      </div>

      {/* Step icon */}
      <div
        className={styles.stepIcon}
        style={{ color: config.color }}
        aria-hidden="true"
      >
        {config.icon}
      </div>

      {/* Instruction text */}
      <div className={styles.instructionWrap}>
        <p className={styles.instruction} style={{ color: config.color }}>
          {instruction ?? config.label}
        </p>
        {step === 'error' && errorMessage && (
          <p className={styles.errorMsg}>{errorMessage}</p>
        )}
      </div>

      {/* Progress percentage */}
      {step !== 'done' && step !== 'error' && (
        <div className={styles.progressText} aria-hidden="true">
          {Math.round(progress)}%
        </div>
      )}
    </div>
  );
}
