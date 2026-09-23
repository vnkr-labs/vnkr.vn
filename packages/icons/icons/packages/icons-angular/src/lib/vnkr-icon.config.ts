import { EnvironmentProviders, InjectionToken, makeEnvironmentProviders } from '@angular/core';

export interface VNKRIconConfig {
  size?: number;
  color?: string;
  stroke?: number;
}

export const VNKR_ICON_CONFIG = new InjectionToken<VNKRIconConfig>('VNKRIconConfig');

/**
 * Provides the configuration for VNKR Icons.
 *
 * @example
 * ```ts
 * bootstrapApplication(AppComponent, {
 *   providers: [
 *     provideVNKRIconConfig({
 *       size: 24,
 *       color: 'red',
 *       stroke: 2
 *     })
 *   ]
 * });
 * ```
 */
export function provideVNKRIconConfig(config: VNKRIconConfig): EnvironmentProviders {
  return makeEnvironmentProviders([{ provide: VNKR_ICON_CONFIG, useValue: config }]);
}
