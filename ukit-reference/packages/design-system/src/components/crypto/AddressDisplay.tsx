import React, { useEffect, useState } from 'react';
import { useANSResolver } from '../../hooks/useANSResolver';
import { resolveTLPLevel } from '../../types/tlp';
import styles from './AddressDisplay.module.css';

export interface AddressDisplayProps {
  address: string;
  /** Force short display (truncated). Default: false */
  truncate?: boolean;
  /** Show copy button */
  copyable?: boolean;
  size?: 'sm' | 'md';
}

function truncateAddress(addr: string): string {
  if (addr.length <= 12) return addr;
  return `${addr.slice(0, 6)}…${addr.slice(-4)}`;
}

/**
 * AddressDisplay
 *
 * SECURITY RULE: Never renders raw 0x address without warning icon
 * when ANS resolver is configured but fails to resolve.
 */
export function AddressDisplay({
  address,
  truncate = false,
  copyable = true,
  size = 'md',
}: AddressDisplayProps) {
  const { resolve, isConfigured } = useANSResolver();
  const [resolved, setResolved] = useState<string | null>(null);
  const [loading, setLoading] = useState(isConfigured);
  const [copied, setCopied] = useState(false);

  useEffect(() => {
    if (!isConfigured) { setLoading(false); return; }
    setLoading(true);
    resolve(address).then((name) => {
      setResolved(name);
      setLoading(false);
    });
  }, [address, isConfigured, resolve]);

  const displayName = resolved ?? (truncate ? truncateAddress(address) : address);
  const isRawAddress = !resolved && /^0x/i.test(address);
  const tlpLevel = resolved ? resolveTLPLevel(resolved) : 'blocked';

  const handleCopy = async () => {
    await navigator.clipboard.writeText(address);
    setCopied(true);
    setTimeout(() => setCopied(false), 1500);
  };

  return (
    <span className={styles.root} data-size={size} data-level={tlpLevel}>
      {loading && <span className={styles.skeleton} aria-label="Resolving address…" />}

      {!loading && (
        <>
          {/* Warning icon for unresolved raw hex */}
          {isRawAddress && isConfigured && (
            <span className={styles.warn} aria-label="Unverified address" title="ANS resolution failed — raw address">
              ⚠️
            </span>
          )}
          <span className={styles.name}>{displayName}</span>
        </>
      )}

      {copyable && !loading && (
        <button
          type="button"
          className={styles.copyBtn}
          onClick={handleCopy}
          aria-label={copied ? 'Copied!' : 'Copy address'}
        >
          {copied ? (
            <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
              <path d="M2 6L5 9L10 3" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/>
            </svg>
          ) : (
            <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
              <rect x="4" y="4" width="7" height="7" rx="1" stroke="currentColor" strokeWidth="1.2"/>
              <path d="M3 8H2C1.45 8 1 7.55 1 7V2C1 1.45 1.45 1 2 1H7C7.55 1 8 1.45 8 2V3" stroke="currentColor" strokeWidth="1.2"/>
            </svg>
          )}
        </button>
      )}
    </span>
  );
}
