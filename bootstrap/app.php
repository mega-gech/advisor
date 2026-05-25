<?php

/**
 * Application bootstrap – load once from public/index.php
 */

declare(strict_types=1);

define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('VIEWS_PATH', APP_PATH . '/Views');
define('STORAGE_PATH', ROOT_PATH . '/storage');

$envFile = ROOT_PATH . '/.env';
if (is_file($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        putenv(trim($key) . '=' . trim($value, " \t\"'"));
    }
}

spl_autoload_register(static function (string $class): void {
    $directories = [
        APP_PATH . '/Core',
        APP_PATH . '/Controllers',
        APP_PATH . '/Models',
        APP_PATH . '/Services',
        CONFIG_PATH,
    ];

    foreach ($directories as $directory) {
        $file = $directory . '/' . $class . '.php';
        if (is_file($file)) {
            require_once $file;
            return;
        }
    }
});

require_once APP_PATH . '/Support/helpers.php';

$config = require CONFIG_PATH . '/app.php';
date_default_timezone_set($config['timezone'] ?? 'Africa/Addis_Ababa');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
