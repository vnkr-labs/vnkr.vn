import type { Meta, StoryObj } from '@storybook/react';
import React, { useState } from 'react';
import { GasFeeSelector } from './GasFeeSelector';
import type { GasSpeed, GasOption } from './GasFeeSelector';

const DEFAULT_OPTIONS: GasOption[] = [
  { speed: 'slow',    label: 'Eco',    eta: '~5 min',  gwei: 12, usdCost: '~$0.80' },
  { speed: 'average', label: 'Normal', eta: '~1 min',  gwei: 20, usdCost: '~$2.40' },
  { speed: 'fast',    label: 'Fast',   eta: '<30 sec', gwei: 35, usdCost: '~$4.10' },
];

const meta: Meta<typeof GasFeeSelector> = {
  title: 'Components/GasFeeSelector',
  component: GasFeeSelector,
  parameters: {
    layout: 'centered',
    backgrounds: { default: 'light' },
  },
  tags: ['autodocs'],
};
export default meta;

type Story = StoryObj<typeof GasFeeSelector>;

export const Default: Story = {
  render: () => {
    const [speed, setSpeed] = useState<GasSpeed>('average');
    return (
      <div style={{ width: 380, padding: 16, background: '#fff', borderRadius: 16 }}>
        <GasFeeSelector
          options={DEFAULT_OPTIONS}
          value={speed}
          onChange={setSpeed}
          feeToken="ETH"
        />
      </div>
    );
  },
};

export const WithSlippage: Story = {
  render: () => {
    const [speed, setSpeed] = useState<GasSpeed>('average');
    const [slippage, setSlippage] = useState(0.5);
    return (
      <div style={{ width: 380, padding: 16, background: '#fff', borderRadius: 16 }}>
        <GasFeeSelector
          options={DEFAULT_OPTIONS}
          value={speed}
          onChange={setSpeed}
          feeToken="ETH"
          showSlippage
          slippage={slippage}
          onSlippageChange={setSlippage}
        />
      </div>
    );
  },
};

export const HighSlippageWarning: Story = {
  render: () => {
    const [speed, setSpeed] = useState<GasSpeed>('average');
    const [slippage, setSlippage] = useState(3.0);
    return (
      <div style={{ width: 380, padding: 16, background: '#fff', borderRadius: 16 }}>
        <GasFeeSelector
          options={DEFAULT_OPTIONS}
          value={speed}
          onChange={setSpeed}
          feeToken="ETH"
          showSlippage
          slippage={slippage}
          onSlippageChange={setSlippage}
        />
      </div>
    );
  },
};

export const FastSelected: Story = {
  render: () => {
    const [speed, setSpeed] = useState<GasSpeed>('fast');
    return (
      <div style={{ width: 380, padding: 16, background: '#fff', borderRadius: 16 }}>
        <GasFeeSelector
          options={DEFAULT_OPTIONS}
          value={speed}
          onChange={setSpeed}
          feeToken="ETH"
        />
      </div>
    );
  },
};

export const Disabled: Story = {
  render: () => (
    <div style={{ width: 380, padding: 16, background: '#fff', borderRadius: 16 }}>
      <GasFeeSelector
        options={DEFAULT_OPTIONS}
        value="average"
        onChange={() => {}}
        feeToken="ETH"
        disabled
      />
    </div>
  ),
};

export const SolanaFee: Story = {
  name: 'Alternative Token (SOL)',
  render: () => {
    const [speed, setSpeed] = useState<GasSpeed>('average');
    const solOptions: GasOption[] = [
      { speed: 'slow',    label: 'Standard', eta: '~30 sec', gwei: 5000,  usdCost: '~$0.001' },
      { speed: 'average', label: 'Priority', eta: '~10 sec', gwei: 50000, usdCost: '~$0.003' },
      { speed: 'fast',    label: 'Turbo',    eta: '<5 sec',  gwei: 200000,usdCost: '~$0.01'  },
    ];
    return (
      <div style={{ width: 380, padding: 16, background: '#fff', borderRadius: 16 }}>
        <GasFeeSelector
          options={solOptions}
          value={speed}
          onChange={setSpeed}
          feeToken="SOL"
        />
      </div>
    );
  },
};
