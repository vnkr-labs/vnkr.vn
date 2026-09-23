/**
 * Zone 8 — System States & Edge Cases
 * NetworkErrorScreen | ServerErrorScreen | SessionExpiredScreen
 * EmptyHistoryScreen | EmptyPortfolioScreen | EmptyCardsScreen
 * JailbreakWarningScreen | AccountBlockedScreen
 */
'use client';
import React from 'react';

// Inline SVG state icons — no emoji, no external deps
const StateIcon = {
  noSignal: (
    <svg width="72" height="72" viewBox="0 0 24 24" fill="none" color="#8f9bb3" aria-hidden="true">
      <path d="M12 20h.01M2 8.82a15 15 0 0120.18 0M5.41 12a10 10 0 0113.2 0M8.53 15.18a6 6 0 016.95 0" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/>
      <line x1="2" y1="2" x2="22" y2="22" stroke="#ff3d71" strokeWidth="1.8" strokeLinecap="round"/>
    </svg>
  ),
  warning: (
    <svg width="72" height="72" viewBox="0 0 24 24" fill="none" color="#fc7339" aria-hidden="true">
      <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" stroke="currentColor" strokeWidth="1.5" strokeLinejoin="round"/>
      <line x1="12" y1="9" x2="12" y2="13" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round"/>
      <circle cx="12" cy="17" r="0.5" fill="currentColor" stroke="currentColor" strokeWidth="1.2"/>
    </svg>
  ),
  clock: (
    <svg width="72" height="72" viewBox="0 0 24 24" fill="none" color="#8f9bb3" aria-hidden="true">
      <circle cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="1.5"/>
      <polyline points="12 6 12 12 16 14" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round"/>
    </svg>
  ),
  inbox: (
    <svg width="72" height="72" viewBox="0 0 24 24" fill="none" color="#8f9bb3" aria-hidden="true">
      <polyline points="22 12 16 12 14 15 10 15 8 12 2 12" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/>
      <path d="M5.45 5.11L2 12v6a2 2 0 002 2h16a2 2 0 002-2v-6l-3.45-6.89A2 2 0 0016.76 4H7.24a2 2 0 00-1.79 1.11z" stroke="currentColor" strokeWidth="1.5" strokeLinejoin="round"/>
    </svg>
  ),
  btc: (
    <svg width="72" height="72" viewBox="0 0 24 24" fill="none" color="#f7931a" aria-hidden="true">
      <circle cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="1.5"/>
      <path d="M9 8h4.5a2 2 0 010 4H9m0 0h5a2 2 0 010 4H9M9 8V6m0 10v2m3-12v2m0 10v2" stroke="currentColor" strokeWidth="1.4" strokeLinecap="round"/>
    </svg>
  ),
  card: (
    <svg width="72" height="72" viewBox="0 0 24 24" fill="none" color="#8f9bb3" aria-hidden="true">
      <rect x="2" y="5" width="20" height="14" rx="2" stroke="currentColor" strokeWidth="1.5"/>
      <path d="M2 10h20" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round"/>
      <rect x="5" y="14" width="4" height="2" rx="0.5" fill="currentColor"/>
    </svg>
  ),
  shield: (
    <svg width="72" height="72" viewBox="0 0 24 24" fill="none" color="#ff3d71" aria-hidden="true">
      <path d="M12 2L3 7v5c0 5.25 3.75 10.15 9 11.33C17.25 22.15 21 17.25 21 12V7l-9-5z" stroke="currentColor" strokeWidth="1.5" strokeLinejoin="round"/>
      <line x1="8" y1="12" x2="16" y2="12" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round"/>
    </svg>
  ),
  lock: (
    <svg width="72" height="72" viewBox="0 0 24 24" fill="none" color="#ff3d71" aria-hidden="true">
      <rect x="3" y="11" width="18" height="11" rx="2" stroke="currentColor" strokeWidth="1.5"/>
      <path d="M7 11V7a5 5 0 0110 0v4" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round"/>
      <circle cx="12" cy="16" r="1.5" fill="currentColor"/>
    </svg>
  ),
};

const S: Record<string, React.CSSProperties> = {
  root:        { minHeight: '100svh', display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center', padding: '40px 24px', background: '#fff', fontFamily: 'system-ui,sans-serif', textAlign: 'center', gap: 16 },
  icon:        { lineHeight: 1, display: 'flex', alignItems: 'center', justifyContent: 'center' },
  title:       { fontSize: 22, fontWeight: 700, color: '#1f2328', margin: 0 },
  body:        { fontSize: 14, color: '#57606a', maxWidth: 280, margin: 0, lineHeight: 1.6 },
  code:        { fontSize: 12, fontFamily: 'monospace', color: '#8f9bb3', background: '#f7f8fa', padding: '4px 10px', borderRadius: 6 },
  primaryBtn:  { width: '100%', maxWidth: 320, height: 56, background: '#0d1117', color: '#fff', border: 'none', borderRadius: 9999, fontSize: 16, fontWeight: 700, cursor: 'pointer' },
  secondaryBtn:{ width: '100%', maxWidth: 320, height: 48, background: 'none', color: '#57606a', border: '1.5px solid #e4e9f2', borderRadius: 9999, fontSize: 14, fontWeight: 600, cursor: 'pointer' },
  dangerBtn:   { width: '100%', maxWidth: 320, height: 56, background: '#ff3d71', color: '#fff', border: 'none', borderRadius: 9999, fontSize: 16, fontWeight: 700, cursor: 'pointer' },
  ctaBtn:      { width: '100%', maxWidth: 320, height: 56, background: '#49dbc8', color: '#000', border: 'none', borderRadius: 9999, fontSize: 16, fontWeight: 700, cursor: 'pointer' },
};

export function NetworkErrorScreen({ onRetry }: { onRetry?: () => void }) {
  return (
    <div style={S.root}>
      <span style={S.icon}>{StateIcon.noSignal}</span>
      <h1 style={S.title}>No Internet Connection</h1>
      <p style={S.body}>Check your Wi-Fi or mobile data and try again.</p>
      <button style={S.primaryBtn} onClick={onRetry}>Try Again</button>
    </div>
  );
}

export function ServerErrorScreen({ onRetry, onSupport }: { onRetry?: () => void; onSupport?: () => void }) {
  return (
    <div style={S.root}>
      <span style={S.icon}>{StateIcon.warning}</span>
      <h1 style={S.title}>Something went wrong</h1>
      <p style={S.body}>Our servers are having a moment. Your funds are safe.</p>
      <span style={S.code}>Error 500 — Internal Server Error</span>
      <button style={S.primaryBtn} onClick={onRetry}>Try Again</button>
      <button style={S.secondaryBtn} onClick={onSupport}>Contact Support</button>
    </div>
  );
}

export function SessionExpiredScreen({ onLogin }: { onLogin?: () => void }) {
  return (
    <div style={S.root}>
      <span style={S.icon}>{StateIcon.clock}</span>
      <h1 style={S.title}>Session Expired</h1>
      <p style={S.body}>You've been logged out for your security. Please sign in again.</p>
      <button style={S.primaryBtn} onClick={onLogin}>Sign In Again</button>
    </div>
  );
}

export function EmptyHistoryScreen({ onTransfer }: { onTransfer?: () => void }) {
  return (
    <div style={S.root}>
      <span style={S.icon}>{StateIcon.inbox}</span>
      <h1 style={S.title}>No Transactions Yet</h1>
      <p style={S.body}>Make your first transfer or payment to see your history here.</p>
      <button style={S.ctaBtn} onClick={onTransfer}>Make a Transfer →</button>
    </div>
  );
}

export function EmptyPortfolioScreen({ onBuy }: { onBuy?: () => void }) {
  return (
    <div style={S.root}>
      <span style={S.icon}>{StateIcon.btc}</span>
      <h1 style={S.title}>No Crypto Yet</h1>
      <p style={S.body}>Buy or receive crypto to start building your portfolio.</p>
      <button style={S.ctaBtn} onClick={onBuy}>Buy Crypto →</button>
    </div>
  );
}

export function EmptyCardsScreen({ onIssue }: { onIssue?: () => void }) {
  return (
    <div style={S.root}>
      <span style={S.icon}>{StateIcon.card}</span>
      <h1 style={S.title}>No Cards Yet</h1>
      <p style={S.body}>Issue a virtual card to start spending anywhere online.</p>
      <button style={S.ctaBtn} onClick={onIssue}>Issue My First Card →</button>
    </div>
  );
}

export function JailbreakWarningScreen() {
  return (
    <div style={{ ...S.root, background: '#0d1117', color: '#fff' }}>
      <span style={S.icon}>{StateIcon.shield}</span>
      <h1 style={{ ...S.title, color: '#ff3d71' }}>Security Risk Detected</h1>
      <p style={{ ...S.body, color: 'rgba(255,255,255,0.7)' }}>
        This device appears to be jailbroken or rooted. For your security, Axiopass cannot run on
        modified devices.
      </p>
      <p style={{ ...S.code, color: '#ff3d71', background: 'rgba(255,61,113,0.1)' }}>
        SECURITY_DEVICE_COMPROMISED
      </p>
      {/* Intentionally no bypass option */}
    </div>
  );
}

export function AccountBlockedScreen({ reason, onSupport }: { reason?: string; onSupport?: () => void }) {
  return (
    <div style={S.root}>
      <span style={S.icon}>{StateIcon.lock}</span>
      <h1 style={S.title}>Account Suspended</h1>
      <p style={S.body}>
        {reason ?? 'Your account has been temporarily suspended pending a security review.'}
      </p>
      <div style={{ background: '#fff2f2', border: '1px solid #ffd6d9', borderRadius: 10, padding: '12px 16px', maxWidth: 300, fontSize: 13, color: '#b81d5b', lineHeight: 1.5 }}>
        If you believe this is an error, contact our support team. We aim to resolve all cases within 48 hours.
      </div>
      <button style={S.dangerBtn} onClick={onSupport}>Contact Support</button>
    </div>
  );
}
