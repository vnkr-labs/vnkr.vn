import { EnvironmentProviders, InjectionToken, makeEnvironmentProviders } from '@angular/core';
import { VNKRIcon, VNKRIcons } from '../types';

export interface IVNKRIconProvider {
  getIcon(name: string): VNKRIcon | null;
}

export class VNKRIconProvider implements IVNKRIconProvider {
  constructor(private readonly icons: VNKRIcons) {}

  getIcon(name: string): VNKRIcon | null {
    name = name.startsWith('Icon') ? name : `Icon${name}`;
    return this.iconExists(name) ? this.icons[name] : null;
  }

  private iconExists(name: string): boolean {
    return name in this.icons;
  }
}

export const VNKR_ICONS = new InjectionToken<IVNKRIconProvider[]>('VNKRIcons', {
  factory: () => []
});

/**
 * Provides a set of VNKR Icons to the application.
 * 
 * @example
 * ```ts
 * bootstrapApplication(AppComponent, {
 *   providers: [
 *     provideVNKRIcons({ IconHome, IconUser })
 *   ]
 * });
 * ```
 */
export function provideVNKRIcons(icons: VNKRIcons): EnvironmentProviders {
  return makeEnvironmentProviders([
    {
      provide: VNKR_ICONS,
      multi: true,
      useValue: new VNKRIconProvider(icons)
    }
  ]);
}
