<?php

declare(strict_types=1);

final class SaleController
{
    public function __construct(
        private SaleService $sales,
        private ExchangeService $exchange,
        private AuthService $auth
    ) {}

    public function multi(array $user): void
    {
        $this->auth->requireModule($user, 'fullsale');
        $body = json_decode(file_get_contents('php://input') ?: '{}', true) ?? [];
        $lines = $body['lines'] ?? [];
        if (!is_array($lines) || count($lines) === 0) {
            ApiResponse::error('validation', 'At least one sale line required', 422);
        }
        $method = $body['method'] ?? 'shop';
        if ($method === 'delivery' && trim((string) ($body['reason'] ?? '')) === '') {
            ApiResponse::error('validation', 'Delivery person / reason is required', 422);
        }
        $result = $this->sales->processMultiSale(
            $user['id'],
            $lines,
            $method,
            $body['reason'] ?? null,
            $body['bank_name'] ?? null
        );
        if (!empty($result['errors']) && ($result['success_count'] ?? 0) === 0) {
            $first = $result['errors'][0]['error'] ?? 'failed';
            $message = $first === 'reason_required'
                ? 'Delivery person / reason is required'
                : 'Checkout failed for all items';
            ApiResponse::error('failed', $message, 400);
        }
        ApiResponse::success($result, 201);
    }

    public function index(array $user): void
    {
        $this->auth->requireModule($user, 'allsale');
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = min(100, max(1, (int) ($_GET['per_page'] ?? 50)));
        ApiResponse::success($this->sales->listSales($page, $perPage));
    }

    public function show(array $user): void
    {
        $type = $_GET['type'] ?? '';
        $id = (int) ($_GET['id'] ?? 0);
        $sale = $this->sales->getSale($type, $id);
        if (!$sale) {
            ApiResponse::error('not_found', 'Sale not found', 404);
        }
        ApiResponse::success($sale);
    }

    public function refund(array $user, string $type, int $id): void
    {
        $this->auth->requireModule($user, 'refundsale' . $type);
        if (!$this->sales->refund($type, $id, $user['id'])) {
            ApiResponse::error('failed', 'Refund failed', 400);
        }
        ApiResponse::success(['refunded' => true]);
    }

    public function destroy(array $user, string $type, int $id): void
    {
        $this->auth->requireModule($user, 'deletesale' . $type);
        if (!$this->sales->deleteSale($type, $id)) {
            ApiResponse::error('not_found', 'Sale not found', 404);
        }
        ApiResponse::success(['deleted' => true]);
    }

    public function update(array $user, string $type, int $id): void
    {
        $this->auth->requireModule($user, 'editsale' . $type);
        $body = json_decode(file_get_contents('php://input') ?: '{}', true) ?? [];
        if (!$this->sales->updateSale($type, $id, $body)) {
            ApiResponse::error('failed', 'Could not update sale', 400);
        }
        ApiResponse::success(['updated' => true]);
    }

    public function logs(array $user): void
    {
        $this->auth->requireModule($user, 'logsale');
        $limit = min(200, (int) ($_GET['limit'] ?? 100));
        ApiResponse::success($this->sales->multiSaleLogs($limit));
    }

    public function exchange(array $user, string $type, int $id): void
    {
        $this->auth->requireModule($user, 'exchangesale' . $type);
        $body = json_decode(file_get_contents('php://input') ?: '{}', true) ?? [];
        $name = trim((string) ($body['name'] ?? ''));
        $size = trim((string) ($body['size'] ?? ''));
        if ($name === '' || $size === '') {
            ApiResponse::error('validation', 'Product name and size required', 422);
        }
        $result = $this->exchange->exchange(
            $user['id'],
            $type,
            $id,
            $name,
            $size,
            (float) ($body['price'] ?? 0),
            (float) ($body['cash'] ?? 0),
            (float) ($body['bank'] ?? 0),
            (string) ($body['method'] ?? 'shop'),
            $body['bank_name'] ?? null,
            (int) ($body['quantity'] ?? 1)
        );
        if (!($result['success'] ?? false)) {
            $err = $result['error'] ?? 'failed';
            $messages = [
                'sale_not_active' => 'Sale is not active or not found',
                'product_not_found' => 'Product not found — sent to verify queue',
                'insufficient_quantity' => 'Not enough stock — sent to verify queue',
            ];
            ApiResponse::error($err, $messages[$err] ?? 'Exchange failed', 400);
        }
        ApiResponse::success($result);
    }
}
