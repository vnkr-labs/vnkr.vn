import React, {
  useState,
  useRef,
  useEffect,
  useId,
  type ReactNode,
} from 'react';
import styles from './Tooltip.module.css';

export type TooltipPlacement = 'top' | 'bottom' | 'left' | 'right';

export interface TooltipProps {
  content: ReactNode;
  placement?: TooltipPlacement;
  delay?: number;
  disabled?: boolean;
  children: React.ReactElement;
}

/**
 * Tooltip — lightweight hover/focus tooltip.
 * Wraps a single child element. No portal — CSS positioned.
 */
export function Tooltip({
  content,
  placement = 'top',
  delay = 300,
  disabled = false,
  children,
}: TooltipProps) {
  const [visible, setVisible] = useState(false);
  const timer = useRef<ReturnType<typeof setTimeout> | null>(null);
  const id = useId();

  const show = () => {
    if (disabled) return;
    timer.current = setTimeout(() => setVisible(true), delay);
  };
  const hide = () => {
    if (timer.current) clearTimeout(timer.current);
    setVisible(false);
  };

  useEffect(() => () => { if (timer.current) clearTimeout(timer.current); }, []);

  return (
    <span
      className={styles.wrapper}
      onMouseEnter={show}
      onMouseLeave={hide}
      onFocusCapture={show}
      onBlurCapture={hide}
    >
      {React.cloneElement(children, { 'aria-describedby': visible ? id : undefined })}
      {visible && (
        <span
          id={id}
          role="tooltip"
          className={styles.tooltip}
          data-placement={placement}
        >
          {content}
        </span>
      )}
    </span>
  );
}
