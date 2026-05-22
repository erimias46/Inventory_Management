<?php
/**
 * CLI: scan PHP pages for fatal errors (run from project root).
 * Usage: php tools/scan_pages.php
 */
$base = dirname(__DIR__);
$phpBin = '/Applications/MAMP/bin/php/php8.3.28/bin/php';
$baseUrl = 'http://localhost:8888/stock/';
$cookie = '/tmp/stock_scan_cookies.txt';

$pages = [];
$dirs = [$base . '/pages'];
$rootPages = ['index.php', 'index2.php', 'login.php', 'newbackup.php'];
$skip = ['/vendor/', '/backup/vendor/', '/tools/', '/include/', '/api/', 'action.php'];

foreach ($dirs as $dir) {
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($it as $file) {
        if (!$file->isFile() || $file->getExtension() !== 'php') {
            continue;
        }
        $path = $file->getPathname();
        foreach ($skip as $s) {
            if (str_contains($path, $s)) {
                continue 2;
            }
        }
        $rel = str_replace($base . '/', '', $path);
        if (str_ends_with($path, 'action.php')) {
            continue;
        }
        $pages[] = $rel;
    }
}

foreach ($rootPages as $rel) {
    if (is_file($base . '/' . $rel)) {
        $pages[] = $rel;
    }
}

$pages = array_values(array_unique($pages));

// Login once
@unlink($cookie);
$ch = curl_init($baseUrl . 'login.php');
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => 'username=masteradmin&password=admin',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_COOKIEJAR => $cookie,
    CURLOPT_FOLLOWLOCATION => true,
]);
curl_exec($ch);
curl_close($ch);

$errors = [];
$queryOverrides = [
    'pages/constants/constant.php' => '?id=15',
    'pages/constants/constant2.php' => '?id=15',
    'pages/constants/constant3.php' => '?id=15',
    'pages/account/user3.php' => '?id=25',
    'pages/sale/exchange.php' => '?type=jeans&sales_id=1',
    'pages/sale/edit.php' => '?type=jeans&sales_id=1',
];

foreach ($pages as $rel) {
    $url = $baseUrl . $rel . ($queryOverrides[$rel] ?? '');
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_COOKIEFILE => $cookie,
        CURLOPT_TIMEOUT => 15,
    ]);
    $body = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if (preg_match('/(Fatal error|Parse error|mysqli_sql_exception)/i', $body ?? '')) {
        if (preg_match('/(Fatal error|Parse error|mysqli_sql_exception)[^\n<]*/i', $body, $m)) {
            $errors[$rel] = ['code' => $code, 'msg' => trim($m[0])];
        } else {
            $errors[$rel] = ['code' => $code, 'msg' => 'Error detected'];
        }
    }
}

echo "Scanned " . count($pages) . " pages\n";
echo "Errors: " . count($errors) . "\n";
foreach ($errors as $rel => $e) {
    echo "$rel [{$e['code']}]: {$e['msg']}\n";
}
exit(count($errors) > 0 ? 1 : 0);
