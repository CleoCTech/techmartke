// Generate all PWA icon sizes from the source SVGs.
// Run with: node scripts/generate-pwa-icons.mjs
import sharp from 'sharp';
import { readFileSync, mkdirSync } from 'fs';
import { resolve } from 'path';

const OUT = resolve('public/pwa');
const SRC = readFileSync(resolve('public/pwa/icon.svg'));
const SRC_MASKABLE = readFileSync(resolve('public/pwa/icon-maskable.svg'));

mkdirSync(OUT, { recursive: true });

// Standard icons
const sizes = [64, 192, 512];
for (const s of sizes) {
    await sharp(SRC, { density: 300 }).resize(s, s).png().toFile(`${OUT}/icon-${s}.png`);
    console.log(`✓ icon-${s}.png`);
}

// Maskable (Android adaptive icon)
await sharp(SRC_MASKABLE, { density: 300 }).resize(512, 512).png().toFile(`${OUT}/icon-maskable-512.png`);
console.log('✓ icon-maskable-512.png');

// Apple touch icon (iOS)
await sharp(SRC, { density: 300 }).resize(180, 180).png().toFile(`${OUT}/apple-touch-icon.png`);
console.log('✓ apple-touch-icon.png (180x180)');

// Favicon fallback
await sharp(SRC, { density: 300 }).resize(32, 32).png().toFile(`${OUT}/favicon-32.png`);
console.log('✓ favicon-32.png');

console.log('\nAll PWA icons generated in public/pwa/');
