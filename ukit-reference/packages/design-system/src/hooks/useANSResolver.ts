import { useANSContext } from '../providers/ANSContext';

/**
 * useANSResolver
 *
 * Provides ANS forward and reverse resolution with built-in LRU cache.
 * Returns null for both functions when AxioProvider has no ansResolverConfig.
 *
 * @example
 * const { resolve, reverseResolve } = useANSResolver();
 * const address = await resolve('alice.axq');   // '0xABC...' or null
 * const name    = await reverseResolve('0xABC'); // 'alice.axq' or null
 */
export function useANSResolver() {
  const { resolve, reverseResolve, config } = useANSContext();
  return { resolve, reverseResolve, isConfigured: config !== null };
}
