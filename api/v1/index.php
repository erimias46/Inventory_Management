<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/bootstrap.php';

$apiConfig = require dirname(__DIR__) . '/config.php';

$authService = new AuthService($con, $apiConfig);
$assetBaseUrl = stock_request_app_base_url($apiConfig);
$productService = new ProductService($con, $assetBaseUrl);
$saleService = new SaleService($con);
$exchangeService = new ExchangeService($con);
$inventoryService = new InventoryService($con, $assetBaseUrl);
$dashboardService = new DashboardService($con);
$userService = new UserService($con, $authService);
$deliveryService = new DeliveryService($con);
$opsService = new OpsService($con, stock_project_root());

$router = new ApiRouter(
    new ShopController(),
    new AuthController($authService),
    new ProductController($productService, $authService),
    new SaleController($saleService, $exchangeService, $authService),
    new CustomerController($userService, $authService),
    new DeliveryController($deliveryService, $authService),
    new VerifyController($inventoryService, $authService),
    new DashboardController($dashboardService),
    new InventoryController($inventoryService, $authService),
    new UserController($userService, $authService),
    new OpsController($opsService, $authService),
    $authService
);

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// PATH_INFO: /stock/api/v1/index.php/health (works without mod_rewrite on MAMP)
$path = $_SERVER['PATH_INFO'] ?? '';
if ($path === '' && isset($_GET['route'])) {
    $path = (string) $_GET['route'];
}
if ($path === '') {
    $uri = $_SERVER['REQUEST_URI'] ?? '/';
    $path = parse_url($uri, PHP_URL_PATH) ?? '/';
    $baseMarkers = ['/stock/api/v1/index.php', '/stock/api/v1', '/api/v1/index.php', '/api/v1'];
    foreach ($baseMarkers as $marker) {
        $pos = strpos($path, $marker);
        if ($pos !== false) {
            $path = substr($path, $pos + strlen($marker));
            break;
        }
    }
}

try {
    $router->dispatch($method, $path ?: '/');
} catch (Throwable $e) {
    ApiResponse::error('server_error', $e->getMessage(), 500);
}
