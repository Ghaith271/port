import { mkdir, copyFile, readdir } from 'node:fs/promises';
import { resolve } from 'node:path';

const root = resolve(import.meta.dirname, '..');
const bootstrap = resolve(root, 'public/vendor/bootstrap');
const fonts = resolve(root, 'public/vendor/fonts');
await Promise.all([mkdir(bootstrap, { recursive: true }), mkdir(fonts, { recursive: true })]);
await Promise.all([
  copyFile(resolve(root, 'node_modules/bootstrap/dist/css/bootstrap.rtl.min.css'), resolve(bootstrap, 'bootstrap.rtl.min.css')),
  copyFile(resolve(root, 'node_modules/bootstrap/dist/js/bootstrap.bundle.min.js'), resolve(bootstrap, 'bootstrap.bundle.min.js')),
  copyFile(resolve(root, 'node_modules/bootstrap/LICENSE'), resolve(bootstrap, 'LICENSE')),
  copyFile(resolve(root, 'node_modules/@fontsource/tajawal/LICENSE'), resolve(fonts, 'LICENSE')),
]);
const fontRoot = resolve(root, 'node_modules/@fontsource/tajawal/files');
for (const file of await readdir(fontRoot)) {
  if (/^tajawal-(arabic|latin)-(400|500|700|800)-normal\.woff2$/.test(file)) {
    await copyFile(resolve(fontRoot, file), resolve(fonts, file));
  }
}
console.log('Bootstrap and Tajawal assets built locally.');
