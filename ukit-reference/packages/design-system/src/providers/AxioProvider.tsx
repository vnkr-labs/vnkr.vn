import React, { type ReactNode } from 'react';
import { ThemeProvider, type Theme } from './ThemeContext';
import { ANSProvider, type ANSResolverConfig } from './ANSContext';

export interface AxioProviderProps {
  /**
   * Color theme.
   * - 'light' / 'dark'  — forced
   * - 'system'           — follows OS prefers-color-scheme (default)
   */
  theme?: Theme;
  /**
   * ANS resolver configuration.
   * Omit to disable ANS resolution (AddressDisplay will show raw address with warning).
   */
  ansResolverConfig?: ANSResolverConfig;
  children: ReactNode;
}

/**
 * AxioProvider
 *
 * Wraps your app (or page) with:
 *  - Theme context + data-theme attribute on <html>
 *  - ANS resolver context
 *
 * Usage:
 * ```tsx
 * <AxioProvider theme="system" ansResolverConfig={{ rpcUrl, contracts }}>
 *   <App />
 * </AxioProvider>
 * ```
 */
export function AxioProvider({
  theme = 'system',
  ansResolverConfig,
  children,
}: AxioProviderProps) {
  return (
    <ThemeProvider theme={theme}>
      <ANSProvider config={ansResolverConfig ?? null}>
        {children}
      </ANSProvider>
    </ThemeProvider>
  );
}
