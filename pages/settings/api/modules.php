<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Cache-Control: no-cache, must-revalidate');

$redirect_link = '../../../';
include_once $redirect_link . 'include/db.php';

/* ── Auto-create app_settings table ─── */
mysqli_query($con, "CREATE TABLE IF NOT EXISTS `app_settings` (
    `key`   varchar(100) NOT NULL,
    `value` text         NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

/* ── Load all settings ─────────────── */
$raw = [];
$res = mysqli_query($con, "SELECT `key`, `value` FROM `app_settings`");
if ($res) { while ($r = mysqli_fetch_assoc($res)) { $raw[$r['key']] = $r['value']; } }

/* Defaults — all modules ON by default */
$defaults = [
    'mod_jeans'       => '1',
    'mod_shoes'       => '1',
    'mod_top'         => '1',
    'mod_complete'    => '1',
    'mod_accessory'   => '1',
    'mod_wig'         => '1',
    'mod_cosmetics'   => '1',
    'company_name'    => 'Zuqemens',
    'store_name'      => 'Stock Hub',
    'currency'        => 'ETB',
    'currency_symbol' => 'Br',
    'company_address' => '',
    'company_phone'   => '',
    'company_email'   => '',
];
$s = array_merge($defaults, $raw);

/* ── Build response ────────────────── */
echo json_encode([
    'status'  => 'ok',
    'modules' => [
        'jeans'     => $s['mod_jeans']     === '1',
        'shoes'     => $s['mod_shoes']     === '1',
        'top'       => $s['mod_top']       === '1',
        'complete'  => $s['mod_complete']  === '1',
        'accessory' => $s['mod_accessory'] === '1',
        'wig'       => $s['mod_wig']       === '1',
        'cosmetics' => $s['mod_cosmetics'] === '1',
    ],
    'store' => [
        'name'            => $s['company_name'],
        'branch'          => $s['store_name'],
        'address'         => $s['company_address'],
        'phone'           => $s['company_phone'],
        'email'           => $s['company_email'],
        'currency'        => $s['currency'],
        'currency_symbol' => $s['currency_symbol'],
    ],
    'updated_at' => date('c'),
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
