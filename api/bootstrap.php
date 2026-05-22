<?php

declare(strict_types=1);

/**
 * Apache/MAMP often strips Authorization before PHP — restore it for Bearer tokens.
 */
(function (): void {
    if (!empty($_SERVER['HTTP_AUTHORIZATION'])) {
        return;
    }
    if (!empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
        $_SERVER['HTTP_AUTHORIZATION'] = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        return;
    }
    if (!empty($_SERVER['Authorization'])) {
        $_SERVER['HTTP_AUTHORIZATION'] = $_SERVER['Authorization'];
        return;
    }
    if (function_exists('apache_request_headers')) {
        $headers = apache_request_headers();
        foreach ($headers as $key => $value) {
            if (strtolower($key) === 'authorization') {
                $_SERVER['HTTP_AUTHORIZATION'] = $value;
                return;
            }
        }
    }
    if (!empty($_SERVER['HTTP_X_API_TOKEN'])) {
        $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer ' . $_SERVER['HTTP_X_API_TOKEN'];
        return;
    }
    $token = $_GET['token'] ?? $_GET['access_token'] ?? null;
    if ($token) {
        $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer ' . $token;
    }
})();

$apiConfig = require __DIR__ . '/config.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: ' . $apiConfig['cors_origin']);
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Api-Token');
header('Access-Control-Max-Age: 86400');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once dirname(__DIR__) . '/include/db.php';

if (!isset($con) || $con === false) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => ['code' => 'db_error', 'message' => 'Database connection failed']]);
    exit;
}

require_once __DIR__ . '/v1/Response.php';
require_once __DIR__ . '/v1/Router.php';
require_once __DIR__ . '/v1/Middleware/AuthMiddleware.php';
require_once dirname(__DIR__) . '/services/mobile/AuthService.php';
require_once dirname(__DIR__) . '/services/mobile/ProductService.php';
require_once dirname(__DIR__) . '/services/mobile/SaleService.php';
require_once dirname(__DIR__) . '/services/mobile/ExchangeService.php';
require_once dirname(__DIR__) . '/services/mobile/OpsService.php';
require_once __DIR__ . '/v1/Controllers/OpsController.php';
require_once dirname(__DIR__) . '/services/mobile/InventoryService.php';
require_once dirname(__DIR__) . '/services/mobile/DashboardService.php';
require_once dirname(__DIR__) . '/services/mobile/UserService.php';
require_once dirname(__DIR__) . '/services/mobile/DeliveryService.php';
require_once __DIR__ . '/v1/Controllers/AuthController.php';
require_once __DIR__ . '/v1/Controllers/ProductController.php';
require_once __DIR__ . '/v1/Controllers/SaleController.php';
require_once __DIR__ . '/v1/Controllers/CustomerController.php';
require_once __DIR__ . '/v1/Controllers/DeliveryController.php';
require_once __DIR__ . '/v1/Controllers/VerifyController.php';
require_once __DIR__ . '/v1/Controllers/DashboardController.php';
require_once __DIR__ . '/v1/Controllers/InventoryController.php';
require_once __DIR__ . '/v1/Controllers/UserController.php';
