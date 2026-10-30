<?php

use Symfony\Component\Process\Process;

require dirname(__DIR__).'/vendor/autoload.php';

$root = dirname(__DIR__);

$run = static function (array|string $command) use ($root): void {
    $process = is_array($command)
        ? new Process($command, $root)
        : Process::fromShellCommandline($command, $root);
    $process->setTimeout(null);
    $process->run(static function (string $type, string $buffer): void {
        fwrite($type === Process::ERR ? STDERR : STDOUT, $buffer);
    });

    if (! $process->isSuccessful()) {
        throw new RuntimeException('Setup command failed: '.$process->getCommandLine());
    }
};

try {
    if (! file_exists($root.'/.env')) {
        if (! copy($root.'/.env.example', $root.'/.env')) {
            throw new RuntimeException('Unable to create the .env file.');
        }
    }

    $environment = file_get_contents($root.'/.env');
    if ($environment === false) {
        throw new RuntimeException('Unable to read the .env file.');
    }

    if (! preg_match('/^APP_KEY=(?:base64:.+|.+)$/m', $environment)) {
        $run([PHP_BINARY, 'artisan', 'key:generate', '--no-interaction']);
    }

    $database = $root.'/database/database.sqlite';
    if (! file_exists($database) && ! touch($database)) {
        throw new RuntimeException('Unable to create the SQLite database file.');
    }

    $commands = [
        [PHP_BINARY, 'artisan', 'migrate', '--force', '--no-interaction'],
        [PHP_BINARY, 'artisan', 'db:seed', '--force', '--no-interaction'],
    ];

    if (! file_exists($root.'/public/storage') && ! is_link($root.'/public/storage')) {
        $commands[] = [PHP_BINARY, 'artisan', 'storage:link'];
    }

    foreach ([
        PHP_OS_FAMILY === 'Windows' ? 'npm.cmd ci --no-audit --no-fund' : 'npm ci --no-audit --no-fund',
        PHP_OS_FAMILY === 'Windows' ? 'npm.cmd run build' : 'npm run build',
    ] as $command) {
        $commands[] = $command;
    }

    foreach ($commands as $command) {
        $run($command);
    }

    fwrite(STDOUT, "Portfolio prepared. Create an administrator with: php artisan portfolio:admin\n");
} catch (Throwable $exception) {
    fwrite(STDERR, $exception->getMessage().PHP_EOL);
    exit(1);
}
