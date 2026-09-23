/**
 * NotificationsScreen — Notification centre + push settings
 */
'use client';
import React, { useState } from 'react';
import styles from './NotificationsScreen.module.css';
import { Icon } from '@axioledger/axio-design-system';

export interface NotificationsScreenProps {
  onBack?: () => void;
}

type NotifCategory = 'transactions' | 'security' | 'promotions' | 'system';

interface NotifItem {
  id: number;
  category: NotifCategory;
  title: string;
  body: string;
  time: string;
  read: boolean;
}

const SAMPLE_NOTIFS: NotifItem[] = [
  { id: 1, category: 'transactions', title: 'Transfer Received',        body: '+VND 2,000,000 from alice.axq',        time: '2 min ago',  read: false },
  { id: 2, category: 'security',     title: 'New device login',          body: 'Chrome on macOS signed in',            time: '1 hr ago',   read: false },
  { id: 3, category: 'transactions', title: 'Card payment',              body: '-USD 34.99 at Spotify',                time: '3 hr ago',   read: true  },
  { id: 4, category: 'promotions',   title: 'Cashback unlocked',         body: 'You earned USD 5 cashback this week',  time: 'Yesterday',  read: true  },
  { id: 5, category: 'system',       title: 'KYC verification complete', body: 'Your account is now fully verified',   time: '2 days ago', read: true  },
];

const CATEGORY_ICON: Record<NotifCategory, React.ReactNode> = {
  transactions: <Icon name="send-2"        size={18} color="#0057c2" aria-hidden />,
  security:     <Icon name="security-safe" size={18} color="#ff3d71" aria-hidden />,
  promotions:   <Icon name="gift"          size={18} color="#49dbc8" aria-hidden />,
  system:       <Icon name="info-circle"   size={18} color="#57606a" aria-hidden />,
};

const CATEGORY_BG_CLASS: Record<NotifCategory, string> = {
  transactions: styles.iconBgTx,
  security:     styles.iconBgSec,
  promotions:   styles.iconBgPromo,
  system:       styles.iconBgSys,
};

function Toggle({ on, onToggle }: { on: boolean; onToggle: () => void }) {
  return (
    <button
      className={[styles.toggle, on ? styles.toggleOn : styles.toggleOff].join(' ')}
      onClick={onToggle}
      role="switch"
      aria-checked={on}
      type="button"
    >
      <div className={[styles.toggleKnob, on ? styles.toggleKnobOn : styles.toggleKnobOff].join(' ')} />
    </button>
  );
}

export function NotificationsScreen({ onBack }: NotificationsScreenProps) {
  const [items, setItems] = useState(SAMPLE_NOTIFS);
  const [pushTx,    setPushTx]    = useState(true);
  const [pushSec,   setPushSec]   = useState(true);
  const [pushPromo, setPushPromo] = useState(false);

  const unreadCount = items.filter((n) => !n.read).length;
  const markAllRead = () => setItems((prev) => prev.map((n) => ({ ...n, read: true })));
  const markRead    = (id: number) => setItems((prev) => prev.map((n) => n.id === id ? { ...n, read: true } : n));

  return (
    <div className={styles.screen}>
      {/* Header */}
      <div className={styles.header}>
        <button className={styles.backBtn} onClick={onBack} aria-label="Go back" type="button">
          <Icon name="arrow-square" size={22} color="#1f2328" aria-hidden />
        </button>
        <h1 className={styles.pageTitle}>
          Notifications
          {unreadCount > 0 && <span className={styles.badge}>{unreadCount}</span>}
        </h1>
        <button className={styles.markAll} onClick={markAllRead} type="button" aria-label="Mark all as read">
          Mark all read
        </button>
      </div>

      {/* Notification list */}
      {items.map((n) => (
        <div
          key={n.id}
          className={[styles.item, !n.read ? styles.itemUnread : ''].filter(Boolean).join(' ')}
          onClick={() => markRead(n.id)}
          role="button"
          tabIndex={0}
          onKeyDown={(e) => e.key === 'Enter' && markRead(n.id)}
          aria-label={`${n.title}: ${n.body}`}
        >
          <div className={[styles.iconWrap, CATEGORY_BG_CLASS[n.category]].join(' ')}>
            {CATEGORY_ICON[n.category]}
          </div>
          <div className={styles.content}>
            <p className={[styles.itemTitle, n.read ? styles.itemTitleRead : ''].filter(Boolean).join(' ')}>{n.title}</p>
            <p className={styles.itemBody}>{n.body}</p>
            <p className={styles.itemTime}>{n.time}</p>
          </div>
          {!n.read && <div className={styles.unreadDot} aria-label="Unread" />}
        </div>
      ))}

      {/* Push notification preferences */}
      <div className={styles.settingsSection}>
        <p className={styles.settingsSectionTitle}>Push Notifications</p>

        {[
          { label: 'Transactions', desc: 'Payments, transfers, card activity', val: pushTx,    set: setPushTx    },
          { label: 'Security',     desc: 'Login alerts, suspicious activity',  val: pushSec,   set: setPushSec   },
          { label: 'Promotions',   desc: 'Cashback, rewards, offers',          val: pushPromo, set: setPushPromo },
        ].map((row) => (
          <div key={row.label} className={styles.settingRow}>
            <div>
              <p className={styles.settingLabel}>{row.label}</p>
              <p className={styles.settingDesc}>{row.desc}</p>
            </div>
            <Toggle on={row.val} onToggle={() => row.set(!row.val)} />
          </div>
        ))}
      </div>
    </div>
  );
}
