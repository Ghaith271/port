import { spawn } from 'node:child_process';
import { randomBytes } from 'node:crypto';
import { mkdtemp, mkdir, writeFile, rm } from 'node:fs/promises';
import { tmpdir } from 'node:os';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
import { setTimeout as delay } from 'node:timers/promises';

const root = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const temporaryDirectory = await mkdtemp(path.join(tmpdir(), 'portfolio-browser-'));
const database = path.join(temporaryDirectory, 'database.sqlite');
const views = path.join(temporaryDirectory, 'views');
await writeFile(database, '');
await mkdir(views);
const port = Number(process.env.BROWSER_TEST_PORT || 8123);
if (!Number.isInteger(port) || port < 1024 || port > 65535) {
    await rm(temporaryDirectory, { recursive: true, force: true });
    throw new Error('BROWSER_TEST_PORT must be a port between 1024 and 65535.');
}
const baseURL = `http://127.0.0.1:${port}`;
const environment = {
    ...process.env,
    APP_ENV: 'local',
    APP_DEBUG: 'false',
    APP_KEY: `base64:${randomBytes(32).toString('base64')}`,
    APP_URL: baseURL,
    APP_CONFIG_CACHE: path.join(temporaryDirectory, 'config.php'),
    APP_ROUTES_CACHE: path.join(temporaryDirectory, 'routes.php'),
    APP_EVENTS_CACHE: path.join(temporaryDirectory, 'events.php'),
    VIEW_COMPILED_PATH: views,
    DB_CONNECTION: 'sqlite',
    DB_DATABASE: database,
    DB_URL: '',
    CACHE_STORE: 'array',
    SESSION_DRIVER: 'file',
    SESSION_COOKIE: `portfolio_browser_${randomBytes(8).toString('hex')}`,
    SESSION_SECURE_COOKIE: 'false',
    SESSION_DOMAIN: '',
    QUEUE_CONNECTION: 'sync',
    MAIL_MAILER: 'log',
    BROWSER_TEST_EMAIL: `browser-admin-${randomBytes(8).toString('hex')}@example.test`,
    BROWSER_TEST_PASSWORD: randomBytes(32).toString('base64url'),
    PLAYWRIGHT_BASE_URL: baseURL,
};
const php = process.env.PHP_BINARY || 'php';
let server;
let browser;
let output = '';
let shuttingDown = false;

const run = (command, args, options = {}) => new Promise((resolve, reject) => {
    const child = spawn(command, args, { cwd: root, env: environment, stdio: 'inherit', ...options });
    child.once('error', reject);
    child.once('exit', (code, signal) => {
        if (code === 0) resolve();
        else reject(new Error(`${path.basename(command)} failed${signal ? ` (${signal})` : ` with exit code ${code}`}.`));
    });
});

const stop = child => {
    if (!child) return;
    try {
        if (process.platform === 'win32' && child.exitCode === null) child.kill('SIGTERM');
        else process.kill(-child.pid, 'SIGTERM');
    } catch (error) {
        if (error.code !== 'ESRCH') throw error;
    }
};
const cleanup = async () => {
    if (shuttingDown) return;
    shuttingDown = true;
    stop(browser);
    stop(server);
    await rm(temporaryDirectory, { recursive: true, force: true });
};
for (const signal of ['SIGINT', 'SIGTERM']) {
    process.once(signal, () => {
        cleanup().finally(() => process.exit(signal === 'SIGINT' ? 130 : 143));
    });
}

try {
    console.log('Preparing an isolated browser-test database…');
    await run(php, ['artisan', 'migrate', '--seed', '--force', '--no-interaction']);
    await run(php, ['-r', `require 'vendor/autoload.php'; $app = require 'bootstrap/app.php'; $app->make(Illuminate\\Contracts\\Console\\Kernel::class)->bootstrap(); App\\Models\\User::create(['name' => 'Browser Test Admin', 'email' => getenv('BROWSER_TEST_EMAIL'), 'password' => getenv('BROWSER_TEST_PASSWORD')]);`]);
    server = spawn(php, ['artisan', 'serve', '--host=127.0.0.1', `--port=${port}`, '--tries=1', '--no-reload'], {
        cwd: root,
        env: environment,
        detached: process.platform !== 'win32',
        stdio: ['ignore', 'pipe', 'pipe'],
    });
    let serverError;
    server.on('error', error => { serverError = error; });
    for (const stream of [server.stdout, server.stderr]) {
        stream.on('data', chunk => { output = (output + chunk.toString()).slice(-12_000); });
    }
    const deadline = Date.now() + 10_000;
    let ready = false;
    while (Date.now() < deadline) {
        if (serverError) throw serverError;
        if (server.exitCode !== null) throw new Error(`The isolated application server exited before readiness.\n${output}`);
        try {
            const response = await fetch(`${baseURL}/up`, { signal: AbortSignal.timeout(700) });
            if (response.ok) {
                ready = true;
                break;
            }
        } catch {
            // Startup can take a moment; retry within the bounded readiness window.
        }
        await delay(120);
    }
    if (!ready) throw new Error(`The isolated server did not become ready within 10 seconds.\n${output}`);
    await delay(100);
    if (server.exitCode !== null) throw new Error(`The isolated server stopped after startup.\n${output}`);
    console.log('Running browser checks against the isolated application…');
    const exitCode = await new Promise((resolve, reject) => {
        browser = spawn(process.execPath, ['node_modules/@playwright/test/cli.js', 'test', ...process.argv.slice(2)], {
            cwd: root,
            env: environment,
            detached: process.platform !== 'win32',
            stdio: 'inherit',
        });
        browser.once('error', reject);
        browser.once('exit', (code, signal) => resolve(code ?? (signal ? 1 : 0)));
    });
    process.exitCode = exitCode;
} catch (error) {
    console.error(error.message);
    process.exitCode = 1;
} finally {
    await cleanup();
}
