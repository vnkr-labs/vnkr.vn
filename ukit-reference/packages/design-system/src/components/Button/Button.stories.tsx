import type { Meta, StoryObj } from '@storybook/react';
import { Button } from './Button';

const meta: Meta<typeof Button> = {
  title: 'Core/Button',
  component: Button,
  tags: ['autodocs'],
  argTypes: {
    variant: {
      control: 'select',
      options: ['filled', 'outlined', 'ghost'],
    },
    color: {
      control: 'select',
      options: ['black', 'blue', 'green', 'yellow', 'orange', 'error', 'navy', 'white'],
    },
    size: {
      control: 'select',
      options: ['giant', 'large', 'medium', 'small'],
    },
    loading: { control: 'boolean' },
    disabled: { control: 'boolean' },
    fullWidth: { control: 'boolean' },
    iconOnly: { control: 'boolean' },
    onClick: { action: 'clicked' },
  },
  parameters: {
    layout: 'centered',
  },
};

export default meta;
type Story = StoryObj<typeof Button>;

// ── Playground ──────────────────────────────────────────
export const Playground: Story = {
  args: {
    variant: 'filled',
    color: 'black',
    size: 'large',
    children: 'Button',
  },
};

// ── Variants ────────────────────────────────────────────
export const Filled: Story = {
  args: { variant: 'filled', color: 'black', children: 'Filled' },
};

export const Outlined: Story = {
  args: { variant: 'outlined', color: 'black', children: 'Outlined' },
};

export const Ghost: Story = {
  args: { variant: 'ghost', color: 'black', children: 'Ghost' },
};

// ── Colors ──────────────────────────────────────────────
export const AllColors: Story = {
  render: () => (
    <div style={{ display: 'flex', flexWrap: 'wrap', gap: 8 }}>
      {(['black', 'blue', 'green', 'yellow', 'orange', 'error', 'navy'] as const).map(
        (color) => (
          <Button key={color} color={color} variant="filled">
            {color}
          </Button>
        )
      )}
    </div>
  ),
};

// ── Sizes ───────────────────────────────────────────────
export const AllSizes: Story = {
  render: () => (
    <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
      {(['small', 'medium', 'large', 'giant'] as const).map((size) => (
        <Button key={size} size={size}>
          {size}
        </Button>
      ))}
    </div>
  ),
};

// ── States ──────────────────────────────────────────────
export const Loading: Story = {
  args: { loading: true, children: 'Loading…' },
};

export const Disabled: Story = {
  args: { disabled: true, children: 'Disabled' },
};

export const FullWidth: Story = {
  args: { fullWidth: true, children: 'Full Width' },
  parameters: { layout: 'padded' },
};

export const WithIcons: Story = {
  args: {
    iconLeft: '🔑',
    iconRight: '→',
    children: 'Connect Wallet',
  },
};

export const IconOnly: Story = {
  args: {
    iconOnly: true,
    'aria-label': 'Settings',
    children: '⚙',
  },
};
