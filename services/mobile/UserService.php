<?php

declare(strict_types=1);

final class UserService
{
    private mysqli $con;
    private AuthService $auth;

    /** @var list<array{key: string, label: string, group: string}> */
    public const MODULE_DEFINITIONS = [
        ['key' => 'viewjeans', 'label' => 'View Jeans', 'group' => 'View'],
        ['key' => 'viewshoes', 'label' => 'View Shoes', 'group' => 'View'],
        ['key' => 'viewtop', 'label' => 'View Top', 'group' => 'View'],
        ['key' => 'viewcomplete', 'label' => 'View Complete', 'group' => 'View'],
        ['key' => 'viewaccessory', 'label' => 'View Accessory', 'group' => 'View'],
        ['key' => 'viewwig', 'label' => 'View Wig', 'group' => 'View'],
        ['key' => 'viewcosmetics', 'label' => 'View Cosmetics', 'group' => 'View'],
        ['key' => 'addjeans', 'label' => 'Add Jeans', 'group' => 'Add'],
        ['key' => 'addshoes', 'label' => 'Add Shoes', 'group' => 'Add'],
        ['key' => 'addtop', 'label' => 'Add Top', 'group' => 'Add'],
        ['key' => 'addcomplete', 'label' => 'Add Complete', 'group' => 'Add'],
        ['key' => 'addaccessory', 'label' => 'Add Accessory', 'group' => 'Add'],
        ['key' => 'addwig', 'label' => 'Add Wig', 'group' => 'Add'],
        ['key' => 'addcosmetics', 'label' => 'Add Cosmetics', 'group' => 'Add'],
        ['key' => 'salejeans', 'label' => 'Sale Jeans', 'group' => 'Sale'],
        ['key' => 'saleshoes', 'label' => 'Sale Shoes', 'group' => 'Sale'],
        ['key' => 'saletop', 'label' => 'Sale Top', 'group' => 'Sale'],
        ['key' => 'salecomplete', 'label' => 'Sale Complete', 'group' => 'Sale'],
        ['key' => 'saleaccessory', 'label' => 'Sale Accessory', 'group' => 'Sale'],
        ['key' => 'salewig', 'label' => 'Sale Wig', 'group' => 'Sale'],
        ['key' => 'salecosmetics', 'label' => 'Sale Cosmetics', 'group' => 'Sale'],
        ['key' => 'fullsale', 'label' => 'Multi Sale', 'group' => 'Sale'],
        ['key' => 'allsale', 'label' => 'All Sales', 'group' => 'Sale'],
        ['key' => 'searchproduct', 'label' => 'Search Products', 'group' => 'Sale'],
        ['key' => 'deliverysale', 'label' => 'Delivery', 'group' => 'Sale'],
        ['key' => 'verifyproducts', 'label' => 'Verify Products', 'group' => 'Sale'],
        ['key' => 'logsale', 'label' => 'Sale Logs', 'group' => 'Sale'],
        ['key' => 'user', 'label' => 'Manage Users', 'group' => 'Admin'],
        ['key' => 'constant', 'label' => 'Constants', 'group' => 'Admin'],
        ['key' => 'backup', 'label' => 'Backup', 'group' => 'Admin'],
        ['key' => 'email', 'label' => 'Email', 'group' => 'Admin'],
        ['key' => 'custview', 'label' => 'Customers', 'group' => 'Admin'],
    ];

    public function __construct(mysqli $con, AuthService $auth)
    {
        $this->con = $con;
        $this->auth = $auth;
    }

    public function listUsers(): array
    {
        $result = mysqli_query($this->con, 'SELECT user_id, user_name, previledge, module FROM user ORDER BY user_name');
        $items = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = $this->auth->formatUser($row);
        }
        return $items;
    }

    public function getUser(int $id, bool $includePassword = false): ?array
    {
        $row = $this->getUserRow($id);
        if (!$row) {
            return null;
        }
        $user = $this->auth->formatUser($row);
        if ($includePassword) {
            $user['password'] = $row['password'];
        }
        return $user;
    }

    public function createUser(string $userName, string $password, string $privilege, array $modules): int
    {
        $userName = trim($userName);
        if ($userName === '' || $password === '') {
            ApiResponse::error('validation', 'Username and password required', 422);
        }
        $json = json_encode($this->normalizeModules($modules));
        $stmt = mysqli_prepare($this->con, 'INSERT INTO user (user_name, password, previledge, module) VALUES (?, ?, ?, ?)');
        mysqli_stmt_bind_param($stmt, 'ssss', $userName, $password, $privilege, $json);
        mysqli_stmt_execute($stmt);
        $id = (int) mysqli_insert_id($this->con);
        mysqli_stmt_close($stmt);
        return $id;
    }

    public function updateUser(int $id, array $data): bool
    {
        $existing = $this->getUserRow($id);
        if (!$existing) {
            return false;
        }
        if ($existing['user_name'] === 'masteradmin' && ($data['user_name'] ?? $existing['user_name']) !== 'masteradmin') {
            ApiResponse::error('forbidden', 'Cannot rename masteradmin', 403);
        }

        $userName = trim((string) ($data['user_name'] ?? $existing['user_name']));
        $password = (string) ($data['password'] ?? $existing['password']);
        $privilege = (string) ($data['privilege'] ?? $existing['previledge']);
        $modules = isset($data['modules']) ? $this->normalizeModules($data['modules']) : json_decode($existing['module'], true);
        $json = json_encode(is_array($modules) ? $modules : []);

        $stmt = mysqli_prepare($this->con, 'UPDATE user SET user_name = ?, password = ?, previledge = ?, module = ? WHERE user_id = ?');
        mysqli_stmt_bind_param($stmt, 'ssssi', $userName, $password, $privilege, $json, $id);
        mysqli_stmt_execute($stmt);
        $ok = mysqli_stmt_affected_rows($stmt) >= 0;
        mysqli_stmt_close($stmt);
        return $ok;
    }

    public function deleteUser(int $id): bool
    {
        $row = $this->getUserRow($id);
        if (!$row || $row['user_name'] === 'masteradmin') {
            return false;
        }
        $stmt = mysqli_prepare($this->con, 'DELETE FROM user WHERE user_id = ?');
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $ok = mysqli_stmt_affected_rows($stmt) > 0;
        mysqli_stmt_close($stmt);
        return $ok;
    }

    public function updateProfile(int $userId, string $userName, string $password): bool
    {
        $userName = trim($userName);
        if ($userName === '' || $password === '') {
            ApiResponse::error('validation', 'Username and password required', 422);
        }
        $stmt = mysqli_prepare($this->con, 'UPDATE user SET user_name = ?, password = ? WHERE user_id = ?');
        mysqli_stmt_bind_param($stmt, 'ssi', $userName, $password, $userId);
        mysqli_stmt_execute($stmt);
        $ok = mysqli_stmt_affected_rows($stmt) >= 0;
        mysqli_stmt_close($stmt);
        return $ok;
    }

    public function moduleDefinitions(): array
    {
        return self::MODULE_DEFINITIONS;
    }

    private function getUserRow(int $id): ?array
    {
        $stmt = mysqli_prepare($this->con, 'SELECT user_id, user_name, password, previledge, module FROM user WHERE user_id = ? LIMIT 1');
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        mysqli_stmt_close($stmt);
        return $row ?: null;
    }

    private function normalizeModules(array $modules): array
    {
        $out = [];
        foreach (self::MODULE_DEFINITIONS as $def) {
            $key = $def['key'];
            $val = $modules[$key] ?? 0;
            $out[$key] = ($val === true || $val === 1 || $val === '1') ? 1 : 0;
        }
        foreach ($modules as $key => $val) {
            if (!isset($out[$key])) {
                $out[$key] = ($val === true || $val === 1 || $val === '1') ? 1 : 0;
            }
        }
        return $out;
    }

    public function listCustomers(): array
    {
        $result = mysqli_query($this->con, 'SELECT * FROM customer ORDER BY customer_name');
        $items = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = $row;
        }
        return $items;
    }

    public function listCustomerNames(): array
    {
        $result = mysqli_query($this->con, 'SELECT customer_name FROM customer ORDER BY customer_name');
        $names = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $names[] = $row['customer_name'];
        }
        return $names;
    }

    public function listBanks(): array
    {
        if (!stock_table_exists($this->con, 'bankdb')) {
            return [];
        }
        $result = mysqli_query($this->con, 'SELECT MIN(id) AS id, bankname FROM bankdb GROUP BY bankname ORDER BY bankname');
        $items = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = ['id' => (int) $row['id'], 'name' => $row['bankname']];
        }
        return $items;
    }

    public function listConstants(): array
    {
        if (!stock_table_exists($this->con, 'd_constants')) {
            return [];
        }
        $result = mysqli_query($this->con, 'SELECT id, name, db FROM d_constants ORDER BY name');
        $items = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = $row;
        }
        return $items;
    }
}
