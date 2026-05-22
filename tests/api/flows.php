<?php

declare(strict_types=1);

/**
 * End-to-end API flows: sale lifecycle, stock integrity, validation, admin CRUD.
 */
function run_api_flows(ApiTestClient $client, ApiTestRunner $runner, array $env): void
{
    require_once __DIR__ . '/DbProbe.php';
    $db = new DbProbe();

    $runner->section('Logic / validation');
    $noReason = $client->request('POST', '/sales/multi', [
        'method' => 'delivery',
        'lines' => [['type' => 'jeans', 'name' => 'Test Jean', 'size' => 'M', 'price' => 100, 'cash' => 100, 'bank' => 0, 'quantity' => 1]],
    ]);
    $runner->assert(($noReason['json']['ok'] ?? true) === false, 'delivery without reason rejected');

    $empty = $client->request('POST', '/sales/multi', ['method' => 'shop', 'lines' => []]);
    $runner->assert(($empty['json']['ok'] ?? true) === false, 'multi sale rejects empty lines');

    $badType = $client->request('POST', '/sales/multi', [
        'method' => 'shop',
        'lines' => [['type' => 'not_a_category', 'name' => 'X', 'size' => 'M', 'price' => 1, 'cash' => 1, 'quantity' => 1]],
    ]);
    $runner->assert(($badType['json']['ok'] ?? true) === false, 'multi sale rejects invalid category');

    $db->resetProductQty('jeans', 'Test Jean', 'M', 3);
    $overQty = $client->request('POST', '/sales/multi', [
        'method' => 'shop',
        'lines' => [['type' => 'jeans', 'name' => 'Test Jean', 'size' => 'M', 'price' => 100, 'cash' => 100, 'bank' => 0, 'quantity' => 99]],
    ]);
    $runner->assert(
        ($overQty['json']['ok'] ?? true) === false || (($overQty['json']['data']['success_count'] ?? 1) === 0),
        'multi sale fails when quantity exceeds stock'
    );
    $db->resetProductQty('jeans', 'Test Jean', 'M', 10);

    $deliveryOk = $client->request('POST', '/sales/multi', [
        'method' => 'delivery',
        'reason' => 'Driver Abebe',
        'lines' => [['type' => 'shoes', 'name' => 'Test Shoe', 'size' => '40', 'price' => 200, 'cash' => 200, 'bank' => 0, 'quantity' => 1]],
    ]);
    $runner->assert(($deliveryOk['json']['ok'] ?? false) === true, 'delivery sale succeeds with reason');

    $cashAsShop = $client->request('POST', '/sales/multi', [
        'method' => 'cash',
        'lines' => [['type' => 'jeans', 'name' => 'Test Jean', 'size' => 'M', 'price' => 50, 'cash' => 50, 'bank' => 0, 'quantity' => 1]],
    ]);
    $runner->assert(($cashAsShop['json']['ok'] ?? false) === true, 'method cash maps to shop sale');

    $runner->section('Full sale lifecycle (shoes)');
    $db->resetProductQty('shoes', 'Test Shoe', '40', 5);
    $qtyBefore = $db->productQty('shoes', 'Test Shoe', '40');

    $create = $client->request('POST', '/sales/multi', [
        'method' => 'shop',
        'lines' => [['type' => 'shoes', 'name' => 'Test Shoe', 'size' => '40', 'price' => 200, 'cash' => 200, 'bank' => 0, 'quantity' => 1]],
    ]);
    $runner->assert(($create['json']['ok'] ?? false) === true, 'create shoes sale for lifecycle');
    $qtyAfterSale = $db->productQty('shoes', 'Test Shoe', '40');
    $runner->assert($qtyAfterSale === $qtyBefore - 1, 'stock decreases by 1 after sale', "before=$qtyBefore after=$qtyAfterSale");

    $salesId = 0;
    $list = $client->request('GET', '/sales');
    $items = $list['json']['data']['items'] ?? [];
    foreach ($items as $item) {
        if (($item['source'] ?? '') === 'shoes' && ($item['product_name'] ?? '') === 'Test Shoe') {
            $salesId = (int) ($item['sales_id'] ?? 0);
            break;
        }
    }
    $runner->assert($salesId > 0, 'find shoes sale in list');

    $detail = $client->request('GET', "/sales/shoes/$salesId");
    $runner->assert(($detail['json']['ok'] ?? false) === true, 'GET sale detail');
    $runner->assert(($detail['json']['data']['status'] ?? '') === 'active', 'sale status active');

    $update = $client->request('PUT', "/sales/shoes/$salesId", [
        'price' => 210,
        'cash' => 210,
        'bank' => 0,
        'method' => 'shop',
    ]);
    $runner->assert(($update['json']['ok'] ?? false) === true, 'PUT update sale price');

    $refund = $client->request('POST', "/sales/shoes/$salesId/refund");
    $runner->assert(($refund['json']['ok'] ?? false) === true, 'POST refund sale', $refund['raw'] ?? '');
    $runner->assert($db->saleStatus('shoes', $salesId) === 'refunded', 'sale status refunded after refund');
    $qtyAfterRefund = $db->productQty('shoes', 'Test Shoe', '40');
    $runner->assert($qtyAfterRefund === $qtyBefore, 'stock restored after refund', "expected $qtyBefore got $qtyAfterRefund");

    $runner->section('Exchange flow (jeans)');
    $db->resetProductQty('jeans', 'Test Jean', 'M', 5);
    $db->resetProductQty('jeans', 'Test Jean', 'L', 5);
    $jeanSale = $client->request('POST', '/sales/multi', [
        'method' => 'shop',
        'lines' => [['type' => 'jeans', 'name' => 'Test Jean', 'size' => 'M', 'price' => 100, 'cash' => 100, 'bank' => 0, 'quantity' => 1]],
    ]);
    $runner->assert(($jeanSale['json']['ok'] ?? false) === true, 'create jeans sale for exchange');

    $jeanId = 0;
    $list2 = $client->request('GET', '/sales');
    foreach ($list2['json']['data']['items'] ?? [] as $item) {
        if (($item['source'] ?? '') === 'jeans' && ($item['size'] ?? '') === 'M' && ($item['product_name'] ?? '') === 'Test Jean') {
            $jeanId = (int) ($item['sales_id'] ?? 0);
            break;
        }
    }
    $runner->assert($jeanId > 0, 'find active jeans M sale');

    $ex = $client->request('POST', "/sales/jeans/$jeanId/exchange", [
        'name' => 'Test Jean',
        'size' => 'L',
        'price' => 110,
        'cash' => 110,
        'bank' => 0,
        'method' => 'shop',
        'quantity' => 1,
    ]);
    $runner->assert(($ex['json']['ok'] ?? false) === true, 'POST exchange jeans M→L', $ex['raw'] ?? '');

    $runner->section('Delete sale');
    $db->resetProductQty('shoes', 'Test Shoe', '40', 3);
    $delSale = $client->request('POST', '/sales/multi', [
        'method' => 'shop',
        'lines' => [['type' => 'shoes', 'name' => 'Test Shoe', 'size' => '40', 'price' => 1, 'cash' => 1, 'bank' => 0, 'quantity' => 1]],
    ]);
    $delId = 0;
    $list3 = $client->request('GET', '/sales');
    foreach ($list3['json']['data']['items'] ?? [] as $item) {
        if (($item['source'] ?? '') === 'shoes' && (float) ($item['price'] ?? 0) === 1.0) {
            $delId = (int) ($item['sales_id'] ?? 0);
            break;
        }
    }
    if ($delId > 0) {
        $del = $client->request('DELETE', "/sales/shoes/$delId");
        $runner->assert(($del['json']['ok'] ?? false) === true, 'DELETE sale');
        $runner->assert($db->saleStatus('shoes', $delId) === null, 'sale row removed');
    } else {
        $runner->assert(false, 'find sale to delete');
    }

    $runner->section('Inventory CRUD');
    $invList = $client->request('GET', '/inventory/jeans');
    $runner->assert(($invList['json']['ok'] ?? false) === true, 'GET inventory before create');

    $invCreate = $client->request('POST', '/inventory/jeans', [
        'name' => 'API Test Product',
        'size' => 'XL',
        'size_id' => 1,
        'type_id' => 1,
        'type' => 'Standard',
        'price' => 99,
        'quantity' => 2,
    ]);
    $runner->assert(($invCreate['json']['ok'] ?? false) === true, 'POST inventory create', $invCreate['raw'] ?? '');
    $newId = (int) ($invCreate['json']['data']['id'] ?? 0);
    if ($newId > 0) {
        $invGet = $client->request('GET', "/inventory/jeans/$newId");
        $runner->assert(($invGet['json']['ok'] ?? false) === true, 'GET inventory item');
        $invPut = $client->request('PUT', "/inventory/jeans/$newId", ['price' => 105, 'quantity' => 3]);
        $runner->assert(($invPut['json']['ok'] ?? false) === true, 'PUT inventory update');
        $invDel = $client->request('DELETE', "/inventory/jeans/$newId");
        $runner->assert(($invDel['json']['ok'] ?? false) === true, 'DELETE inventory item');
    }

    $runner->section('User CRUD round-trip');
    $uname = 'apitest_' . time();
    $createU = $client->request('POST', '/users', [
        'user_name' => $uname,
        'password' => 'temp123',
        'privilege' => 'user',
        'modules' => ['viewjeans' => 1],
    ]);
    $runner->assert(($createU['json']['ok'] ?? false) === true, 'POST /users create');
    $uid = (int) ($createU['json']['data']['id'] ?? 0);
    if ($uid > 0) {
        $getU = $client->request('GET', "/users/$uid");
        $runner->assert(($getU['json']['ok'] ?? false) === true, 'GET /users/{id}');
        $putU = $client->request('PUT', "/users/$uid", [
            'user_name' => $uname,
            'password' => 'temp123',
            'privilege' => 'user',
            'modules' => ['viewjeans' => 1, 'viewshoes' => 1],
        ]);
        $runner->assert(($putU['json']['ok'] ?? false) === true, 'PUT /users/{id}');
        $delU = $client->request('DELETE', "/users/$uid");
        $runner->assert(($delU['json']['ok'] ?? false) === true, 'DELETE /users/{id}');
    }

    $runner->section('Dashboard period sanity');
    foreach (['today', '7', '30', '365'] as $period) {
        $ov = $client->request('GET', "/dashboard/overview?period=$period");
        $runner->assert(($ov['json']['ok'] ?? false) === true, "overview period=$period");
        $kpis = $ov['json']['data']['kpis'] ?? [];
        $runner->assert(is_array($kpis) && array_key_exists('earnings', $kpis), "overview kpis period=$period");
    }

    $invalidPeriod = $client->request('GET', '/dashboard/daily-sales?month=13&year=2026');
    $runner->assert(($invalidPeriod['json']['ok'] ?? true) === false, 'daily-sales rejects month 13');

    $db->close();
}
