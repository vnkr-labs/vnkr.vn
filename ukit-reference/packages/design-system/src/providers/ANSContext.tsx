import React, {
  createContext,
  useContext,
  useMemo,
  type ReactNode,
} from 'react';

export interface ANSResolverConfig {
  rpcUrl: string;
  contracts: {
    ansRegistry: string;
  };
  /** LRU cache TTL in milliseconds. Default: 50ms */
  cacheTtlMs?: number;
}

export interface ANSContextValue {
  config: ANSResolverConfig | null;
  /** Resolve a name to an address. Returns null if unavailable. */
  resolve: (name: string) => Promise<string | null>;
  /** Reverse-resolve an address to a name. Returns null if unavailable. */
  reverseResolve: (address: string) => Promise<string | null>;
}

const noopResolve = async () => null;

export const ANSContext = createContext<ANSContextValue>({
  config: null,
  resolve: noopResolve,
  reverseResolve: noopResolve,
});

interface ANSProviderProps {
  config: ANSResolverConfig | null;
  children: ReactNode;
}

export function ANSProvider({ config, children }: ANSProviderProps) {
  const value = useMemo<ANSContextValue>(() => {
    if (!config) {
      return { config: null, resolve: noopResolve, reverseResolve: noopResolve };
    }

    // Simple LRU-style cache using Map (insertion order)
    const cache = new Map<string, { value: string | null; ts: number }>();
    const ttl = config.cacheTtlMs ?? 50;

    const cached = async (key: string, fetcher: () => Promise<string | null>) => {
      const hit = cache.get(key);
      if (hit && Date.now() - hit.ts < ttl) return hit.value;
      const value = await fetcher().catch(() => null);
      cache.set(key, { value, ts: Date.now() });
      // Evict old entries (keep last 500)
      if (cache.size > 500) {
        const firstKey = cache.keys().next().value;
        if (firstKey !== undefined) cache.delete(firstKey);
      }
      return value;
    };

    const resolve = (name: string) =>
      cached(`fwd:${name}`, async () => {
        // Placeholder — replaced by real @axioledger/ans-resolver call
        // when injected via AxioProvider config
        return null;
      });

    const reverseResolve = (address: string) =>
      cached(`rev:${address}`, async () => null);

    return { config, resolve, reverseResolve };
  }, [config]);

  return <ANSContext.Provider value={value}>{children}</ANSContext.Provider>;
}

export function useANSContext(): ANSContextValue {
  return useContext(ANSContext);
}
