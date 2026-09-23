import type { Meta, StoryObj } from '@storybook/react';
import React, { useState } from 'react';
import { PINPad } from './PINPad';

const meta: Meta<typeof PINPad> = {
  title: 'Components/PINPad',
  component: PINPad,
  parameters: {
    layout: 'centered',
    backgrounds: { default: 'light' },
  },
  tags: ['autodocs'],
};
export default meta;

type Story = StoryObj<typeof PINPad>;

export const Default: Story = {
  args: {
    length: 6,
    mode: 'enter',
    label: 'Enter your PIN',
  },
};

export const CreateMode: Story = {
  args: {
    length: 6,
    mode: 'create',
  },
};

export const ConfirmMode: Story = {
  args: {
    length: 6,
    mode: 'confirm',
    label: 'Confirm your PIN',
    hint: 'Re-enter the same 6-digit PIN',
  },
};

export const WithBiometric: Story = {
  args: {
    length: 6,
    mode: 'enter',
    showBiometric: true,
    label: 'Enter your PIN',
  },
};

export const ErrorState: Story = {
  args: {
    length: 6,
    mode: 'enter',
    error: true,
    label: 'Wrong PIN',
    hint: '2 attempts remaining',
  },
};

export const Disabled: Story = {
  args: {
    length: 6,
    mode: 'enter',
    disabled: true,
    label: 'Account locked',
  },
};

export const FourDigit: Story = {
  args: {
    length: 4,
    mode: 'create',
    label: 'Create a 4-digit PIN',
  },
};

/** Interactive story — logs the completed PIN to console */
export const Interactive: Story = {
  render: () => {
    const [result, setResult] = useState<string | null>(null);
    return (
      <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 16 }}>
        <PINPad
          length={6}
          mode="enter"
          label="Enter any 6 digits"
          onComplete={(pin) => setResult(pin)}
        />
        {result && (
          <p style={{ fontFamily: 'monospace', background: '#f0f2f4', padding: '6px 12px', borderRadius: 8, fontSize: 14 }}>
            Completed: <strong>{result}</strong>
          </p>
        )}
      </div>
    );
  },
};
