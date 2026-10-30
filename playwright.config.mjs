import { defineConfig } from '@playwright/test';

export default defineConfig({
    testDir: './tests/browser',
    fullyParallel: false,
    workers: 1,
    timeout: 30_000,
    expect: { timeout: 6_000 },
    reporter: 'list',
    use: {
        baseURL: process.env.PLAYWRIGHT_BASE_URL || 'http://127.0.0.1:8000',
        viewport: { width: 1440, height: 1000 },
        browserName: 'chromium',
        launchOptions: {
            executablePath: process.env.CHROMIUM_PATH || '/usr/bin/chromium',
            args: ['--no-sandbox', '--disable-dev-shm-usage'],
        },
        screenshot: 'only-on-failure',
        trace: 'retain-on-failure',
    },
});
