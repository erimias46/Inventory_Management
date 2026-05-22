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
    $table  = mysqli_real_escape_string($con, $table);
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

/* ── Category helpers (DB-driven) ───────────────────────────────── */

/**
 * Returns all enabled categories as full rows, ordered by sort_order.
 * Falls back to the hardcoded list if the table doesn't exist yet.
 */
function stock_get_categories(?mysqli $con = null): array
{
    static $cache = null;
    if ($cache !== null) return $cache;

    if ($con === null && isset($GLOBALS['con'])) $con = $GLOBALS['con'];

    if ($con !== null && stock_table_exists($con, 'categories')) {
        $res = mysqli_query($con, "SELECT * FROM categories WHERE enabled=1 ORDER BY sort_order ASC, id ASC");
        if ($res && mysqli_num_rows($res) > 0) {
            $cache = [];
            while ($r = mysqli_fetch_assoc($res)) $cache[] = $r;
            return $cache;
        }
    }

    // Fallback — no categories table yet
    $cache = [
        ['id'=>1,'slug'=>'jeans',    'label'=>'Jeans',    'icon'=>'fas fa-scroll',     'sort_order'=>1,'enabled'=>1,'default_image'=>'defaultjeans.jpg',    'page_folder'=>'price_calculator','file_prefix'=>'jeans',    'add_file'=>'add_single_jeans.php'],
        ['id'=>2,'slug'=>'shoes',    'label'=>'Shoes',    'icon'=>'fas fa-shoe-prints', 'sort_order'=>2,'enabled'=>1,'default_image'=>'defaultshoes.jpg',    'page_folder'=>'shoe',            'file_prefix'=>'shoes',    'add_file'=>'add_shoes.php'],
        ['id'=>3,'slug'=>'top',      'label'=>'Top',      'icon'=>'fas fa-tshirt',      'sort_order'=>3,'enabled'=>1,'default_image'=>'defaulttop.jpg',      'page_folder'=>'top',             'file_prefix'=>'top',      'add_file'=>'add_top.php'],
        ['id'=>4,'slug'=>'complete', 'label'=>'Complete', 'icon'=>'fas fa-box-open',    'sort_order'=>4,'enabled'=>1,'default_image'=>'defaultcomplete.jpg', 'page_folder'=>'complete',        'file_prefix'=>'complete', 'add_file'=>'add_complete.php'],
        ['id'=>5,'slug'=>'accessory','label'=>'Accessory','icon'=>'fas fa-gem',         'sort_order'=>5,'enabled'=>1,'default_image'=>'defaultaccessory.jpg','page_folder'=>'accessory',       'file_prefix'=>'accessory','add_file'=>'add_accessory.php'],
        ['id'=>6,'slug'=>'wig',      'label'=>'Wig',      'icon'=>'fas fa-hat-wizard',  'sort_order'=>6,'enabled'=>1,'default_image'=>'defaultwig.jpg',      'page_folder'=>'wig',             'file_prefix'=>'wig',      'add_file'=>'add_wig.php'],
        ['id'=>7,'slug'=>'cosmetics','label'=>'Cosmetics','icon'=>'fas fa-spa',         'sort_order'=>7,'enabled'=>1,'default_image'=>'defaultcosmetics.jpg','page_folder'=>'cosmetics',       'file_prefix'=>'cosmetics','add_file'=>'add_cosmetics.php'],
    ];
    return $cache;
}

function stock_get_category(?mysqli $con = null, string $slug = ''): ?array
{
    if ($con === null && isset($GLOBALS['con'])) $con = $GLOBALS['con'];

    $slug = preg_replace('/[^a-z0-9_]/', '', strtolower($slug));
    if ($slug === '') return null;

    if ($con !== null && stock_table_exists($con, 'categories')) {
        $res = mysqli_query($con, "SELECT * FROM categories WHERE slug='$slug' AND enabled=1 LIMIT 1");
        if ($res) return mysqli_fetch_assoc($res) ?: null;
    }

    foreach (stock_get_categories($con) as $cat) {
        if ($cat['slug'] === $slug) return $cat;
    }
    return null;
}

function stock_allowed_product_types(?mysqli $con = null): array
{
    return array_column(stock_get_categories($con), 'slug');
}

function stock_allowed_product_type(string $type, ?mysqli $con = null): bool
{
    return in_array($type, stock_allowed_product_types($con), true);
}

function stock_product_type_labels(?mysqli $con = null): array
{
    $out = [];
    foreach (stock_get_categories($con) as $cat) {
        $out[$cat['slug']] = $cat['label'];
    }
    return $out;
}

function stock_default_image_filename(string $category, ?mysqli $con = null): string
{
    $cat = stock_get_category($con, $category);
    if ($cat && !empty($cat['default_image'])) return $cat['default_image'];
    return 'default' . preg_replace('/[^a-z]/', '', strtolower($category)) . '.jpg';
}

/**
 * Builds the nav link set for a category used by sidenav.
 */
function stock_category_nav_links(array $cat, string $base): array
{
    $folder  = $cat['page_folder'];
    $prefix  = $cat['file_prefix'];
    $slug    = $cat['slug'];
    $generic = ($folder === 'category' || $prefix === '');
    $b       = $base . 'pages/' . $folder . '/';
    $q       = '?cat=' . urlencode($slug);

    if ($generic) {
        return [
            'add'      => $b . 'add.php'       . $q,
            'all'      => $b . 'all.php'        . $q,
            'type'     => $b . 'type.php'       . $q,
            'sale'     => $b . 'sale.php'       . $q,
            'log'      => $b . 'stock_log.php'  . $q,
            'verify'   => $b . 'verify.php'     . $q,
            'exchange' => $b . 'exchange.php'   . $q,
            'refund'   => $b . 'refund.php'     . $q,
            'delivery' => $b . 'delivery.php'   . $q,
        ];
    }

    return [
        'add'      => $b . $cat['add_file'],
        'all'      => $b . 'all_'    . $prefix . '.php',
        'type'     => $b . 'type_'   . $prefix . '.php',
        'sale'     => $b . 'sale_'   . $prefix . '.php',
        'log'      => $b . $prefix   . '_stock_log.php',
        'verify'   => $b . 'verify.php',
        'exchange' => $b . 'exchange.php',
        'refund'   => $b . 'refund.php',
        'delivery' => $b . 'delivery.php',
    ];
}

/* ── URL / image helpers ─────────────────────────────────────────── */

function stock_project_root(): string
{
    return dirname(__DIR__);
}

function stock_request_app_base_url(array $config): string
{
    $configured = rtrim((string)($config['app_base_url'] ?? ''), '/');
    $host       = $_SERVER['HTTP_HOST'] ?? '';
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

function stock_resolve_public_image_relative(?string $path, ?string $category = null, ?mysqli $con = null): ?string
{
    if ($path === null || trim($path) === '') {
        if ($category === null) return null;
        $path = 'uploads/' . stock_default_image_filename($category, $con);
    }

    $path = str_replace('\\', '/', trim($path));
    if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
        return $path;
    }

    $root  = stock_project_root();
    $clean = ltrim($path, '/');
    $candidates = [$clean];
    if (str_starts_with($clean, 'uploads/')) {
        $candidates[] = 'include/' . $clean;
    }

    foreach ($candidates as $rel) {
        if (is_file($root . '/' . $rel)) return $rel;
    }

    if (str_starts_with($clean, 'uploads/')) {
        return 'include/' . $clean;
    }

    return $clean;
}

function stock_absolute_image_url(?string $path, string $baseUrl, ?string $category = null, ?mysqli $con = null): ?string
{
    $resolved = stock_resolve_public_image_relative($path, $category, $con);
    if ($resolved === null) return null;
    if (str_starts_with($resolved, 'http://') || str_starts_with($resolved, 'https://')) {
        return $resolved;
    }
    return rtrim($baseUrl, '/') . '/' . $resolved;
}
