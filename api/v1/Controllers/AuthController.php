<?php

declare(strict_types=1);

final class AuthController
{
    public function __construct(private AuthService $auth) {}

    public function login(): void
    {
        $body = json_decode(file_get_contents('php://input') ?: '{}', true) ?? [];
        $username  = trim($body['username'] ?? '');
        $password  = $body['password'] ?? '';
        $shopSlug  = trim($body['shop_slug'] ?? '');
        if ($username === '' || $password === '') {
            ApiResponse::error('validation', 'Username and password required', 422);
        }
        $result = $this->auth->login($username, $password, $shopSlug);
        if (!$result) {
            ApiResponse::error('invalid_credentials', 'Invalid username or password', 401);
        }
        ApiResponse::success($result);
    }

    public function me(array $user): void
    {
        ApiResponse::success(['user' => $user]);
    }

    public function logout(): void
    {
        $token = AuthMiddleware::extractToken();
        if ($token !== '') {
            $this->auth->logout($token);
        }
        ApiResponse::success(['message' => 'Logged out']);
    }
}
