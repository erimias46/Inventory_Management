<?php

declare(strict_types=1);

final class InventoryController
{
    public function __construct(
        private InventoryService $inventory,
        private AuthService $auth
    ) {}

    private function requireView(array $user, string $type): void
    {
        $key = 'view' . $type;
        if ($type === 'jeans') {
            $key = 'viewjeans';
        } elseif ($type === 'shoes') {
            $key = 'viewshoes';
        }
        if (!$this->auth->hasModule($user, $key) && empty($user['is_master_admin'])) {
            ApiResponse::error('forbidden', 'No inventory permission', 403);
        }
    }

    public function index(array $user, string $type): void
    {
        $this->requireView($user, $type);
        $search = $_GET['q'] ?? null;
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $limit = min(100, (int) ($_GET['per_page'] ?? 50));
        $offset = ($page - 1) * $limit;
        ApiResponse::success($this->inventory->list($type, $search, $limit, $offset));
    }

    public function show(array $user, string $type, int $id): void
    {
        $this->requireView($user, $type);
        $item = $this->inventory->get($type, $id);
        if (!$item) {
            ApiResponse::error('not_found', 'Product not found', 404);
        }
        ApiResponse::success($item);
    }

    public function update(array $user, string $type, int $id): void
    {
        $key = 'edit' . $type;
        if ($type === 'jeans') {
            $key = 'editjeans';
        }
        $this->auth->requireModule($user, $key);
        $body = json_decode(file_get_contents('php://input') ?: '{}', true) ?? [];
        $this->inventory->update($type, $id, $body);
        ApiResponse::success(['updated' => true]);
    }

    public function destroy(array $user, string $type, int $id): void
    {
        $key = 'delete' . $type;
        if ($type === 'jeans') {
            $key = 'deletejeans';
        }
        $this->auth->requireModule($user, $key);
        if (!$this->inventory->delete($type, $id)) {
            ApiResponse::error('not_found', 'Product not found', 404);
        }
        ApiResponse::success(['deleted' => true]);
    }

    public function types(array $user, string $type): void
    {
        $this->requireView($user, $type);
        ApiResponse::success($this->inventory->productTypes($type));
    }

    public function create(array $user, string $type): void
    {
        $key = 'add' . $type;
        if ($type === 'jeans') {
            $key = 'addjeans';
        }
        $this->auth->requireModule($user, $key);
        $body = json_decode(file_get_contents('php://input') ?: '{}', true) ?? [];
        ApiResponse::success($this->inventory->create($type, $body), 201);
    }
}
