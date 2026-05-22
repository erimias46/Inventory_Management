<?php

declare(strict_types=1);

final class AuthMiddleware
{
    public static function authenticate(AuthService $auth): array
    {
        $token = self::extractToken();
        if ($token === '') {
            ApiResponse::error('unauthorized', 'Missing or invalid Authorization header', 401);
        }
        $user = $auth->resolveUser($token);
        if (!$user) {
            ApiResponse::error('unauthorized', 'Invalid or expired token', 401);
        }
        return $user;
    }

    public static function extractToken(): string
    {
        $header = $_SERVER['HTTP_AUTHORIZATION']
            ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION']
            ?? '';
        if (preg_match('/Bearer\s+(\S+)/i', $header, $m)) {
            return $m[1];
        }
        if (!empty($_SERVER['HTTP_X_API_TOKEN'])) {
            return trim((string) $_SERVER['HTTP_X_API_TOKEN']);
        }
        $q = $_GET['token'] ?? $_GET['access_token'] ?? '';
        return is_string($q) ? trim($q) : '';
    }
}
