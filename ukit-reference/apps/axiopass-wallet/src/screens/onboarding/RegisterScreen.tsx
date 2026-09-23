/**
 * RegisterScreen — Welcome / Phone-Email input / Mode select
 */
'use client';
import React, { useState } from 'react';
import styles from './RegisterScreen.module.css';
import { Icon } from '@axioledger/axio-design-system';

export type RegisterMode = 'login' | 'register';

export interface RegisterScreenProps {
  onSubmit?: (mode: RegisterMode, identifier: string) => void;
  onForgot?: () => void;
}

const COUNTRY_CODES = [
  { code: '+84', flag: '🇻🇳', name: 'VN' },
  { code: '+1',  flag: '🇺🇸', name: 'US' },
  { code: '+44', flag: '🇬🇧', name: 'GB' },
  { code: '+65', flag: '🇸🇬', name: 'SG' },
];

export function RegisterScreen({ onSubmit, onForgot }: RegisterScreenProps) {
  const [mode, setMode] = useState<RegisterMode>('register');
  const [inputType, setInputType] = useState<'phone' | 'email'>('phone');
  const [countryCode, setCountryCode] = useState(COUNTRY_CODES[0]);
  const [value, setValue] = useState('');
  const [error, setError] = useState('');

  const validate = (): boolean => {
    if (!value.trim()) { setError('This field is required'); return false; }
    if (inputType === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
      setError('Enter a valid email address'); return false;
    }
    if (inputType === 'phone' && !/^\d{7,15}$/.test(value.replace(/\s/g, ''))) {
      setError('Enter a valid phone number'); return false;
    }
    setError('');
    return true;
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!validate()) return;
    const identifier = inputType === 'phone' ? `${countryCode.code}${value}` : value;
    onSubmit?.(mode, identifier);
  };

  return (
    <div className={styles.root}>
      <header className={styles.header}>
        <div className={styles.logo}>AXQ</div>
        <h1 className={styles.title}>
          {mode === 'register' ? 'Create account' : 'Welcome back'}
        </h1>
        <p className={styles.subtitle}>
          {mode === 'register'
            ? 'Set up your passkey-native wallet'
            : 'Sign in to your account'}
        </p>
      </header>

      {/* Mode toggle */}
      <div className={styles.modeToggle} role="tablist">
        {(['register', 'login'] as RegisterMode[]).map((m) => (
          <button
            key={m}
            role="tab"
            aria-selected={mode === m}
            className={[styles.modeBtn, mode === m ? styles.modeBtnActive : ''].filter(Boolean).join(' ')}
            onClick={() => { setMode(m); setError(''); }}
          >
            {m === 'register' ? 'Sign Up' : 'Log In'}
          </button>
        ))}
      </div>

      <form onSubmit={handleSubmit} noValidate className={styles.form}>
        {/* Input type toggle */}
        <div className={styles.inputTypeToggle}>
          {(['phone', 'email'] as const).map((t) => (
            <button
              key={t}
              type="button"
              className={[styles.typeBtn, inputType === t ? styles.typeBtnActive : ''].filter(Boolean).join(' ')}
              onClick={() => { setInputType(t); setValue(''); setError(''); }}
              aria-pressed={inputType === t}
            >
              <span style={{ display: 'inline-flex', alignItems: 'center', gap: 5 }}>
                <Icon name={t === 'phone' ? 'mobile' : 'direct'} size={14} color="currentColor" aria-hidden />
                {t === 'phone' ? 'Phone' : 'Email'}
              </span>
            </button>
          ))}
        </div>

        {/* Phone/Email input */}
        <div className={styles.inputWrap} data-error={!!error || undefined}>
          {inputType === 'phone' && (
            <select
              className={styles.countrySelect}
              value={countryCode.code}
              onChange={(e) => {
                const found = COUNTRY_CODES.find((c) => c.code === e.target.value);
                if (found) setCountryCode(found);
              }}
              aria-label="Country code"
            >
              {COUNTRY_CODES.map((c) => (
                <option key={c.code} value={c.code}>
                  {c.flag} {c.code}
                </option>
              ))}
            </select>
          )}
          <input
            className={styles.input}
            type={inputType === 'email' ? 'email' : 'tel'}
            inputMode={inputType === 'email' ? 'email' : 'numeric'}
            placeholder={inputType === 'phone' ? '09x xxx xxxx' : 'you@example.com'}
            value={value}
            onChange={(e) => { setValue(e.target.value); setError(''); }}
            autoComplete={inputType === 'email' ? 'email' : 'tel'}
            aria-label={inputType === 'phone' ? 'Phone number' : 'Email address'}
            aria-invalid={!!error}
            aria-describedby={error ? 'register-error' : undefined}
          />
        </div>
        {error && (
          <p id="register-error" className={styles.errorMsg} role="alert">
            {error}
          </p>
        )}

        <button type="submit" className={styles.submitBtn}>
          Continue
        </button>

        {mode === 'login' && (
          <button
            type="button"
            className={styles.forgotBtn}
            onClick={onForgot}
          >
            Forgot PIN or password?
          </button>
        )}
      </form>
    </div>
  );
}
