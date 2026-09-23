import React from 'react';
import { Avatar } from '../Avatar/Avatar';
import styles from './Navbar.module.css';

export interface NavItem {
  id: string;
  label: string;
  icon: React.ReactNode;
  badge?: number;
}

export interface NavbarProps {
  items: NavItem[];
  activeId: string;
  onSelect: (id: string) => void;
  /** Show at top (horizontal) or bottom (mobile default) */
  position?: 'top' | 'bottom';
  /** Right slot — avatar, button */
  trailing?: React.ReactNode;
}

/**
 * Navbar — bottom navigation bar (mobile-first).
 * Keyboard: ArrowLeft / ArrowRight navigate between items.
 */
export function Navbar({ items, activeId, onSelect, position = 'bottom', trailing }: NavbarProps) {
  const handleKey = (e: React.KeyboardEvent, currentIndex: number) => {
    if (e.key === 'ArrowRight') {
      const next = (currentIndex + 1) % items.length;
      onSelect(items[next].id);
    }
    if (e.key === 'ArrowLeft') {
      const prev = (currentIndex - 1 + items.length) % items.length;
      onSelect(items[prev].id);
    }
  };

  return (
    <nav
      className={styles.root}
      data-position={position}
      aria-label="Main navigation"
    >
      <ul className={styles.list} role="tablist">
        {items.map((item, index) => {
          const isActive = item.id === activeId;
          return (
            <li key={item.id} role="none" className={styles.item}>
              <button
                type="button"
                role="tab"
                className={styles.btn}
                data-active={isActive || undefined}
                aria-selected={isActive}
                aria-label={item.label}
                onClick={() => onSelect(item.id)}
                onKeyDown={(e) => handleKey(e, index)}
              >
                <span className={styles.iconWrap} aria-hidden="true">
                  {item.icon}
                  {item.badge !== undefined && item.badge > 0 && (
                    <span className={styles.badge} aria-label={`${item.badge} notifications`}>
                      {item.badge > 99 ? '99+' : item.badge}
                    </span>
                  )}
                </span>
                <span className={styles.label}>{item.label}</span>
              </button>
            </li>
          );
        })}
      </ul>
      {trailing && <div className={styles.trailing}>{trailing}</div>}
    </nav>
  );
}
