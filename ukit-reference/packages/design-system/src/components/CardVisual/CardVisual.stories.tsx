import type { Meta, StoryObj } from '@storybook/react';
import React from 'react';
import { CardVisual } from './CardVisual';

const meta: Meta<typeof CardVisual> = {
  title: 'Components/CardVisual',
  component: CardVisual,
  parameters: {
    layout: 'centered',
    backgrounds: { default: 'dark', values: [{ name: 'dark', value: '#0d1117' }, { name: 'light', value: '#f7f8fa' }] },
  },
  tags: ['autodocs'],
  argTypes: {
    skin:    { control: 'select', options: ['dark', 'teal', 'purple', 'green'] },
    scheme:  { control: 'select', options: ['visa', 'mastercard', 'axq'] },
    variant: { control: 'select', options: ['virtual', 'physical'] },
  },
};
export default meta;

type Story = StoryObj<typeof CardVisual>;

export const Default: Story = {
  args: {
    name: 'Alex Johnson',
    last4: '8742',
    expiry: '09/27',
    scheme: 'axq',
    skin: 'dark',
    variant: 'virtual',
  },
};

export const TealSkin: Story = {
  args: {
    name: 'Maria Silva',
    last4: '1234',
    expiry: '12/26',
    scheme: 'visa',
    skin: 'teal',
    variant: 'physical',
  },
};

export const PurpleSkin: Story = {
  args: {
    name: 'John Doe',
    last4: '9900',
    expiry: '03/28',
    scheme: 'mastercard',
    skin: 'purple',
    variant: 'virtual',
  },
};

export const GreenSkin: Story = {
  args: {
    name: 'Sarah Lee',
    last4: '5566',
    expiry: '06/29',
    scheme: 'axq',
    skin: 'green',
    variant: 'physical',
  },
};

export const Frozen: Story = {
  args: {
    name: 'Alex Johnson',
    last4: '8742',
    expiry: '09/27',
    scheme: 'axq',
    skin: 'dark',
    frozen: true,
  },
};

export const Flipped: Story = {
  args: {
    name: 'Alex Johnson',
    last4: '8742',
    expiry: '09/27',
    scheme: 'axq',
    skin: 'dark',
    flipped: true,
    cvv: '***',
  },
};

/** All skins side by side */
export const AllSkins: Story = {
  render: () => (
    <div style={{ display: 'flex', flexWrap: 'wrap', gap: 20, justifyContent: 'center' }}>
      {(['dark', 'teal', 'purple', 'green'] as const).map((skin) => (
        <CardVisual key={skin} name="Alex Johnson" last4="8742" expiry="09/27" skin={skin} scheme="axq" />
      ))}
    </div>
  ),
};
