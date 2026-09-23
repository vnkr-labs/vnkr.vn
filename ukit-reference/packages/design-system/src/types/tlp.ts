/**
 * TLP — Trust Level Protocol
 * Maps namespace suffixes to visual trust indicators.
 *
 * Chain:  name → resolveTLPLevel() → TLPLevel → TLP_CONFIG → CSS var
 */

export type TLPLevel = 'safe' | 'caution' | 'blocked' | 'system';

export interface TLPConfig {
  level: TLPLevel;
  /** CSS custom property — e.g. var(--tlp-safe) */
  color: string;
  /** Background CSS custom property */
  bgColor: string;
  /** Text CSS custom property (accessible contrast) */
  textColor: string;
  /** Human-readable label */
  label: string;
  /** ARIA description for screen readers */
  ariaDescription: string;
}

export const TLP_CONFIG: Record<TLPLevel, TLPConfig> = {
  safe: {
    level: 'safe',
    color: 'var(--tlp-safe)',
    bgColor: 'var(--tlp-safe-bg)',
    textColor: 'var(--tlp-safe-text)',
    label: 'Safe',
    ariaDescription: 'Verified AXQ ecosystem namespace — safe to interact',
  },
  caution: {
    level: 'caution',
    color: 'var(--tlp-caution)',
    bgColor: 'var(--tlp-caution-bg)',
    textColor: 'var(--tlp-caution-text)',
    label: 'Caution',
    ariaDescription: 'DeFi namespace — verify before signing transactions',
  },
  blocked: {
    level: 'blocked',
    color: 'var(--tlp-blocked)',
    bgColor: 'var(--tlp-blocked-bg)',
    textColor: 'var(--tlp-blocked-text)',
    label: 'Unverified',
    ariaDescription: 'Unknown or unverified address — do not sign without verification',
  },
  system: {
    level: 'system',
    color: 'var(--tlp-system)',
    bgColor: 'var(--tlp-system-bg)',
    textColor: 'var(--tlp-system-text)',
    label: 'System',
    ariaDescription: 'System or infrastructure namespace',
  },
};

/**
 * Resolves a name or address string to a TLP trust level.
 *
 * Rules (in priority order):
 *  .axq / .vrq  → safe    (verified AXQ ecosystem)
 *  .kpx         → caution (DeFi bridge — risk disclaimer required)
 *  .sqx / .vpx  → system  (infrastructure)
 *  0x...        → blocked (raw EVM address, unresolved)
 *  anything else → blocked
 *
 * @example
 *   resolveTLPLevel('alice.axq')    // 'safe'
 *   resolveTLPLevel('pool.kpx')     // 'caution'
 *   resolveTLPLevel('relay.sqx')    // 'system'
 *   resolveTLPLevel('0xABCDEF...')  // 'blocked'
 */
export function resolveTLPLevel(name: string): TLPLevel {
  if (!name || typeof name !== 'string') return 'blocked';

  const trimmed = name.trim().toLowerCase();

  // Verified AXQ ecosystem namespaces
  if (/\.(axq|vrq)$/.test(trimmed)) return 'safe';

  // DeFi bridge — risk disclaimer before first transaction
  if (/\.kpx$/.test(trimmed)) return 'caution';

  // Infrastructure / system namespaces
  if (/\.(sqx|vpx)$/.test(trimmed)) return 'system';

  // Raw EVM hex address (unresolved) — always blocked until ANS resolves
  if (/^0x[0-9a-f]{40,}$/i.test(trimmed)) return 'blocked';

  // Unknown — treat as blocked
  return 'blocked';
}

/**
 * Returns the full TLPConfig for a given name/address.
 */
export function getTLPConfig(name: string): TLPConfig {
  return TLP_CONFIG[resolveTLPLevel(name)];
}
