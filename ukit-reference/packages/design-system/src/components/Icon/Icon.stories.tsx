/**
 * Icon — Storybook stories
 *
 * Shows the full 708-icon catalogue, size variants, colour control,
 * and the "unknown name" fallback behaviour.
 */
import React, { useState } from 'react';
import type { Meta, StoryObj } from '@storybook/react';
import { Icon } from './Icon';
import type { IconName, IconSize } from './Icon';
import { iconPaths } from './iconPaths';

const meta: Meta<typeof Icon> = {
  title: 'System / Icon',
  component: Icon,
  tags: ['autodocs'],
  argTypes: {
    name: {
      control: 'text',
      description: 'Icon name — matches SVG filename without extension',
    },
    size: {
      control: { type: 'select' },
      options: ['xs', 'sm', 'md', 'lg', 'xl', 16, 20, 24, 32, 40, 48, 64],
      description: 'Named alias or pixel number',
    },
    color: {
      control: 'color',
      description: 'CSS color value (sets currentColor)',
    },
    strokeWidth: {
      control: { type: 'range', min: 0.5, max: 3, step: 0.1 },
      description: 'SVG stroke width',
    },
  },
  parameters: {
    docs: {
      description: {
        component:
          'Renders any of the 708 Axioledger icons as an accessible inline SVG. ' +
          'Color is driven by `currentColor` — set via the `color` prop or the CSS `color` property. ' +
          'Use `aria-label` for meaningful icons; decorative icons receive `aria-hidden` automatically.',
      },
    },
  },
};

export default meta;
type Story = StoryObj<typeof Icon>;

/* ─────────────────────────────────────────────────────────
   Default (playground)
   ───────────────────────────────────────────────────────── */
export const Default: Story = {
  args: {
    name: 'home',
    size: 'md',
    color: '#1f2328',
    strokeWidth: 1.5,
  },
};

/* ─────────────────────────────────────────────────────────
   Size variants
   ───────────────────────────────────────────────────────── */
export const Sizes: Story = {
  render: () => (
    <div style={{ display: 'flex', alignItems: 'center', gap: 20, flexWrap: 'wrap', padding: 16 }}>
      {(['xs', 'sm', 'md', 'lg', 'xl'] as IconSize[]).map((s) => (
        <div key={s} style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 6 }}>
          <Icon name="send" size={s} color="#1f2328" />
          <span style={{ fontSize: 11, color: '#57606a', fontFamily: 'monospace' }}>{s}</span>
        </div>
      ))}
    </div>
  ),
};

/* ─────────────────────────────────────────────────────────
   Colour variants
   ───────────────────────────────────────────────────────── */
export const Colours: Story = {
  render: () => {
    const palette = [
      { label: 'primary',  color: '#1f2328' },
      { label: 'teal',     color: '#49dbc8' },
      { label: 'blue',     color: '#0057c2' },
      { label: 'red',      color: '#ff3d71' },
      { label: 'green',    color: '#00d68f' },
      { label: 'orange',   color: '#fc7339' },
      { label: 'purple',   color: '#af96fb' },
      { label: 'muted',    color: '#8f9bb3' },
    ];
    return (
      <div style={{ display: 'flex', gap: 20, flexWrap: 'wrap', padding: 16 }}>
        {palette.map(({ label, color }) => (
          <div key={label} style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 6 }}>
            <Icon name="briefcase" size="lg" color={color} />
            <span style={{ fontSize: 11, color: '#57606a' }}>{label}</span>
          </div>
        ))}
      </div>
    );
  },
};

/* ─────────────────────────────────────────────────────────
   Stroke width
   ───────────────────────────────────────────────────────── */
export const StrokeWidths: Story = {
  render: () => (
    <div style={{ display: 'flex', alignItems: 'center', gap: 24, padding: 16 }}>
      {[1, 1.5, 2, 2.5].map((sw) => (
        <div key={sw} style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 6 }}>
          <Icon name="shield-tick" size="lg" color="#1f2328" strokeWidth={sw} />
          <span style={{ fontSize: 11, color: '#57606a', fontFamily: 'monospace' }}>{sw}</span>
        </div>
      ))}
    </div>
  ),
};

/* ─────────────────────────────────────────────────────────
   Full catalogue — searchable grid
   ───────────────────────────────────────────────────────── */
const ALL_NAMES = Object.keys(iconPaths) as IconName[];

export const Catalogue: Story = {
  render: () => {
    // eslint-disable-next-line react-hooks/rules-of-hooks
    const [query, setQuery] = useState('');
    const filtered = query
      ? ALL_NAMES.filter((n) => n.includes(query.toLowerCase()))
      : ALL_NAMES;

    return (
      <div style={{ padding: 16, fontFamily: 'system-ui, sans-serif' }}>
        <input
          type="search"
          placeholder={`Search ${ALL_NAMES.length} icons…`}
          value={query}
          onChange={(e) => setQuery(e.target.value)}
          style={{ width: '100%', maxWidth: 380, height: 40, border: '1.5px solid #e4e9f2', borderRadius: 8, padding: '0 12px', fontSize: 14, marginBottom: 20, boxSizing: 'border-box' }}
        />
        <p style={{ fontSize: 12, color: '#57606a', margin: '0 0 12px' }}>
          {filtered.length} icon{filtered.length !== 1 ? 's' : ''}
          {query ? ` matching "${query}"` : ' total'}
        </p>
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill, minmax(80px, 1fr))', gap: 8 }}>
          {filtered.map((name) => (
            <div
              key={name}
              title={name}
              style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 5, padding: '10px 4px', background: '#f7f8fa', borderRadius: 8, cursor: 'default', overflow: 'hidden' }}
            >
              <Icon name={name} size="md" color="#1f2328" />
              <span style={{ fontSize: 9, color: '#57606a', textAlign: 'center', wordBreak: 'break-all', lineHeight: 1.3 }}>{name}</span>
            </div>
          ))}
        </div>
      </div>
    );
  },
  parameters: {
    docs: { disable: true },
  },
};
