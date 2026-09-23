import { useThemeContext } from '../providers/ThemeContext';
import type { Theme, ResolvedTheme } from '../providers/ThemeContext';

export type { Theme, ResolvedTheme };

/**
 * useTheme
 *
 * Returns current theme, resolved theme, and a setter.
 *
 * @example
 * const { resolvedTheme, setTheme } = useTheme();
 * setTheme('dark');
 */
export function useTheme() {
  return useThemeContext();
}
