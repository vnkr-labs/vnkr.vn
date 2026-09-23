/**
 * scripts/build-icon-paths.mjs
 *
 * Run: node scripts/build-icon-paths.mjs
 *
 * Reads all SVG files from assets/img/icon/ and generates:
 *   packages/design-system/src/components/Icon/iconPaths.generated.tsx
 *
 * This replaces hardcoded stroke colors with currentColor so the Icon
 * component can control color via CSS.
 */
import { readFileSync, writeFileSync, readdirSync } from 'fs';
import { join, basename } from 'path';

const ICON_DIR = join(process.cwd(), 'assets/img/icon');
const OUT_FILE = join(
  process.cwd(),
  'packages/design-system/src/components/Icon/iconPaths.generated.tsx'
);

const files = readdirSync(ICON_DIR).filter((f) => f.endsWith('.svg')).sort();

const entries = files.map((file) => {
  const name = basename(file, '.svg');
  const raw = readFileSync(join(ICON_DIR, file), 'utf-8');

  // Extract inner content between <svg ...> and </svg>
  const inner = raw
    .replace(/<svg[^>]*>/i, '')
    .replace(/<\/svg>/i, '')
    .trim()
    // Remove hardcoded stroke colors — Icon component uses currentColor via <g>
    .replace(/\sstroke="#[0-9A-Fa-f]{3,8}"/g, '')
    // Remove hardcoded fill colors (except fill="none" which must stay)
    .replace(/\sfill="#[0-9A-Fa-f]{3,8}"/g, '')
    // Replace any remaining fill="black" or fill="white"
    .replace(/\sfill="black"/g, '')
    .replace(/\sfill="white"/g, ' fill="none"');

  return `  '${name}': <>${inner}</>,`;
});

const output = `/**
 * AUTO-GENERATED — do not edit manually.
 * Run: node scripts/build-icon-paths.mjs
 * Source: assets/img/icon/*.svg (${files.length} icons)
 */
/* eslint-disable */
import React from 'react';
import type { IconName } from './Icon';

export const iconPaths: Partial<Record<IconName, React.ReactNode>> = {
${entries.join('\n')}
};
`;

writeFileSync(OUT_FILE, output, 'utf-8');
console.log(`✅ Generated ${files.length} icon paths → ${OUT_FILE}`);
