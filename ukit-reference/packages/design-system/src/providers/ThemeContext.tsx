import React, {
  createContext,
  useContext,
  useEffect,
  useState,
  type ReactNode,
} from 'react';

export type Theme = 'light' | 'dark' | 'system';
export type ResolvedTheme = 'light' | 'dark';

interface ThemeContextValue {
  theme: Theme;
  resolvedTheme: ResolvedTheme;
  setTheme: (theme: Theme) => void;
}

export const ThemeContext = createContext<ThemeContextValue>({
  theme: 'system',
  resolvedTheme: 'light',
  setTheme: () => {},
});

interface ThemeProviderProps {
  theme: Theme;
  children: ReactNode;
}

export function ThemeProvider({ theme, children }: ThemeProviderProps) {
  const [resolvedTheme, setResolvedTheme] = useState<ResolvedTheme>('light');

  useEffect(() => {
    if (theme === 'system') {
      const mq = window.matchMedia('(prefers-color-scheme: dark)');
      const apply = (dark: boolean) => {
        const resolved: ResolvedTheme = dark ? 'dark' : 'light';
        setResolvedTheme(resolved);
        document.documentElement.setAttribute('data-theme', resolved);
      };
      apply(mq.matches);
      mq.addEventListener('change', (e) => apply(e.matches));
      return () => mq.removeEventListener('change', (e) => apply(e.matches));
    } else {
      setResolvedTheme(theme);
      document.documentElement.setAttribute('data-theme', theme);
    }
  }, [theme]);

  const [currentTheme, setCurrentTheme] = useState<Theme>(theme);

  const setTheme = (next: Theme) => {
    setCurrentTheme(next);
  };

  return (
    <ThemeContext.Provider value={{ theme: currentTheme, resolvedTheme, setTheme }}>
      {children}
    </ThemeContext.Provider>
  );
}

export function useThemeContext(): ThemeContextValue {
  return useContext(ThemeContext);
}
