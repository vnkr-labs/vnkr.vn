import type { Meta, StoryObj } from '@storybook/react';
import { Alert } from './Alert';

const meta: Meta<typeof Alert> = {
  title: 'Feedback/Alert',
  component: Alert,
  tags: ['autodocs'],
  argTypes: {
    variant: {
      control: 'select',
      options: ['info', 'success', 'warning', 'error'],
    },
    onClose: { action: 'closed' },
  },
  parameters: {
    layout: 'padded',
  },
};

export default meta;
type Story = StoryObj<typeof Alert>;

export const Info: Story = {
  args: {
    variant: 'info',
    title: 'Info',
    children: 'Your transaction is being processed on the Ethereum network.',
  },
};

export const Success: Story = {
  args: {
    variant: 'success',
    title: 'Transaction Confirmed',
    children: 'Block #19,452,281 — 0.5 ETH sent to alice.axq',
  },
};

export const Warning: Story = {
  args: {
    variant: 'warning',
    title: 'High Gas Fee',
    children: 'Current gas price is 142 gwei — consider waiting for lower fees.',
  },
};

export const Error: Story = {
  args: {
    variant: 'error',
    title: 'Transaction Failed',
    children: 'Insufficient balance to cover the gas fee.',
  },
};

export const WithClose: Story = {
  args: {
    variant: 'info',
    title: 'Update Available',
    children: 'A new version of AXQ Wallet is ready to install.',
    onClose: () => {},
  },
};

export const AllVariants: Story = {
  render: () => (
    <div style={{ display: 'flex', flexDirection: 'column', gap: 12, maxWidth: 560 }}>
      <Alert variant="info" title="Info">Informational message.</Alert>
      <Alert variant="success" title="Success">Operation completed successfully.</Alert>
      <Alert variant="warning" title="Warning">Proceed with caution.</Alert>
      <Alert variant="error" title="Error">Something went wrong.</Alert>
    </div>
  ),
};
