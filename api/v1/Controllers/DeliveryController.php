<?php

declare(strict_types=1);

final class DeliveryController
{
    public function __construct(
        private DeliveryService $delivery,
        private AuthService $auth
    ) {}

    public function index(array $user): void
    {
        $this->auth->requireModule($user, 'deliverysale');
        ApiResponse::success($this->delivery->listPending());
    }

    public function complete(array $user): void
    {
        $this->auth->requireModule($user, 'deliverysale');
        $body = json_decode(file_get_contents('php://input') ?: '{}', true) ?? [];
        $type = $body['type'] ?? '';
        $id = (int) ($body['id'] ?? 0);
        if (!$this->delivery->complete($type, $id)) {
            ApiResponse::error('failed', 'Could not complete delivery', 400);
        }
        ApiResponse::success(['completed' => true]);
    }
}
