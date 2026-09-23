import { useCallback, useRef, useState } from 'react';

export type ToastVariant = 'success' | 'error' | 'warning' | 'info' | 'default';

export interface ToastOptions {
  /** Auto-dismiss duration in ms. Default: 3000 (success/info/default) | 5000 (error) */
  duration?: number;
  /** Optional action button */
  action?: { label: string; onClick: () => void };
}

export interface ToastItem {
  id: string;
  variant: ToastVariant;
  message: string;
  options?: ToastOptions;
}

const DEFAULT_DURATION: Record<ToastVariant, number> = {
  success: 3000,
  info:    3000,
  default: 3000,
  warning: 4000,
  error:   5000,
};

/**
 * useToast
 *
 * Manages a queue of toast notifications.
 * Pair with <ToastContainer /> to render them.
 *
 * @example
 * const { toasts, toast, dismiss } = useToast();
 * toast.success('Saved!');
 * toast.error('Something went wrong', { duration: 8000 });
 */
export function useToast() {
  const [toasts, setToasts] = useState<ToastItem[]>([]);
  const timers = useRef<Map<string, ReturnType<typeof setTimeout>>>(new Map());

  const dismiss = useCallback((id: string) => {
    clearTimeout(timers.current.get(id));
    timers.current.delete(id);
    setToasts((prev) => prev.filter((t) => t.id !== id));
  }, []);

  const add = useCallback(
    (variant: ToastVariant, message: string, options?: ToastOptions) => {
      const id = `toast-${Date.now()}-${Math.random().toString(36).slice(2)}`;
      const duration = options?.duration ?? DEFAULT_DURATION[variant];

      setToasts((prev) => [...prev, { id, variant, message, options }]);

      const timer = setTimeout(() => dismiss(id), duration);
      timers.current.set(id, timer);

      return id;
    },
    [dismiss]
  );

  const toast = {
    success: (message: string, options?: ToastOptions) => add('success', message, options),
    error:   (message: string, options?: ToastOptions) => add('error',   message, options),
    warning: (message: string, options?: ToastOptions) => add('warning', message, options),
    info:    (message: string, options?: ToastOptions) => add('info',    message, options),
    default: (message: string, options?: ToastOptions) => add('default', message, options),
  };

  return { toasts, toast, dismiss };
}
