<?php

declare(strict_types=1);

final class VerifyController
{
    public function __construct(
        private InventoryService $inventory,
        private AuthService $auth
    ) {}

    public function queue(array $user): void
    {
        $type = $_GET['type'] ?? '';
        $key = 'verify' . $type;
        if (!$this->auth->hasModule($user, $key) && !$this->auth->hasModule($user, 'verifyproducts')) {
            ApiResponse::error('forbidden', 'No verify permission', 403);
        }
        ApiResponse::success($this->inventory->verifyQueue($type));
    }

    public function approve(array $user, int $id): void
    {
        $body = json_decode(file_get_contents('php://input') ?: '{}', true) ?? [];
        $type = $body['type'] ?? ($_GET['type'] ?? '');
        if ($id <= 0) {
            $id = (int) ($body['id'] ?? 0);
        }
        if (!$this->inventory->approveVerify($type, $id)) {
            ApiResponse::error('failed', 'Approve failed', 400);
        }
        ApiResponse::success(['approved' => true]);
    }
}
