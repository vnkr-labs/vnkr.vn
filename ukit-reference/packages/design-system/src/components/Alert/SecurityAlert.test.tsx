import React from 'react';
import { render, screen, fireEvent } from '@testing-library/react';
import { SecurityAlert } from './SecurityAlert';

describe('SecurityAlert', () => {
  const baseProps = {
    level: 'blocked' as const,
    name: '0xDeadBeef1234567890AbCd1234567890AbCd1234',
    reason: 'This address has not been verified via ANS.',
    onDismiss: jest.fn(),
    onAction: jest.fn(),
  };

  afterEach(() => jest.clearAllMocks());

  it('renders with correct ARIA role', () => {
    render(<SecurityAlert {...baseProps} />);
    expect(screen.getByRole('alertdialog')).toBeInTheDocument();
  });

  it('shows the TLP badge label for blocked', () => {
    render(<SecurityAlert {...baseProps} />);
    expect(screen.getByText('Unverified')).toBeInTheDocument();
  });

  it('SECURITY: action button is ALWAYS disabled when level=blocked', () => {
    render(<SecurityAlert {...baseProps} />);
    const actionBtn = screen.getByRole('button', { name: /sign anyway/i });
    expect(actionBtn).toBeDisabled();
    expect(actionBtn).toHaveAttribute('aria-disabled', 'true');
  });

  it('SECURITY: clicking disabled action button does NOT fire onAction', () => {
    render(<SecurityAlert {...baseProps} />);
    const actionBtn = screen.getByRole('button', { name: /sign anyway/i });
    fireEvent.click(actionBtn);
    expect(baseProps.onAction).not.toHaveBeenCalled();
  });

  it('dismiss button fires onDismiss even when blocked', () => {
    render(<SecurityAlert {...baseProps} />);
    fireEvent.click(screen.getByRole('button', { name: /cancel/i }));
    expect(baseProps.onDismiss).toHaveBeenCalledTimes(1);
  });

  it('action button is ENABLED for caution level', () => {
    render(<SecurityAlert {...baseProps} level="caution" name="pool.kpx" />);
    const actionBtn = screen.getByRole('button', { name: /sign anyway/i });
    expect(actionBtn).not.toBeDisabled();
  });

  it('action button is ENABLED for safe level', () => {
    render(<SecurityAlert {...baseProps} level="safe" name="alice.axq" />);
    const actionBtn = screen.getByRole('button', { name: /sign anyway/i });
    expect(actionBtn).not.toBeDisabled();
  });

  it('shows the address/name in a code element', () => {
    render(<SecurityAlert {...baseProps} />);
    expect(screen.getByText(baseProps.name)).toBeInTheDocument();
  });

  it('shows custom actionLabel', () => {
    render(<SecurityAlert {...baseProps} level="caution" name="pool.kpx" actionLabel="Confirm Transfer" />);
    expect(screen.getByRole('button', { name: /confirm transfer/i })).toBeInTheDocument();
  });
});
