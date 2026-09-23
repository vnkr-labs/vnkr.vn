import type { Meta, StoryObj } from '@storybook/react';
import { QRDisplay } from './QRDisplay';

const meta: Meta<typeof QRDisplay> = {
  title: 'Components/QRDisplay',
  component: QRDisplay,
  parameters: {
    layout: 'centered',
    backgrounds: { default: 'light' },
  },
  tags: ['autodocs'],
};
export default meta;

type Story = StoryObj<typeof QRDisplay>;

const SAMPLE_ADDRESS = '0x1A2b3C4d5E6f7A8B9C0D1E2F3A4B5C6D7E8F9A0B';

export const Default: Story = {
  args: {
    value: SAMPLE_ADDRESS,
    label: 'Your ERC-20 Address',
    sublabel: `${SAMPLE_ADDRESS.slice(0, 10)}…${SAMPLE_ADDRESS.slice(-8)}`,
    showCopy: true,
  },
};

export const WithNetworkBadge: Story = {
  args: {
    value: SAMPLE_ADDRESS,
    label: 'Your BEP-20 Address',
    sublabel: `${SAMPLE_ADDRESS.slice(0, 10)}…${SAMPLE_ADDRESS.slice(-8)}`,
    network: 'BEP-20',
    showCopy: true,
    showShare: true,
  },
};

export const LargeSize: Story = {
  args: {
    value: SAMPLE_ADDRESS,
    size: 300,
    label: 'Your Wallet Address',
    sublabel: `${SAMPLE_ADDRESS.slice(0, 10)}…${SAMPLE_ADDRESS.slice(-8)}`,
    showCopy: true,
  },
};

export const SmallSize: Story = {
  args: {
    value: SAMPLE_ADDRESS,
    size: 160,
    showCopy: false,
  },
};

export const Empty: Story = {
  args: {
    value: '',
    label: 'No address loaded',
    showCopy: false,
  },
};

export const PaymentURI: Story = {
  args: {
    value: 'ethereum:0x1A2b3C4d5E6f7A8B9C0D1E2F3A4B5C6D7E8F9A0B?amount=0.05&token=ETH',
    label: 'Payment Request',
    sublabel: 'Scan to pay 0.05 ETH',
    network: 'ERC-20',
    showCopy: true,
    showShare: true,
  },
};
