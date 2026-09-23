import type { Meta, StoryObj } from '@storybook/react';
import { LivenessFrame } from './LivenessFrame';
import type { LivenessStep } from './LivenessFrame';

const meta: Meta<typeof LivenessFrame> = {
  title: 'Components/LivenessFrame',
  component: LivenessFrame,
  parameters: {
    layout: 'fullscreen',
    backgrounds: { default: 'dark', values: [{ name: 'dark', value: '#000000' }] },
  },
  tags: ['autodocs'],
  argTypes: {
    step: {
      control: 'select',
      options: ['align', 'blink', 'turn_left', 'turn_right', 'smile', 'done', 'error'] satisfies LivenessStep[],
    },
    progress: { control: { type: 'range', min: 0, max: 100, step: 1 } },
  },
};
export default meta;

type Story = StoryObj<typeof LivenessFrame>;

export const Align: Story = {
  args: { step: 'align', progress: 0, scanning: true },
};

export const Blink: Story = {
  args: { step: 'blink', progress: 35, scanning: true },
};

export const TurnLeft: Story = {
  args: { step: 'turn_left', progress: 60, scanning: true },
};

export const TurnRight: Story = {
  args: { step: 'turn_right', progress: 75, scanning: true },
};

export const Smile: Story = {
  args: { step: 'smile', progress: 90, scanning: true },
};

export const Done: Story = {
  args: { step: 'done', progress: 100, scanning: false },
};

export const Error: Story = {
  args: { step: 'error', progress: 0, scanning: false, errorMessage: 'Too much light — please find a shaded area and try again.' },
};

export const NotScanning: Story = {
  name: 'Not Scanning (idle)',
  args: { step: 'align', progress: 0, scanning: false },
};
