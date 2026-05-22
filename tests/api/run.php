<?php

declare(strict_types=1);

/**
 * API integration test runner.
 * Usage: php tests/api/run.php
 */

$root = dirname(__DIR__, 2);
require_once $root . '/tests/_load_env.php';
require_once __DIR__ . '/Client.php';

$env = tests_load_env();
$runner = new ApiTestRunner();
$client = new ApiTestClient($env['api_base']);

echo "API base: {$env['api_base']}\n";
echo "Shop: {$env['shop_slug']} ({$env['shop_db']})\n";

/* Health */
$runner->section('Health');
$h = $client->request('GET', '/health', null, false);
$runner->assert(($h['json']['ok'] ?? false) === true, 'GET /health ok');
$runner->assert(($h['json']['data']['database'] ?? '') === 'connected', 'database connected', $h['raw']);

/* Shops */
$runner->section('Multi-tenant / shops');
$shops = $client->request('GET', '/shops', null, false);
$runner->assert(($shops['json']['ok'] ?? false) === true, 'GET /shops');
$shopList = $shops['json']['data'] ?? [];
$runner->assert(is_array($shopList), '/shops returns array');
$hasTestShop = false;
foreach ($shopList as $s) {
    if (($s['slug'] ?? '') === $env['shop_slug']) {
        $hasTestShop = true;
        break;
    }
}
$runner->assert($hasTestShop, 'test shop in /shops (run setup_test_shop.php if missing)', $env['shop_slug']);

/* Auth */
$runner->section('Auth');
$badShop = $client->request('POST', '/auth/login', null, false);
// wrong login body
$badLogin = $client->request('POST', '/auth/login', [
    'username' => 'nobody',
    'password' => 'wrong',
    'shop_slug' => $env['shop_slug'],
], false);
$runner->assert(($badLogin['json']['ok'] ?? true) === false, 'invalid login rejected');

$login = $client->request('POST', '/auth/login', [
    'username' => $env['user'],
    'password' => $env['pass'],
    'shop_slug' => $env['shop_slug'],
], false);
$token = $login['json']['data']['token'] ?? '';
$runner->assert($token !== '', 'login with shop_slug', $login['raw'] ?? '');
$client->setToken($token);

$me = $client->request('GET', '/auth/me');
$runner->assert(($me['json']['ok'] ?? false) === true, 'GET /auth/me');
$runner->assert(($me['json']['data']['user']['user_name'] ?? '') === $env['user'], 'auth/me user name');

$noAuth = $client->request('GET', '/sales', null, false);
$client->setToken(null);
$runner->assert(($noAuth['code'] === 401) || (($noAuth['json']['ok'] ?? true) === false), 'protected route without token');
$client->setToken($token);

/* Products */
$runner->section('Products');
$cats = $client->request('GET', '/products/categories');
$runner->assert(($cats['json']['ok'] ?? false) === true, 'GET /products/categories');
$catData = $cats['json']['data'] ?? [];
$runner->assert(is_array($catData) && count($catData) > 0, 'categories non-empty');

$types = $client->request('GET', '/products/types');
$runner->assert(($types['json']['ok'] ?? false) === true, 'GET /products/types');

$names = $client->request('GET', '/products/names?type=jeans');
$runner->assert(($names['json']['ok'] ?? false) === true, 'GET /products/names jeans');

$sizes = $client->request('GET', '/products/sizes?type=jeans&name=Test+Jean');
$runner->assert(($sizes['json']['ok'] ?? false) === true, 'GET /products/sizes');

$price = $client->request('GET', '/products/price?type=jeans&name=Test+Jean&size=M');
$runner->assert(($price['json']['ok'] ?? false) === true, 'GET /products/price');

$search = $client->request('GET', '/products/search?type=jeans&q=Test');
$runner->assert(($search['json']['ok'] ?? false) === true, 'GET /products/search');

$searchAll = $client->request('GET', '/products/search-all?q=Test');
$runner->assert(($searchAll['json']['ok'] ?? false) === true, 'GET /products/search-all');

$multiSearch = $client->request('POST', '/products/search-multi', ['type' => 'jeans', 'sizes' => ['M']]);
$runner->assert(($multiSearch['json']['ok'] ?? false) === true, 'POST /products/search-multi');

/* Sales */
$runner->section('Sales');
$deliveryNoReason = $client->request('POST', '/sales/multi', [
    'method' => 'delivery',
    'lines' => [['type' => 'jeans', 'name' => 'Test Jean', 'size' => 'M', 'price' => 100, 'cash' => 100, 'bank' => 0, 'quantity' => 1]],
]);
$runner->assert(($deliveryNoReason['json']['ok'] ?? true) === false, 'delivery without reason fails');

$shopSale = $client->request('POST', '/sales/multi', [
    'method' => 'shop',
    'lines' => [['type' => 'jeans', 'name' => 'Test Jean', 'size' => 'M', 'price' => 100, 'cash' => 100, 'bank' => 0, 'quantity' => 1]],
]);
$runner->assert(($shopSale['json']['ok'] ?? false) === true, 'POST /sales/multi shop sale', $shopSale['raw'] ?? '');

$salesList = $client->request('GET', '/sales');
$runner->assert(($salesList['json']['ok'] ?? false) === true, 'GET /sales');
$saleItems = $salesList['json']['data']['items'] ?? $salesList['json']['data'] ?? [];
if (is_array($saleItems) && count($saleItems) > 0) {
    $first = $saleItems[0];
    $type = $first['source'] ?? $first['type'] ?? 'jeans';
    $id = (int) ($first['id'] ?? $first['sales_id'] ?? 0);
    if ($id > 0) {
        $detail = $client->request('GET', "/sales/$type/$id");
        $runner->assert(($detail['json']['ok'] ?? false) === true, "GET /sales/$type/$id");
    }
}

$logs = $client->request('GET', '/sales/logs');
$runner->assert(($logs['json']['ok'] ?? false) === true, 'GET /sales/logs');

/* Dashboard */
$runner->section('Dashboard');
$summary = $client->request('GET', '/dashboard/summary?period=30');
$runner->assert(($summary['json']['ok'] ?? false) === true, 'GET /dashboard/summary');

$daily = $client->request('GET', '/dashboard/daily-sales?month=' . date('n') . '&year=' . date('Y'));
$runner->assert(($daily['json']['ok'] ?? false) === true, 'GET /dashboard/daily-sales');
$runner->assert(isset($daily['json']['data']['series']), 'daily-sales has series');

$overview = $client->request('GET', '/dashboard/overview?period=30');
$runner->assert(($overview['json']['ok'] ?? false) === true, 'GET /dashboard/overview');
$ov = $overview['json']['data'] ?? [];
foreach (['kpis', 'today', 'activity', 'by_category', 'banks', 'top_products', 'stock', 'monthly'] as $key) {
    $runner->assert(array_key_exists($key, $ov), "overview has $key");
}

/* Ops */
$runner->section('Operations');
foreach ([
    '/ops/sale-log',
    '/ops/products-in',
    '/ops/all-product-types',
    '/ops/refunds?type=jeans',
    '/ops/exchanges?type=jeans',
    '/ops/stock-logs?type=jeans',
    '/ops/constants',
    '/ops/settings',
    '/ops/backups',
    '/ops/emails',
    '/ops/digital-pages',
] as $path) {
    $r = $client->request('GET', $path);
    $runner->assert(($r['json']['ok'] ?? false) === true, "GET $path", $r['json']['error']['message'] ?? '');
}

/* Users */
$runner->section('Users');
$users = $client->request('GET', '/users');
$runner->assert(($users['json']['ok'] ?? false) === true, 'GET /users');

$keys = $client->request('GET', '/users/module-keys');
$runner->assert(($keys['json']['ok'] ?? false) === true, 'GET /users/module-keys');

$profile = $client->request('PUT', '/users/me/profile', [
    'user_name' => $env['user'],
    'password' => $env['pass'],
]);
$runner->assert(($profile['json']['ok'] ?? false) === true, 'PUT /users/me/profile');

/* Inventory */
$runner->section('Inventory');
$inv = $client->request('GET', '/inventory/jeans');
$runner->assert(($inv['json']['ok'] ?? false) === true, 'GET /inventory/jeans');

$invTypes = $client->request('GET', '/inventory/jeans/types');
$runner->assert(($invTypes['json']['ok'] ?? false) === true, 'GET /inventory/jeans/types');

/* Delivery / verify / customers / banks */
$runner->section('Other modules');
$delivery = $client->request('GET', '/delivery');
$runner->assert(($delivery['json']['ok'] ?? false) === true, 'GET /delivery');

$verify = $client->request('GET', '/verify/queue?type=jeans');
$runner->assert(($verify['json']['ok'] ?? false) === true, 'GET /verify/queue');

$customers = $client->request('GET', '/customers/manage');
$runner->assert(($customers['json']['ok'] ?? false) === true, 'GET /customers/manage');

$banks = $client->request('GET', '/banks', null, false);
$runner->assert(($banks['json']['ok'] ?? false) === true, 'GET /banks (public)');

/* Permissions — limited user */
$runner->section('Permissions');
$limLogin = $client->request('POST', '/auth/login', [
    'username' => $env['limited_user'],
    'password' => $env['limited_pass'],
    'shop_slug' => $env['shop_slug'],
], false);
$limToken = $limLogin['json']['data']['token'] ?? '';
$runner->assert($limToken !== '', 'limited user login');

$limClient = new ApiTestClient($env['api_base']);
$limClient->setToken($limToken);
$denied = $limClient->request('POST', '/users', [
    'user_name' => 'hacker',
    'password' => 'x',
    'privilege' => 'user',
    'modules' => [],
]);
$runner->assert(
    ($denied['code'] === 403) || (($denied['json']['ok'] ?? true) === false),
    'limited user cannot POST /users'
);

/* Negative */
$runner->section('Negative');
$nf = $client->request('GET', '/does-not-exist-route');
$runner->assert(($nf['code'] === 404) || (($nf['json']['error']['code'] ?? '') === 'not_found'), 'unknown route 404');

require_once __DIR__ . '/flows.php';
run_api_flows($client, $runner, $env);

$logout = $client->request('POST', '/auth/logout');
$runner->assert(($logout['json']['ok'] ?? false) === true, 'POST /auth/logout');

exit($runner->summary());
