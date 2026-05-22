<?php

function stock_table_exists(mysqli $con, string $table): bool
{
    $table = mysqli_real_escape_string($con, $table);
    $result = mysqli_query($con, "SHOW TABLES LIKE '$table'");
    return $result && mysqli_num_rows($result) > 0;
}

function stock_column_exists(mysqli $con, string $table, string $column): bool
{
    if (!stock_table_exists($con, $table)) {
        return false;
    }
    $table = mysqli_real_escape_string($con, $table);
    $column = mysqli_real_escape_string($con, $column);
    $result = mysqli_query($con, "SHOW COLUMNS FROM `$table` LIKE '$column'");
    return $result && mysqli_num_rows($result) > 0;
}

function stock_require_get_params(array $params): bool
{
    foreach ($params as $key) {
        if (!isset($_GET[$key]) || $_GET[$key] === '') {
            return false;
        }
    }
    return true;
}

function stock_allowed_product_types(): array
{
    return ['jeans', 'shoes', 'top', 'complete', 'accessory', 'wig', 'cosmetics'];
}

function stock_allowed_product_type(string $type): bool
{
    return in_array($type, stock_allowed_product_types(), true);
}

function stock_product_type_labels(): array
{
    return [
        'jeans' => 'Jeans',
        'shoes' => 'Shoes',
        'top' => 'Top',
        'complete' => 'Complete',
        'accessory' => 'Accessory',
        'wig' => 'Wig',
        'cosmetics' => 'Cosmetics',
    ];
}

function stock_project_root(): string
{
    return dirname(__DIR__);
}

/** Public base URL for static assets (matches the host the mobile app uses). */
function stock_request_app_base_url(array $config): string
{
    $configured = rtrim((string) ($config['app_base_url'] ?? ''), '/');
    $host = $_SERVER['HTTP_HOST'] ?? '';
    if ($host === '') {
        return $configured !== '' ? $configured : 'http://127.0.0.1:8888/stock';
    }

    $scheme = 'http';
    if (
        (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
    ) {
        $scheme = 'https';
    }

    if ($configured !== '' && preg_match('#^https?://[^/]+(.*)$#', $configured, $m)) {
        $path = $m[1] !== '' ? $m[1] : '/stock';

        return $scheme . '://' . $host . $path;
    }

    return $scheme . '://' . $host . '/stock';
}

function stock_default_image_filename(string $category): string
{
    return match ($category) {
        'jeans' => 'defaultjeans.jpg',
        'shoes' => 'defaultshoes.jpg',
        'top' => 'defaulttop.jpg',
        'complete' => 'defaultcomplete.jpg',
        'accessory' => 'defaultaccessory.jpg',
        'wig' => 'defaultwig.jpg',
        'cosmetics' => 'defaultcosmetics.jpg',
        default => 'defaultjeans.jpg',
    };
}

/**
 * DB stores paths like uploads/foo.jpg; files live under include/uploads/.
 */
function stock_resolve_public_image_relative(?string $path, ?string $category = null): ?string
{
    if ($path === null || trim($path) === '') {
        if ($category === null) {
            return null;
        }
        $path = 'uploads/' . stock_default_image_filename($category);
    }

    $path = str_replace('\\', '/', trim($path));
    if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
        return $path;
    }

    $root = stock_project_root();
    $clean = ltrim($path, '/');
    $candidates = [$clean];
    if (str_starts_with($clean, 'uploads/')) {
        $candidates[] = 'include/' . $clean;
    }

    foreach ($candidates as $rel) {
        if (is_file($root . '/' . $rel)) {
            return $rel;
        }
    }

    if (str_starts_with($clean, 'uploads/')) {
        return 'include/' . $clean;
    }

    return $clean;
}

function stock_absolute_image_url(?string $path, string $baseUrl, ?string $category = null): ?string
{
    $resolved = stock_resolve_public_image_relative($path, $category);
    if ($resolved === null) {
        return null;
    }
    if (str_starts_with($resolved, 'http://') || str_starts_with($resolved, 'https://')) {
        return $resolved;
    }

    return rtrim($baseUrl, '/') . '/' . $resolved;
}
