import { resolveTLPLevel, getTLPConfig, TLP_CONFIG } from '../types/tlp';

describe('resolveTLPLevel', () => {
  it('returns safe for .axq namespaces', () => {
    expect(resolveTLPLevel('alice.axq')).toBe('safe');
    expect(resolveTLPLevel('ALICE.AXQ')).toBe('safe');
    expect(resolveTLPLevel('multi.word.axq')).toBe('safe');
  });

  it('returns safe for .vrq namespaces', () => {
    expect(resolveTLPLevel('identity.vrq')).toBe('safe');
  });

  it('returns caution for .kpx namespaces', () => {
    expect(resolveTLPLevel('pool.kpx')).toBe('caution');
    expect(resolveTLPLevel('bridge.kpx')).toBe('caution');
  });

  it('returns system for .sqx namespaces', () => {
    expect(resolveTLPLevel('relay.sqx')).toBe('system');
  });

  it('returns system for .vpx namespaces', () => {
    expect(resolveTLPLevel('zkp.vpx')).toBe('system');
  });

  it('returns blocked for raw 0x addresses', () => {
    expect(resolveTLPLevel('0xAbCdEf1234567890AbCdEf1234567890AbCdEf12')).toBe('blocked');
    expect(resolveTLPLevel('0x1234567890abcdef1234567890abcdef12345678')).toBe('blocked');
  });

  it('returns blocked for unknown strings', () => {
    expect(resolveTLPLevel('unknown.xyz')).toBe('blocked');
    expect(resolveTLPLevel('')).toBe('blocked');
    expect(resolveTLPLevel('   ')).toBe('blocked');
  });

  it('handles null/undefined gracefully', () => {
    // @ts-expect-error intentional bad input
    expect(resolveTLPLevel(null)).toBe('blocked');
    // @ts-expect-error intentional bad input
    expect(resolveTLPLevel(undefined)).toBe('blocked');
  });
});

describe('getTLPConfig', () => {
  it('returns correct config for safe level', () => {
    const config = getTLPConfig('alice.axq');
    expect(config.level).toBe('safe');
    expect(config.color).toBe('var(--tlp-safe)');
    expect(config.label).toBe('Safe');
  });

  it('returns correct config for blocked level', () => {
    const config = getTLPConfig('0x1234567890abcdef1234567890abcdef12345678');
    expect(config.level).toBe('blocked');
    expect(config.label).toBe('Unverified');
  });
});

describe('TLP_CONFIG', () => {
  it('has entries for all 4 levels', () => {
    const levels: Array<keyof typeof TLP_CONFIG> = ['safe', 'caution', 'blocked', 'system'];
    levels.forEach((l) => {
      expect(TLP_CONFIG[l]).toBeDefined();
      expect(TLP_CONFIG[l].color).toMatch(/var\(--tlp-/);
      expect(TLP_CONFIG[l].ariaDescription).toBeTruthy();
    });
  });
});
