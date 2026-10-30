# Browser checks

Run `npm run test:browser` from the repository root after installing PHP/Composer dependencies, running `npm ci`, and building local assets with `npm run build`.

The harness creates a temporary SQLite database, random application key, unique session cookie, and temporary administrator. It migrates and seeds that database, starts an isolated Laravel server on `127.0.0.1:8123`, and waits for `/up` before running Playwright. It keeps `APP_ENV=local` so Laravel's real CSRF protection remains enabled. It does not reset or write to the development database. Test credentials are generated in memory and never printed. The isolated server and database are removed when the run finishes, including failures.

PHP must be available as `php` on `PATH`; `PHP_BINARY` can select another executable. Chromium defaults to `/usr/bin/chromium`; `CHROMIUM_PATH` can select another installed browser. `BROWSER_TEST_PORT` can change the isolated server port when necessary. The Playwright CLI's arguments can be forwarded, for example `npm run test:browser -- --grep mobile`.

The checks cover public rendering and local assets, desktop/mobile layout, project filtering, mobile navigation, reduced-motion behavior, CSRF rejection, contact submission and persistence, anonymous access restrictions, and authenticated project creation, editing, and deletion. Contact and CRUD data remain inside the disposable database.

Desktop, mobile, and dashboard screenshots are saved to `/tmp/portfolio-desktop.png`, `/tmp/portfolio-mobile.png`, and `/tmp/portfolio-admin.png`. Failure screenshots and traces are written to Playwright's `test-results` directory. Run the complete suite to produce all screenshots. Direct `playwright test` invocation is intended for debugging with a separately prepared test server and test credentials; use the harness for ordinary checks.
