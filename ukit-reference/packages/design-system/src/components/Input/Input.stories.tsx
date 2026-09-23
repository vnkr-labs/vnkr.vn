import type { Meta, StoryObj } from '@storybook/react';
import { Input } from './Input';

const meta: Meta<typeof Input> = {
  title: 'Core/Input',
  component: Input,
  tags: ['autodocs'],
  argTypes: {
    type: {
      control: 'select',
      options: ['text', 'password', 'search', 'number', 'email', 'readonly'],
    },
    size: {
      control: 'select',
      options: ['large', 'medium', 'small'],
    },
    disabled: { control: 'boolean' },
    readOnly: { control: 'boolean' },
    multiline: { control: 'boolean' },
  },
  parameters: { layout: 'centered' },
};

export default meta;
type Story = StoryObj<typeof Input>;

export const Default: Story = {
  args: {
    label: 'Email',
    placeholder: 'Enter your email',
    type: 'email',
    size: 'medium',
  },
};

export const WithHelperText: Story = {
  args: {
    label: 'Username',
    placeholder: 'axio_user',
    helperText: 'Must be 3–20 characters',
  },
};

export const WithError: Story = {
  args: {
    label: 'Wallet Address',
    value: '0xInvalid',
    errorText: 'Invalid Ethereum address',
  },
};

export const Disabled: Story = {
  args: {
    label: 'Wallet',
    value: '0xAbCd1234…',
    disabled: true,
  },
};

export const ReadOnly: Story = {
  args: {
    label: 'Contract Address',
    value: '0x1234567890abcdef',
    readOnly: true,
  },
};

export const Password: Story = {
  args: {
    label: 'Password',
    type: 'password',
    placeholder: 'Enter password',
  },
};

export const Multiline: Story = {
  args: {
    label: 'Note',
    multiline: true,
    rows: 4,
    placeholder: 'Enter a note…',
  },
};

export const WithIcons: Story = {
  args: {
    label: 'Search',
    iconLeft: '🔍',
    iconRight: '✕',
    placeholder: 'Search assets…',
  },
};

export const AllSizes: Story = {
  render: () => (
    <div style={{ display: 'flex', flexDirection: 'column', gap: 16, width: 320 }}>
      <Input label="Large" size="large" placeholder="large" />
      <Input label="Medium" size="medium" placeholder="medium" />
      <Input label="Small" size="small" placeholder="small" />
    </div>
  ),
};
