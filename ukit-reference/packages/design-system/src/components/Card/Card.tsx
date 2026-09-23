import React, { type ReactNode } from 'react';
import styles from './Card.module.css';

export interface CardProps {
  /** Visual style */
  variant?: 'default' | 'outlined' | 'elevated';
  /** Make the whole card clickable */
  onClick?: () => void;
  /** aria-label when used as a button */
  'aria-label'?: string;
  className?: string;
  children: ReactNode;
}

/**
 * Card — surface container.
 * Renders as <button> when onClick is provided (keyboard accessible).
 */
export function Card({ variant = 'default', onClick, 'aria-label': ariaLabel, className, children }: CardProps) {
  const Tag = onClick ? 'button' : 'div';
  return (
    <Tag
      className={[styles.root, styles[variant], className ?? ''].filter(Boolean).join(' ')}
      onClick={onClick}
      aria-label={onClick ? ariaLabel : undefined}
      type={onClick ? 'button' : undefined}
    >
      {children}
    </Tag>
  );
}

export function CardHeader({ children, className }: { children: ReactNode; className?: string }) {
  return <div className={[styles.header, className ?? ''].filter(Boolean).join(' ')}>{children}</div>;
}

export function CardBody({ children, className }: { children: ReactNode; className?: string }) {
  return <div className={[styles.body, className ?? ''].filter(Boolean).join(' ')}>{children}</div>;
}

export function CardFooter({ children, className }: { children: ReactNode; className?: string }) {
  return <div className={[styles.footer, className ?? ''].filter(Boolean).join(' ')}>{children}</div>;
}
