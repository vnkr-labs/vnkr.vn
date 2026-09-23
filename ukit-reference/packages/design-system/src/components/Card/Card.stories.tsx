import type { Meta, StoryObj } from '@storybook/react';
import { Card, CardHeader, CardBody, CardFooter } from './Card';
import { Button } from '../Button/Button';

const meta: Meta<typeof Card> = {
  title: 'Core/Card',
  component: Card,
  tags: ['autodocs'],
  argTypes: {
    variant: {
      control: 'select',
      options: ['default', 'outlined', 'elevated'],
    },
    onClick: { action: 'clicked' },
  },
  parameters: { layout: 'centered' },
};

export default meta;
type Story = StoryObj<typeof Card>;

export const Default: Story = {
  render: () => (
    <Card style={{ width: 320 }}>
      <CardHeader>
        <strong>Card Title</strong>
      </CardHeader>
      <CardBody>
        <p style={{ fontSize: 14, color: '#2E3A59' }}>
          This is a default card with a header, body, and footer.
        </p>
      </CardBody>
      <CardFooter>
        <Button size="small">Action</Button>
      </CardFooter>
    </Card>
  ),
};

export const Outlined: Story = {
  render: () => (
    <Card variant="outlined" style={{ width: 320 }}>
      <CardBody>
        <p>Outlined variant with a visible border.</p>
      </CardBody>
    </Card>
  ),
};

export const Elevated: Story = {
  render: () => (
    <Card variant="elevated" style={{ width: 320 }}>
      <CardBody>
        <p>Elevated variant with a drop shadow.</p>
      </CardBody>
    </Card>
  ),
};

export const Clickable: Story = {
  render: () => (
    <Card
      variant="outlined"
      onClick={() => alert('Card clicked')}
      aria-label="Open asset details"
      style={{ width: 320, cursor: 'pointer' }}
    >
      <CardBody>
        <p>Click anywhere on this card. Renders as &lt;button&gt; for a11y.</p>
      </CardBody>
    </Card>
  ),
};

export const CryptoAsset: Story = {
  render: () => (
    <Card variant="elevated" style={{ width: 320 }}>
      <CardHeader>
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
          <strong>ETH Balance</strong>
          <span style={{ fontSize: 12, color: '#57606a' }}>Ethereum</span>
        </div>
      </CardHeader>
      <CardBody>
        <div style={{ fontSize: 28, fontWeight: 700 }}>1.8432 ETH</div>
        <div style={{ fontSize: 14, color: '#2E3A59', marginTop: 4 }}>≈ $5,621.00 USD</div>
      </CardBody>
      <CardFooter>
        <div style={{ display: 'flex', gap: 8 }}>
          <Button size="small" color="blue">Send</Button>
          <Button size="small" variant="outlined">Receive</Button>
        </div>
      </CardFooter>
    </Card>
  ),
};
