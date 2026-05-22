<?php

declare(strict_types=1);

final class ApiResponse
{
    public static function success(mixed $data = null, int $status = 200): void
    {
        http_response_code($status);
        echo json_encode(['ok' => true, 'data' => $data], JSON_UNESCAPED_UNICODE);
        exit;
    }

    public static function error(string $code, string $message, int $status = 400): void
    {
        http_response_code($status);
        echo json_encode([
            'ok' => false,
            'error' => ['code' => $code, 'message' => $message],
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
}
