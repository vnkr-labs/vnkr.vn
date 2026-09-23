import type { Meta, StoryObj } from '@storybook/react';
import { SecurityAlert } from './SecurityAlert';

const meta: Meta<typeof SecurityAlert> = {
  title: 'Security/SecurityAlert',
  component: SecurityAlert,
  tags: ['autodocs'],
  argTypes: {
    level: {
      control: 'select',
      options: ['safe', 'caution', 'blocked'],
      description: '`blocked` always disables the action button — non-overridable.',
    },
    onAction: { action: 'action' },
    onDismiss: { action: 'dismissed' },
  },
  parameters: {
    layout: 'centered',
    docs: {
      description: {
        component:
          '**SecurityAlert** is a critical security component. When `level="blocked"`, the action button is ' +
          'unconditionally disabled regardless of all other props. This rule cannot be overridden.',
      },
    },
  },
};

export default meta;
type Story = StoryObj<typeof SecurityAlert>;

// ── Safe ────────────────────────────────────────────────
export const Safe: Story = {
  args: {
    level: 'safe',
    name: 'alice.axq',
    reason: 'This address has been verified via ANS and is safe to interact with.',
    actionLabel: 'Sign Transaction',
  },
};

// ── Caution ─────────────────────────────────────────────
export const Caution: Story = {
  args: {
    level: 'caution',
    name: 'pool.kpx',
    reason: 'This address is partially verified. Proceed carefully.',
    actionLabel: 'Sign Anyway',
  },
};

// ── Blocked (security critical) ─────────────────────────
export const Blocked: Story = {
  name: '⚠️ Blocked (action always disabled)',
  args: {
    level: 'blocked',
    name: '0xDeadBeef1234567890AbCd1234567890AbCd1234',
    reason: 'This address has not been verified via ANS and is flagged as high-risk.',
    actionLabel: 'Sign Anyway',
  },
  parameters: {
    docs: {
      description: {
        story:
          'Action button is **always** `disabled` + `aria-disabled="true"` when `level="blocked"`. ' +
          'Inspect the rendered DOM to confirm — this cannot be changed via props.',
      },
    },
  },
};

// ── Custom action label ──────────────────────────────────
export const CustomActionLabel: Story = {
  args: {
    level: 'caution',
    name: 'bridge.uniswap',
    reason: 'Bridge contract — verify before proceeding.',
    actionLabel: 'Confirm Bridge Transfer',
  },
};
