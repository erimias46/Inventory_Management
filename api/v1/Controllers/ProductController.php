<?php

declare(strict_types=1);

final class ProductController
{
    public function __construct(
        private ProductService $products,
        private AuthService $auth
    ) {}

    public function types(): void
    {
        ApiResponse::success($this->products->types());
    }

    public function names(array $user): void
    {
        $type = $_GET['type'] ?? '';
        ApiResponse::success($this->products->names($type));
    }

    public function sizes(array $user): void
    {
        $type = $_GET['type'] ?? '';
        $name = $_GET['name'] ?? '';
        ApiResponse::success($this->products->sizes($type, $name));
    }

    public function price(array $user): void
    {
        $type = $_GET['type'] ?? '';
        $name = $_GET['name'] ?? '';
        $size = $_GET['size'] ?? '';
        ApiResponse::success(['price' => $this->products->price($type, $name, $size)]);
    }

    public function image(): void
    {
        $type = $_GET['type'] ?? '';
        $name = $_GET['name'] ?? '';
        $url = $this->products->image($type, $name);
        if (!$url) {
            ApiResponse::error('not_found', 'Image not found', 404);
        }
        ApiResponse::success(['image' => $url]);
    }

    public function search(array $user): void
    {
        $type = $_GET['type'] ?? '';
        $size = $_GET['size'] ?? null;
        $q = $_GET['q'] ?? null;
        ApiResponse::success($this->products->search($type, $size, $q));
    }

    public function searchAll(array $user): void
    {
        $this->auth->requireModule($user, 'searchproduct');
        ApiResponse::success($this->products->searchAll($_GET['q'] ?? null));
    }

    public function searchMulti(array $user): void
    {
        $this->auth->requireModule($user, 'searchproduct');
        $body = json_decode(file_get_contents('php://input') ?: '{}', true) ?? [];
        $type = (string) ($body['type'] ?? $_GET['type'] ?? '');
        $sizes = $body['sizes'] ?? [];
        if (!is_array($sizes)) {
            $sizes = [];
        }
        ApiResponse::success($this->products->searchMulti($type, $sizes));
    }
}
