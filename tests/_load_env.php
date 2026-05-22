<?php

declare(strict_types=1);

/**
 * Loads tests/.env.test then tests/env.example defaults.
 */
function tests_load_env(): array
{
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }

    $defaults = [];
    $example = __DIR__ . '/env.example';
    if (is_file($example)) {
        foreach (file($example, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            $line = trim($line);
            if ($line === '' || (isset($line[0]) && $line[0] === '#')) {
                continue;
            }
            if (strpos($line, '=') !== false) {
                [$k, $v] = explode('=', $line, 2);
                $defaults[trim($k)] = trim($v);
            }
        }
    }

    $local = __DIR__ . '/.env.test';
    if (is_file($local)) {
        foreach (file($local, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            $line = trim($line);
            if ($line === '' || (isset($line[0]) && $line[0] === '#')) {
                continue;
            }
            if (strpos($line, '=') !== false) {
                [$k, $v] = explode('=', $line, 2);
                $defaults[trim($k)] = trim($v);
            }
        }
    }

    foreach ($defaults as $k => $v) {
        if (getenv($k) === false) {
            putenv("$k=$v");
        }
    }

    $cache = [
        'api_base' => getenv('TEST_API_BASE') ?: 'http://127.0.0.1:8888/stock/api/v1/index.php',
        'mysql_host' => getenv('TEST_MYSQL_HOST') ?: 'localhost',
        'mysql_user' => getenv('TEST_MYSQL_USER') ?: 'root',
        'mysql_pass' => getenv('TEST_MYSQL_PASS') ?: 'root',
        'shop_db' => getenv('TEST_SHOP_DB') ?: 'stock_test',
        'shop_slug' => getenv('TEST_SHOP_SLUG') ?: 'testshop',
        'user' => getenv('TEST_USER') ?: 'masteradmin',
        'pass' => getenv('TEST_PASS') ?: 'admin',
        'limited_user' => getenv('TEST_LIMITED_USER') ?: 'testuser',
        'limited_pass' => getenv('TEST_LIMITED_PASS') ?: 'testpass',
    ];

    return $cache;
}
