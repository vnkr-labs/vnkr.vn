/**
 * Zone 7 — Profile & Settings screens
 * ProfileScreen | EditProfileScreen | SecurityScreen | SettingsScreen | FAQScreen
 */
'use client';
import React, { useState } from 'react';
import { Icon } from '@axioledger/axio-design-system';

const S: Record<string, React.CSSProperties> = {
  screen:      { minHeight: '100svh', padding: '16px 20px 80px', background: '#fff', fontFamily: 'system-ui,sans-serif', overflowY: 'auto' },
  pageTitle:   { fontSize: 22, fontWeight: 700, color: '#1f2328', margin: '0 0 20px' },
  backBtn:     { background: 'none', border: 'none', fontSize: 20, cursor: 'pointer', marginBottom: 12, padding: 0, color: '#1f2328' },
  card:        { background: '#f7f8fa', borderRadius: 14, padding: 16, marginBottom: 12 },
  sectionLink: { display: 'flex', justifyContent: 'space-between', alignItems: 'center', padding: '14px 0', background: 'none', border: 'none', width: '100%', fontSize: 15, fontWeight: 600, cursor: 'pointer', borderBottom: '1px solid #f0f2f4', color: '#1f2328', textAlign: 'left' },
  input:       { width: '100%', height: 52, border: '1.5px solid #e4e9f2', borderRadius: 12, padding: '0 16px', fontSize: 15, background: '#fff', boxSizing: 'border-box', marginBottom: 12 },
  label:       { fontSize: 13, fontWeight: 600, color: '#57606a', marginBottom: 6, display: 'block' },
  primaryBtn:  { width: '100%', height: 56, background: '#0d1117', color: '#fff', border: 'none', borderRadius: 9999, fontSize: 16, fontWeight: 700, cursor: 'pointer', marginTop: 16 },
  toggle:      { width: 48, height: 28, borderRadius: 9999, border: 'none', cursor: 'pointer', position: 'relative', transition: 'background 0.2s', flexShrink: 0 },
  toggleKnob:  { position: 'absolute', top: 4, width: 20, height: 20, borderRadius: '50%', background: '#fff', transition: 'transform 0.2s', boxShadow: '0 1px 3px rgba(0,0,0,0.2)' },
};

/* ─────────────────────────────────────────────────────────
   ProfileScreen
   ───────────────────────────────────────────────────────── */
export function ProfileScreen({ onEdit, onSettings, onSecurity, onFAQ, onLogout, onNotifications }:
  { onEdit?: () => void; onSettings?: () => void; onSecurity?: () => void; onFAQ?: () => void; onLogout?: () => void; onNotifications?: () => void }) {
  return (
    <div style={S.screen}>
      <h1 style={S.pageTitle}>Profile</h1>

      {/* Avatar + info */}
      <div style={{ ...S.card, display: 'flex', alignItems: 'center', gap: 16 }}>
        <div style={{ width: 64, height: 64, borderRadius: '50%', background: '#49dbc8', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 28, fontWeight: 700, color: '#000', flexShrink: 0 }}>A</div>
        <div>
          <p style={{ fontSize: 18, fontWeight: 700, margin: 0, color: '#1f2328' }}>Alex Nguyen</p>
          <p style={{ fontSize: 13, color: '#57606a', margin: '2px 0' }}>alex.nguyen.axq</p>
          <span style={{ fontSize: 11, fontWeight: 700, background: '#49dbc8', color: '#000', borderRadius: 9999, padding: '2px 8px', display: 'inline-flex', alignItems: 'center', gap: 3 }}>
            <Icon name="tick-circle" size={11} color="#000" aria-hidden /> Verified
          </span>
        </div>
        <button style={{ marginLeft: 'auto', background: 'none', border: 'none', color: '#0057c2', fontSize: 14, cursor: 'pointer', fontWeight: 600 }} onClick={onEdit}>Edit</button>
      </div>

      {([
        { icon: 'setting-2'           as const, label: 'Settings',       action: onSettings },
        { icon: 'security'            as const, label: 'Security',        action: onSecurity },
        { icon: 'message-question'    as const, label: 'FAQ / Help',      action: onFAQ },
        { icon: 'notification-bing'   as const, label: 'Notifications',   action: onNotifications },
        { icon: 'global'              as const, label: 'Language',        action: () => {} },
        { icon: 'lamp'                as const, label: 'Dark Mode',       action: () => {} },
      ]).map((item) => (
        <button key={item.label} style={S.sectionLink} onClick={item.action}>
          <span style={{ display: 'inline-flex', alignItems: 'center', gap: 8 }}>
            <Icon name={item.icon} size={18} color="#57606a" aria-hidden />
            {item.label}
          </span>
          <span style={{ color: '#8f9bb3' }}>›</span>
        </button>
      ))}

      <div style={{ height: 1, background: '#e4e9f2', margin: '16px 0' }} />
      <button style={{ ...S.sectionLink, color: '#ff3d71' }} onClick={onLogout}>
        <span style={{ display: 'inline-flex', alignItems: 'center', gap: 8 }}>
          <Icon name="arrow-square" size={18} color="#ff3d71" aria-hidden />
          Log Out
        </span>
        <span style={{ color: '#8f9bb3' }}>›</span>
      </button>
    </div>
  );
}

/* ─────────────────────────────────────────────────────────
   EditProfileScreen
   ───────────────────────────────────────────────────────── */
export function EditProfileScreen({ onBack, onSave }: { onBack?: () => void; onSave?: () => void }) {
  const [name, setName] = useState('Alex Nguyen');
  const [email, setEmail] = useState('alex@example.com');

  return (
    <div style={S.screen}>
      <button style={S.backBtn} onClick={onBack}>←</button>
      <h1 style={S.pageTitle}>Edit Profile</h1>

      {/* Avatar upload */}
      <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 10, marginBottom: 28 }}>
        <div style={{ width: 80, height: 80, borderRadius: '50%', background: '#49dbc8', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 36, fontWeight: 700, color: '#000', position: 'relative' }}>
          A
          <button style={{ position: 'absolute', bottom: 0, right: 0, width: 28, height: 28, borderRadius: '50%', background: '#0d1117', border: 'none', cursor: 'pointer', color: '#fff', display: 'flex', alignItems: 'center', justifyContent: 'center' }} aria-label="Change photo">
            <Icon name="scan" size={14} color="#fff" aria-hidden />
          </button>
        </div>
        <p style={{ fontSize: 13, color: '#0057c2', fontWeight: 600, cursor: 'pointer', margin: 0 }}>Change photo</p>
      </div>

      <label style={S.label}>Display Name</label>
      <input style={S.input} value={name} onChange={(e) => setName(e.target.value)} aria-label="Display name" />

      <label style={S.label}>Email Address</label>
      <input style={S.input} type="email" value={email} onChange={(e) => setEmail(e.target.value)} aria-label="Email address" />

      <button style={S.primaryBtn} onClick={onSave}>Save Changes</button>
    </div>
  );
}

/* ─────────────────────────────────────────────────────────
   SecurityScreen
   ───────────────────────────────────────────────────────── */
const DEVICES = [
  { id: 1, name: 'iPhone 16 Pro · Current',  lastSeen: 'Now',       current: true },
  { id: 2, name: 'iPad Air',                  lastSeen: '2 days ago', current: false },
];

export function SecurityScreen({ onBack }: { onBack?: () => void }) {
  return (
    <div style={S.screen}>
      <button style={S.backBtn} onClick={onBack}>←</button>
      <h1 style={S.pageTitle}>Account Security</h1>

      {['Change PIN', 'Change Password', 'Manage Passkeys'].map((action) => (
        <button key={action} style={S.sectionLink}>{action} <span style={{ color: '#8f9bb3' }}>›</span></button>
      ))}

      <p style={{ fontSize: 14, fontWeight: 700, margin: '20px 0 12px', color: '#1f2328' }}>Active Devices</p>
      {DEVICES.map((d) => (
        <div key={d.id} style={{ ...S.card, display: 'flex', alignItems: 'center', gap: 12 }}>
          <Icon name="mobile" size={28} color="#57606a" aria-hidden />
          <div style={{ flex: 1 }}>
            <p style={{ fontSize: 14, fontWeight: 600, margin: 0, color: d.current ? '#0057c2' : '#1f2328' }}>{d.name}</p>
            <p style={{ fontSize: 12, color: '#57606a', margin: 0 }}>Last seen: {d.lastSeen}</p>
          </div>
          {!d.current && (
            <button style={{ background: 'none', border: 'none', color: '#ff3d71', fontSize: 13, fontWeight: 600, cursor: 'pointer' }}>Revoke</button>
          )}
        </div>
      ))}
    </div>
  );
}

/* ─────────────────────────────────────────────────────────
   SettingsScreen
   ───────────────────────────────────────────────────────── */
export function SettingsScreen({ onBack }: { onBack?: () => void }) {
  const [darkMode, setDarkMode] = useState(false);
  const [pushNotif, setPushNotif] = useState(true);
  const [emailNotif, setEmailNotif] = useState(false);

  const toggleRow = (label: string, val: boolean, set: (v: boolean) => void) => (
    <div key={label} style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', padding: '14px 0', borderBottom: '1px solid #f0f2f4' }}>
      <span style={{ fontSize: 15, fontWeight: 600, color: '#1f2328' }}>{label}</span>
      <button style={{ ...S.toggle, background: val ? '#49dbc8' : '#e4e9f2' }} onClick={() => set(!val)} role="switch" aria-checked={val} aria-label={label}>
        <div style={{ ...S.toggleKnob, transform: val ? 'translateX(20px)' : 'translateX(2px)' }} />
      </button>
    </div>
  );

  return (
    <div style={S.screen}>
      <button style={S.backBtn} onClick={onBack}>←</button>
      <h1 style={S.pageTitle}>Settings</h1>

      {toggleRow('Dark Mode', darkMode, setDarkMode)}
      {toggleRow('Push Notifications', pushNotif, setPushNotif)}
      {toggleRow('Email Notifications', emailNotif, setEmailNotif)}

      {([
        { icon: 'global'   as const, label: 'Language (English)' },
        { icon: 'location' as const, label: 'Region & Currency' },
        { icon: 'bank'     as const, label: 'Linked Bank Accounts' },
      ]).map((item) => (
        <button key={item.label} style={S.sectionLink} onClick={() => {}}>
          <span style={{ display: 'inline-flex', alignItems: 'center', gap: 8 }}>
            <Icon name={item.icon} size={18} color="#57606a" aria-hidden />
            {item.label}
          </span>
          <span style={{ color: '#8f9bb3' }}>›</span>
        </button>
      ))}
    </div>
  );
}

/* ─────────────────────────────────────────────────────────
   FAQScreen
   ───────────────────────────────────────────────────────── */
const FAQ_ITEMS = [
  { q: 'How do I reset my PIN?',               a: 'Go to Profile → Security → Change PIN. You will need to verify via OTP.' },
  { q: 'What is a Passkey?',                   a: 'A passkey replaces your password with Face ID or Touch ID, secured by your device.' },
  { q: 'How long does KYC take?',              a: 'Usually under 10 minutes. We will notify you when verification is complete.' },
  { q: 'Can I use the card internationally?',  a: 'Yes. Enable International Payments in Card Settings first.' },
  { q: 'How do I get cashback?',               a: 'Cashback is earned automatically on eligible card transactions.' },
];

export function FAQScreen({ onBack }: { onBack?: () => void }) {
  const [expanded, setExpanded] = useState<number | null>(null);
  const [query, setQuery] = useState('');

  const filtered = FAQ_ITEMS.filter(
    (f) => f.q.toLowerCase().includes(query.toLowerCase()) || f.a.toLowerCase().includes(query.toLowerCase())
  );

  return (
    <div style={S.screen}>
      <button style={S.backBtn} onClick={onBack}>←</button>
      <h1 style={S.pageTitle}>Help & FAQ</h1>
      <div style={{ position: 'relative' }}>
        <span style={{ position: 'absolute', left: 14, top: '50%', transform: 'translateY(-50%)', pointerEvents: 'none' }}>
          <Icon name="search-normal" size={16} color="#8f9bb3" aria-hidden />
        </span>
        <input style={{ ...S.input, paddingLeft: 40 }} placeholder="Search questions…" value={query} onChange={(e) => setQuery(e.target.value)} aria-label="Search FAQ" />
      </div>

      {filtered.map((f, i) => (
        <div key={i} style={{ borderBottom: '1px solid #f0f2f4' }}>
          <button style={{ ...S.sectionLink, borderBottom: 'none', fontWeight: 600 }} onClick={() => setExpanded(expanded === i ? null : i)} aria-expanded={expanded === i}>
            {f.q} <span style={{ color: '#8f9bb3', fontSize: 18 }}>{expanded === i ? '⌃' : '⌄'}</span>
          </button>
          {expanded === i && (
            <p style={{ fontSize: 14, color: '#57606a', lineHeight: 1.6, padding: '0 0 14px', margin: 0 }}>{f.a}</p>
          )}
        </div>
      ))}

      {filtered.length === 0 && (
        <p style={{ fontSize: 14, color: '#57606a', textAlign: 'center', marginTop: 32 }}>No results for "{query}"</p>
      )}

      <div style={{ marginTop: 32, textAlign: 'center' }}>
        <p style={{ fontSize: 14, color: '#57606a', marginBottom: 12 }}>Still need help?</p>
        <button style={{ height: 48, padding: '0 24px', border: '1.5px solid #e4e9f2', borderRadius: 9999, background: 'none', fontSize: 14, fontWeight: 600, cursor: 'pointer', display: 'inline-flex', alignItems: 'center', gap: 6 }}>
          <Icon name="message-circle" size={16} color="#0057c2" aria-hidden />
          Live Chat Support
        </button>
      </div>
    </div>
  );
}
