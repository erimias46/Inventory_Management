<?php

declare(strict_types=1);

$base = $argv[1] ?? 'http://127.0.0.1:8888/stock/api/v1/index.php';

function request(string $method, string $url, ?string $token = null, ?array $body = null): array
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_HTTPHEADER => array_filter([
            'Content-Type: application/json',
            $token ? "Authorization: Bearer $token" : null,
            $token ? "X-Api-Token: $token" : null,
        ]),
        CURLOPT_POSTFIELDS => $body ? json_encode($body) : null,
        CURLOPT_TIMEOUT => 15,
    ]);
    $raw = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ['code' => $code, 'raw' => $raw, 'json' => json_decode($raw ?: '{}', true)];
}

echo "Base: $base\n\n";

$h = request('GET', "$base/health");
echo ($h['json']['ok'] ?? false) ? "✓ health\n" : "✗ health\n";

$login = request('POST', "$base/auth/login", null, ['username' => 'masteradmin', 'password' => 'admin']);
$token = $login['json']['data']['token'] ?? '';
echo ($token ? "✓ login\n" : "✗ login: {$login['raw']}\n");
if (!$token) {
    exit(1);
}

$tests = [
    ['GET', '/products/types', false],
    ['GET', '/products/search?type=jeans', true],
    ['GET', '/sales', true],
    ['GET', '/banks', true],
    ['GET', '/delivery', true],
    ['GET', '/verify/queue?type=jeans', true],
    ['GET', '/dashboard/summary?period=30', true],
    ['GET', '/inventory/jeans', true],
    ['GET', '/users', true],
    ['GET', '/customers/manage', true],
];

foreach ($tests as [$method, $path, $auth]) {
    $r = request($method, $base . $path, $auth ? $token : null);
    $ok = $r['json']['ok'] ?? false;
    $data = $r['json']['data'] ?? null;
    $count = is_array($data) ? count($data) : (is_array($data['items'] ?? null) ? count($data['items']) : '-');
    $status = $ok ? '✓' : '✗';
    $err = $r['json']['error']['message'] ?? '';
    echo "$status [$r[code]] $path " . ($ok ? "items=$count" : $err) . "\n";
}

echo "\nDone.\n";
