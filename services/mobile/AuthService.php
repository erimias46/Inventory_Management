<?php

declare(strict_types=1);

final class AuthService
{
    private mysqli $con;
    private array $config;

    public function __construct(mysqli $con, array $config)
    {
        $this->con = $con;
        $this->config = $config;
        $this->ensureTokenTable();
    }

    private function ensureTokenTable(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS user_api_token (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            token_hash VARCHAR(64) NOT NULL,
            expires_at DATETIME NOT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_token_hash (token_hash),
            INDEX idx_user_id (user_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        mysqli_query($this->con, $sql);
    }

    public function login(string $username, string $password): ?array
    {
        $stmt = mysqli_prepare($this->con, 'SELECT user_id, user_name, password, previledge, module FROM user WHERE user_name = ? LIMIT 1');
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if (!$row || $row['password'] !== $password) {
            return null;
        }

        $token = bin2hex(random_bytes(32));
        $hash = hash('sha256', $token);
        $expires = date('Y-m-d H:i:s', strtotime('+' . (int) $this->config['token_ttl_days'] . ' days'));

        $stmt = mysqli_prepare($this->con, 'INSERT INTO user_api_token (user_id, token_hash, expires_at) VALUES (?, ?, ?)');
        mysqli_stmt_bind_param($stmt, 'iss', $row['user_id'], $hash, $expires);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        return [
            'token' => $token,
            'user' => $this->formatUser($row),
        ];
    }

    public function resolveUser(string $token): ?array
    {
        $hash = hash('sha256', $token);
        $now = date('Y-m-d H:i:s');
        $stmt = mysqli_prepare(
            $this->con,
            'SELECT u.user_id, u.user_name, u.previledge, u.module
             FROM user_api_token t
             INNER JOIN user u ON u.user_id = t.user_id
             WHERE t.token_hash = ? AND t.expires_at > ?
             LIMIT 1'
        );
        mysqli_stmt_bind_param($stmt, 'ss', $hash, $now);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if (!$row) {
            return null;
        }

        return $this->formatUser($row);
    }

    public function logout(string $token): void
    {
        $hash = hash('sha256', $token);
        $stmt = mysqli_prepare($this->con, 'DELETE FROM user_api_token WHERE token_hash = ?');
        mysqli_stmt_bind_param($stmt, 's', $hash);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    public function formatUser(array $row): array
    {
        $modules = json_decode($row['module'] ?? '{}', true);
        if (!is_array($modules)) {
            $modules = [];
        }

        $normalized = [];
        foreach ($modules as $key => $value) {
            $normalized[$key] = ($value === true || $value === 1 || $value === '1') ? 1 : 0;
        }

        return [
            'id' => (int) $row['user_id'],
            'user_name' => $row['user_name'],
            'privilege' => $row['previledge'],
            'is_master_admin' => $row['user_name'] === 'masteradmin',
            'modules' => $normalized,
        ];
    }

    public function hasModule(array $user, string $key): bool
    {
        if (!empty($user['is_master_admin'])) {
            return true;
        }
        return !empty($user['modules'][$key]);
    }

    public function requireModule(array $user, string $key): void
    {
        if (!$this->hasModule($user, $key)) {
            ApiResponse::error('forbidden', 'You do not have permission for this action', 403);
        }
    }
}
