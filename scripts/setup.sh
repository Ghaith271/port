#!/usr/bin/env bash
set -euo pipefail
cd "$(dirname "$0")/.."
if ! command -v php >/dev/null && [ -x /workspace/.tools/bin/php ]; then
  export PATH="/workspace/.tools/bin:$PATH"
fi
composer install --no-interaction "$@"
if [ ! -f .env ]; then cp .env.example .env; fi
if ! grep -q '^APP_KEY=base64:' .env; then php artisan key:generate --no-interaction; fi
# Laravel's default SQL connection needs a file before the first migration.
if [ ! -f database/database.sqlite ]; then touch database/database.sqlite; fi
php artisan migrate --force --no-interaction
php artisan db:seed --force --no-interaction
php artisan storage:link
npm ci --no-audit --no-fund
npm run build
echo 'Portfolio prepared. Create an administrator with: php artisan portfolio:admin'
