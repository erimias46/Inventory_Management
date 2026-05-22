<?php

declare(strict_types=1);

final class OpsController
{
    public function __construct(
        private OpsService $ops,
        private AuthService $auth
    ) {}

    public function saleItemLog(array $user): void
    {
        $this->auth->requireModule($user, 'logsale');
        ApiResponse::success($this->ops->unifiedSaleItemLog());
    }

    public function productsInLog(array $user): void
    {
        ApiResponse::success($this->ops->productsInLog());
    }

    public function allProductTypes(array $user): void
    {
        ApiResponse::success($this->ops->allProductTypesSummary());
    }

    public function refundLogs(array $user): void
    {
        $type = $_GET['type'] ?? '';
        $this->auth->requireModule($user, 'refundsale' . $type);
        ApiResponse::success($this->ops->refundLogs($type));
    }

    public function exchangeLogs(array $user): void
    {
        $type = $_GET['type'] ?? '';
        $this->auth->requireModule($user, 'exchangesale' . $type);
        ApiResponse::success($this->ops->exchangeLogs($type));
    }

    public function stockLogs(array $user): void
    {
        $type = $_GET['type'] ?? '';
        $key = 'view' . ($type === 'jeans' ? 'jeans' : $type);
        if ($type === 'shoes') {
            $key = 'viewshoes';
        }
        if (!$this->auth->hasModule($user, $key) && empty($user['is_master_admin'])) {
            ApiResponse::error('forbidden', 'No permission', 403);
        }
        ApiResponse::success($this->ops->stockLogs(
            $type,
            $_GET['from'] ?? null,
            $_GET['to'] ?? null,
            $_GET['product'] ?? null
        ));
    }

    public function constantConfigs(array $user): void
    {
        $this->auth->requireModule($user, 'constant');
        ApiResponse::success($this->ops->listConstantConfigs());
    }

    public function constantRows(array $user, int $configId): void
    {
        $this->auth->requireModule($user, 'constant');
        ApiResponse::success($this->ops->listConstantRows($configId));
    }

    public function addConstantRow(array $user, int $configId): void
    {
        $this->auth->requireModule($user, 'constant');
        $body = json_decode(file_get_contents('php://input') ?: '{}', true) ?? [];
        if (!$this->ops->addConstantRow($configId, $body['row'] ?? $body)) {
            ApiResponse::error('failed', 'Could not add row', 400);
        }
        ApiResponse::success(['added' => true], 201);
    }

    public function deleteConstantRow(array $user, int $configId): void
    {
        $this->auth->requireModule($user, 'constant');
        $body = json_decode(file_get_contents('php://input') ?: '{}', true) ?? [];
        $pk = (string) ($body['primary_key'] ?? $_GET['primary_key'] ?? 'id');
        $id = $body['id'] ?? $_GET['id'] ?? null;
        if ($id === null || !$this->ops->deleteConstantRow($configId, $pk, $id)) {
            ApiResponse::error('failed', 'Could not delete row', 400);
        }
        ApiResponse::success(['deleted' => true]);
    }

    public function settingsGet(array $user): void
    {
        if (!$user['is_master_admin']) {
            ApiResponse::error('forbidden', 'Master admin only', 403);
        }
        ApiResponse::success($this->ops->getSettings());
    }

    public function settingsPut(array $user): void
    {
        if (!$user['is_master_admin']) {
            ApiResponse::error('forbidden', 'Master admin only', 403);
        }
        $body = json_decode(file_get_contents('php://input') ?: '{}', true) ?? [];
        $this->ops->updateSettings($body);
        ApiResponse::success($this->ops->getSettings());
    }

    public function backupsList(array $user): void
    {
        $this->auth->requireModule($user, 'backup');
        ApiResponse::success($this->ops->listBackups());
    }

    public function backupCreate(array $user): void
    {
        $this->auth->requireModule($user, 'backup');
        ApiResponse::success($this->ops->createBackup(), 201);
    }

    public function exportData(array $user): void
    {
        $table = $_GET['table'] ?? '';
        if ($table === '') {
            ApiResponse::error('validation', 'table required', 422);
        }
        ApiResponse::success($this->ops->exportTable(
            $table,
            $_GET['from'] ?? null,
            $_GET['to'] ?? null
        ));
    }

    public function emails(array $user): void
    {
        $this->auth->requireModule($user, 'email');
        ApiResponse::success($this->ops->listEmailSubscribers());
    }

    public function addEmail(array $user): void
    {
        $this->auth->requireModule($user, 'email');
        $body = json_decode(file_get_contents('php://input') ?: '{}', true) ?? [];
        $email = trim((string) ($body['email'] ?? ''));
        if (!$this->ops->addEmailSubscriber($email)) {
            ApiResponse::error('failed', 'Invalid or duplicate email', 400);
        }
        ApiResponse::success(['added' => true], 201);
    }

    public function deleteEmail(array $user, int $id): void
    {
        $this->auth->requireModule($user, 'email');
        if (!$this->ops->deleteEmailSubscriber($id)) {
            ApiResponse::error('not_found', 'Subscriber not found', 404);
        }
        ApiResponse::success(['deleted' => true]);
    }

    public function digitalPages(array $user): void
    {
        ApiResponse::success($this->ops->listDigitalPages());
    }
}
