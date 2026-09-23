/**
 * @axioledger/ans-resolver
 * Axio Name Service — Client-side domain resolution SDK
 * License: MIT
 */

// Supported TLDs
export const VALID_TLDS = ['.axq', '.vpx', '.sqx', '.kpx', '.vrq'] as const;
export type TLD = (typeof VALID_TLDS)[number];

export interface DomainRecord {
  fqdn: string;            // e.g. "alice.axq"
  owner: string;           // Base58 public key
  resolver: string;        // Resolved address or ZK-DID
  registeredAt: number;    // Unix timestamp
  expiresAt: number;       // Unix timestamp
  isActive: boolean;
  tld: TLD;
}

export interface ResolverConfig {
  rpcUrl: string;
  programId: string;
  network: 'localnet' | 'devnet' | 'testnet' | 'mainnet';
}

/**
 * ANSResolver — resolves ANS domain names to addresses
 *
 * @example
 * const resolver = new ANSResolver({ rpcUrl: 'https://rpc.axioledger.network', ... });
 * const record = await resolver.resolve('alice.axq');
 * console.log(record.resolver); // → "7Xf8..."
 */
export class ANSResolver {
  private config: ResolverConfig;

  constructor(config: ResolverConfig) {
    this.config = config;
  }

  /**
   * Resolve a fully-qualified domain name to its DomainRecord
   * @param fqdn - e.g. "alice.axq" or "mybusiness.vpx"
   */
  async resolve(fqdn: string): Promise<DomainRecord | null> {
    const parsed = this.parse(fqdn);
    if (!parsed) return null;

    // TODO: derive PDA from label + TLD, fetch account from RPC
    console.log(`[ANSResolver] Resolving ${fqdn} on ${this.config.network}`);
    return null;
  }

  /**
   * Check if a domain name is available for registration
   */
  async isAvailable(fqdn: string): Promise<boolean> {
    const record = await this.resolve(fqdn);
    if (!record) return true;
    const now = Math.floor(Date.now() / 1000);
    return !record.isActive || record.expiresAt < now;
  }

  /**
   * Parse a FQDN into label + TLD
   * @returns { label, tld } or null if invalid
   */
  parse(fqdn: string): { label: string; tld: TLD } | null {
    const lower = fqdn.toLowerCase().trim();
    for (const tld of VALID_TLDS) {
      if (lower.endsWith(tld)) {
        const label = lower.slice(0, lower.length - tld.length);
        if (label.length >= 3 && label.length <= 63) {
          return { label, tld };
        }
      }
    }
    return null;
  }

  /**
   * Validate a domain label against SecurityGuard rules
   */
  static isLabelSafe(label: string): boolean {
    const RESERVED = new Set([
      'axioledger', 'admin', 'root', 'system', 'dao',
      'treasury', 'guardian', 'council', 'foundation',
      'sol', 'eth', 'btc', 'usdc', 'usdt',
      'null', 'undefined', 'test', 'localhost',
    ]);
    const lower = label.toLowerCase();
    if (RESERVED.has(lower)) return false;
    if (/^\d+$/.test(lower)) return false;  // purely numeric
    if (lower.length < 3 || lower.length > 63) return false;
    return true;
  }
}

// Convenience export
export default ANSResolver;
