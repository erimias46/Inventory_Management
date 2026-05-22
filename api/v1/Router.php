<?php

declare(strict_types=1);

final class ApiRouter
{
    public function __construct(
        private ShopController $shops,
        private AuthController $auth,
        private ProductController $products,
        private SaleController $sales,
        private CustomerController $customers,
        private DeliveryController $delivery,
        private VerifyController $verify,
        private DashboardController $dashboard,
        private InventoryController $inventory,
        private UserController $users,
        private OpsController $ops,
        private AuthService $authService
    ) {}

    public function dispatch(string $method, string $path): void
    {
        $path = '/' . trim($path, '/');
        $segments = $path === '/' ? [] : explode('/', trim($path, '/'));

        if ($method === 'GET' && $path === '/health') {
            global $con;
            $dbOk = isset($con) && $con !== false && mysqli_query($con, 'SELECT 1') !== false;
            ApiResponse::success([
                'status' => 'ok',
                'version' => '1.0.0',
                'database' => $dbOk ? 'connected' : 'disconnected',
            ]);
        }

        if ($method === 'POST' && $path === '/auth/login') {
            $this->auth->login();
        }

        if ($method === 'GET') {
            $public = [
                '/shops'          => fn () => $this->shops->index(),
                '/customers'      => fn () => $this->customers->names(),
                '/banks'          => fn () => $this->customers->banks(),
                '/products/types' => fn () => $this->products->types(),
                '/products/image' => fn () => $this->products->image(),
            ];
            if (isset($public[$path])) {
                $public[$path]();
            }
        }

        $user = AuthMiddleware::authenticate($this->authService);

        $handlers = [
            'GET:/auth/me' => fn () => $this->auth->me($user),
            'POST:/auth/logout' => fn () => $this->auth->logout(),
            'GET:/products/categories' => fn () => $this->products->categories($user),
            'GET:/products/names' => fn () => $this->products->names($user),
            'GET:/products/sizes' => fn () => $this->products->sizes($user),
            'GET:/products/price' => fn () => $this->products->price($user),
            'GET:/products/search' => fn () => $this->products->search($user),
            'GET:/products/search-all' => fn () => $this->products->searchAll($user),
            'POST:/products/search-multi' => fn () => $this->products->searchMulti($user),
            'GET:/ops/sale-log' => fn () => $this->ops->saleItemLog($user),
            'GET:/ops/products-in' => fn () => $this->ops->productsInLog($user),
            'GET:/ops/all-product-types' => fn () => $this->ops->allProductTypes($user),
            'POST:/users' => fn () => $this->users->create($user),
            'PUT:/users/me/profile' => fn () => $this->users->updateProfile($user),
            'GET:/users/module-keys' => fn () => $this->users->moduleKeys($user),
            'POST:/sales/multi' => fn () => $this->sales->multi($user),
            'GET:/sales' => fn () => $this->sales->index($user),
            'GET:/sales/search' => fn () => $this->products->search($user),
            'GET:/sales/logs' => fn () => $this->sales->logs($user),
            'GET:/delivery' => fn () => $this->delivery->index($user),
            'POST:/delivery/complete' => fn () => $this->delivery->complete($user),
            'GET:/verify/queue' => fn () => $this->verify->queue($user),
            'GET:/dashboard/daily-sales' => fn () => $this->dashboard->dailySales(),
            'GET:/dashboard/summary' => fn () => $this->dashboard->summary(),
            'GET:/dashboard/overview' => fn () => $this->dashboard->overview(),
            'GET:/customers/manage' => fn () => $this->customers->manage($user),
            'GET:/users' => fn () => $this->users->index($user),
            'GET:/constants' => fn () => $this->users->constants($user),
            'GET:/ops/refunds' => fn () => $this->ops->refundLogs($user),
            'GET:/ops/exchanges' => fn () => $this->ops->exchangeLogs($user),
            'GET:/ops/stock-logs' => fn () => $this->ops->stockLogs($user),
            'GET:/ops/constants' => fn () => $this->ops->constantConfigs($user),
            'GET:/ops/settings' => fn () => $this->ops->settingsGet($user),
            'PUT:/ops/settings' => fn () => $this->ops->settingsPut($user),
            'GET:/ops/backups' => fn () => $this->ops->backupsList($user),
            'POST:/ops/backups' => fn () => $this->ops->backupCreate($user),
            'GET:/ops/export' => fn () => $this->ops->exportData($user),
            'GET:/ops/emails' => fn () => $this->ops->emails($user),
            'POST:/ops/emails' => fn () => $this->ops->addEmail($user),
            'GET:/ops/digital-pages' => fn () => $this->ops->digitalPages($user),
        ];

        $key = $method . ':' . $path;
        if (isset($handlers[$key])) {
            $handlers[$key]();
        }

        if ($method === 'GET' && count($segments) === 3 && $segments[0] === 'sales') {
            $_GET['type'] = $segments[1];
            $_GET['id'] = $segments[2];
            $this->sales->show($user);
        }

        if ($method === 'POST' && count($segments) === 4 && $segments[0] === 'sales' && $segments[3] === 'refund') {
            $this->sales->refund($user, $segments[1], (int) $segments[2]);
        }

        if ($method === 'POST' && count($segments) === 4 && $segments[0] === 'sales' && $segments[3] === 'exchange') {
            $this->sales->exchange($user, $segments[1], (int) $segments[2]);
        }

        if ($method === 'PUT' && count($segments) === 3 && $segments[0] === 'sales') {
            $this->sales->update($user, $segments[1], (int) $segments[2]);
        }

        if ($method === 'DELETE' && count($segments) === 3 && $segments[0] === 'sales') {
            $this->sales->destroy($user, $segments[1], (int) $segments[2]);
        }

        if ($method === 'GET' && count($segments) === 3 && $segments[0] === 'ops' && $segments[1] === 'constants') {
            $this->ops->constantRows($user, (int) $segments[2]);
        }

        if ($method === 'POST' && count($segments) === 3 && $segments[0] === 'ops' && $segments[1] === 'constants') {
            $this->ops->addConstantRow($user, (int) $segments[2]);
        }

        if ($method === 'DELETE' && count($segments) === 3 && $segments[0] === 'ops' && $segments[1] === 'constants') {
            $this->ops->deleteConstantRow($user, (int) $segments[2]);
        }

        if ($method === 'DELETE' && count($segments) === 3 && $segments[0] === 'ops' && $segments[1] === 'emails') {
            $this->ops->deleteEmail($user, (int) $segments[2]);
        }

        if ($method === 'POST' && count($segments) === 4 && $segments[0] === 'verify' && $segments[3] === 'approve') {
            $_GET['type'] = $segments[1];
            $this->verify->approve($user, (int) $segments[2]);
        }

        if ($method === 'POST' && count($segments) === 1 && $segments[0] === 'users') {
            $this->users->create($user);
        }

        if ($method === 'PUT' && count($segments) === 2 && $segments[0] === 'users') {
            $this->users->update($user, (int) $segments[1]);
        }

        if ($method === 'DELETE' && count($segments) === 2 && $segments[0] === 'users') {
            $this->users->destroy($user, (int) $segments[1]);
        }

        if ($method === 'GET' && count($segments) === 2 && $segments[0] === 'users') {
            $this->users->show($user, (int) $segments[1]);
        }

        if ($segments[0] === 'inventory' && count($segments) >= 2) {
            $type = $segments[1];
            if ($method === 'POST' && count($segments) === 2) {
                $this->inventory->create($user, $type);
            }
            if ($method === 'GET' && count($segments) === 2) {
                $this->inventory->index($user, $type);
            }
            if ($method === 'GET' && count($segments) === 3 && $segments[2] === 'types') {
                $this->inventory->types($user, $type);
            }
            if ($method === 'GET' && count($segments) === 3 && is_numeric($segments[2])) {
                $this->inventory->show($user, $type, (int) $segments[2]);
            }
            if ($method === 'PUT' && count($segments) === 3 && is_numeric($segments[2])) {
                $this->inventory->update($user, $type, (int) $segments[2]);
            }
            if ($method === 'DELETE' && count($segments) === 3 && is_numeric($segments[2])) {
                $this->inventory->destroy($user, $type, (int) $segments[2]);
            }
        }

        ApiResponse::error('not_found', 'Route not found: ' . $path, 404);
    }
}
