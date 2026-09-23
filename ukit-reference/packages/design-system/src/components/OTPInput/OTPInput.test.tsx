import React from 'react';
import { render, screen, fireEvent } from '@testing-library/react';
import userEvent from '@testing-library/user-event';
import { OTPInput } from './OTPInput';

describe('OTPInput', () => {
  it('renders correct number of cells (default 6)', () => {
    render(<OTPInput />);
    const inputs = screen.getAllByRole('textbox');
    expect(inputs).toHaveLength(6);
  });

  it('renders 4 cells when length=4', () => {
    render(<OTPInput length={4} />);
    expect(screen.getAllByRole('textbox')).toHaveLength(4);
  });

  it('rejects non-numeric input', async () => {
    const onChange = jest.fn();
    render(<OTPInput onChange={onChange} />);
    const inputs = screen.getAllByRole('textbox');
    await userEvent.type(inputs[0], 'a');
    expect(onChange).not.toHaveBeenCalledWith(expect.stringContaining('a'));
  });

  it('calls onComplete when all digits filled', async () => {
    const onComplete = jest.fn();
    render(<OTPInput length={4} onComplete={onComplete} />);
    const inputs = screen.getAllByRole('textbox');
    for (let i = 0; i < 4; i++) {
      fireEvent.change(inputs[i], { target: { value: String(i + 1) } });
    }
    expect(onComplete).toHaveBeenCalledWith('1234');
  });

  it('has accessible labels for each digit', () => {
    render(<OTPInput length={6} aria-label="Enter OTP" />);
    expect(screen.getByLabelText('Digit 1 of 6')).toBeInTheDocument();
    expect(screen.getByLabelText('Digit 6 of 6')).toBeInTheDocument();
  });

  it('disables all inputs when disabled=true', () => {
    render(<OTPInput disabled />);
    const inputs = screen.getAllByRole('textbox');
    inputs.forEach((input) => expect(input).toBeDisabled());
  });
});
