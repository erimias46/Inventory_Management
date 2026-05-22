<?php

declare(strict_types=1);

final class OpsService
{
    private mysqli $con;
    private string $projectRoot;

    public function __construct(mysqli $con, string $projectRoot)
    {
        $this->con = $con;
        $this->projectRoot = rtrim($projectRoot, '/');
    }

    private function salesLogTable(string $type): string
    {
        return $type === 'jeans' ? 'sales_log' : $type . '_sales_log';
    }

    private function exchangeTable(string $type): string
    {
        return $type === 'jeans' ? 'exchange' : 'exchange_' . $type;
    }

    public function refundLogs(string $type, int $limit = 100): array
    {
        $this->assertType($type);
        $logTable = $this->salesLogTable($type);
        $nameCol = $type . '_name';
        $sql = "SELECT *, '$type' AS source FROM `$logTable` WHERE status = 'Refund' ORDER BY update_date DESC LIMIT ?";
        $stmt = mysqli_prepare($this->con, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $limit);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $items = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $row['product_name'] = $row[$nameCol] ?? '';
            $items[] = $row;
        }
        mysqli_stmt_close($stmt);
        return $items;
    }

    public function exchangeLogs(string $type, int $limit = 100): array
    {
        $this->assertType($type);
        $exchangeTable = $this->exchangeTable($type);
        if (!stock_table_exists($this->con, $exchangeTable)) {
            return [];
        }
        $sql = "SELECT *, '$type' AS source FROM `$exchangeTable` ORDER BY before_sale_id DESC LIMIT ?";
        $stmt = mysqli_prepare($this->con, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $limit);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $items = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = $row;
        }
        mysqli_stmt_close($stmt);
        return $items;
    }

    public function stockLogs(
        string $type,
        ?string $fromDate = null,
        ?string $toDate = null,
        ?string $productFilter = null,
        int $limit = 200
    ): array {
        $this->assertType($type);
        $logTable = $this->salesLogTable($type);
        $nameCol = $type . '_name';
        $where = ['1=1'];
        $params = [];
        $types = '';

        if ($fromDate) {
            $where[] = 'sales_date >= ?';
            $params[] = $fromDate;
            $types .= 's';
        }
        if ($toDate) {
            $where[] = 'sales_date <= ?';
            $params[] = $toDate . ' 23:59:59';
            $types .= 's';
        }
        if ($productFilter && $productFilter !== 'all') {
            $where[] = "`$nameCol` = ?";
            $params[] = $productFilter;
            $types .= 's';
        }

        $sql = "SELECT *, '$type' AS source FROM `$logTable` WHERE " . implode(' AND ', $where)
            . ' ORDER BY update_date DESC LIMIT ?';
        $params[] = $limit;
        $types .= 'i';

        $stmt = mysqli_prepare($this->con, $sql);
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $items = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $row['product_name'] = $row[$nameCol] ?? '';
            $items[] = $row;
        }
        mysqli_stmt_close($stmt);
        return $items;
    }

    public function listConstantConfigs(): array
    {
        if (!stock_table_exists($this->con, 'd_constants')) {
            return [];
        }
        $result = mysqli_query($this->con, 'SELECT * FROM d_constants ORDER BY name');
        $items = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = [
                'id' => (int) $row['id'],
                'name' => $row['name'],
                'table' => $row['db'],
            ];
        }
        return $items;
    }

    public function listConstantRows(int $configId, int $limit = 500): array
    {
        $config = $this->getConstantConfig($configId);
        if (!$config) {
            return [];
        }
        $table = $config['table'];
        $result = mysqli_query($this->con, "SELECT * FROM `$table` LIMIT $limit");
        $items = [];
        $columns = [];
        if ($result && mysqli_num_rows($result) > 0) {
            $fields = mysqli_fetch_fields($result);
            foreach ($fields as $f) {
                $columns[] = $f->name;
            }
            mysqli_data_seek($result, 0);
            while ($row = mysqli_fetch_assoc($result)) {
                $items[] = $row;
            }
        }
        return ['config' => $config, 'columns' => $columns, 'rows' => $items];
    }

    public function addConstantRow(int $configId, array $data): bool
    {
        $config = $this->getConstantConfig($configId);
        if (!$config || empty($data)) {
            return false;
        }
        $table = $config['table'];
        $cols = [];
        $placeholders = [];
        $values = [];
        $types = '';
        foreach ($data as $col => $val) {
            if (!preg_match('/^[a-zA-Z0-9_]+$/', (string) $col)) {
                continue;
            }
            $cols[] = "`$col`";
            $placeholders[] = '?';
            $values[] = $val;
            $types .= is_int($val) ? 'i' : (is_float($val) ? 'd' : 's');
        }
        if (empty($cols)) {
            return false;
        }
        $sql = 'INSERT INTO `' . $table . '` (' . implode(',', $cols) . ') VALUES (' . implode(',', $placeholders) . ')';
        $stmt = mysqli_prepare($this->con, $sql);
        mysqli_stmt_bind_param($stmt, $types, ...$values);
        mysqli_stmt_execute($stmt);
        $ok = mysqli_stmt_affected_rows($stmt) > 0;
        mysqli_stmt_close($stmt);
        return $ok;
    }

    public function deleteConstantRow(int $configId, string $primaryKey, $id): bool
    {
        $config = $this->getConstantConfig($configId);
        if (!$config || !preg_match('/^[a-zA-Z0-9_]+$/', $primaryKey)) {
            return false;
        }
        $table = $config['table'];
        $stmt = mysqli_prepare($this->con, "DELETE FROM `$table` WHERE `$primaryKey` = ?");
        $type = is_int($id) ? 'i' : 's';
        mysqli_stmt_bind_param($stmt, $type, $id);
        mysqli_stmt_execute($stmt);
        $ok = mysqli_stmt_affected_rows($stmt) > 0;
        mysqli_stmt_close($stmt);
        return $ok;
    }

    public function getSettings(): array
    {
        mysqli_query($this->con, "CREATE TABLE IF NOT EXISTS `app_settings` (
            `key` varchar(100) NOT NULL,
            `value` text NOT NULL,
            PRIMARY KEY (`key`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $raw = [];
        $res = mysqli_query($this->con, 'SELECT `key`, `value` FROM app_settings');
        if ($res) {
            while ($r = mysqli_fetch_assoc($res)) {
                $raw[$r['key']] = $r['value'];
            }
        }
        $defaults = [
            'mod_jeans' => '1', 'mod_shoes' => '1', 'mod_top' => '1', 'mod_complete' => '1',
            'mod_accessory' => '1', 'mod_wig' => '1', 'mod_cosmetics' => '1',
            'company_name' => 'Zuqemens', 'store_name' => 'Stock Hub', 'currency' => 'ETB',
            'currency_symbol' => 'Br', 'company_address' => '', 'company_phone' => '', 'company_email' => '',
        ];
        $s = array_merge($defaults, $raw);
        return [
            'modules' => [
                'jeans' => $s['mod_jeans'] === '1',
                'shoes' => $s['mod_shoes'] === '1',
                'top' => $s['mod_top'] === '1',
                'complete' => $s['mod_complete'] === '1',
                'accessory' => $s['mod_accessory'] === '1',
                'wig' => $s['mod_wig'] === '1',
                'cosmetics' => $s['mod_cosmetics'] === '1',
            ],
            'store' => [
                'name' => $s['company_name'],
                'branch' => $s['store_name'],
                'address' => $s['company_address'],
                'phone' => $s['company_phone'],
                'email' => $s['company_email'],
                'currency' => $s['currency'],
                'currency_symbol' => $s['currency_symbol'],
            ],
        ];
    }

    public function updateSettings(array $body): void
    {
        $map = [];
        if (isset($body['modules']) && is_array($body['modules'])) {
            foreach ($body['modules'] as $key => $on) {
                $map['mod_' . $key] = $on ? '1' : '0';
            }
        }
        if (isset($body['store']) && is_array($body['store'])) {
            $store = $body['store'];
            if (isset($store['name'])) {
                $map['company_name'] = (string) $store['name'];
            }
            if (isset($store['branch'])) {
                $map['store_name'] = (string) $store['branch'];
            }
            if (isset($store['address'])) {
                $map['company_address'] = (string) $store['address'];
            }
            if (isset($store['phone'])) {
                $map['company_phone'] = (string) $store['phone'];
            }
            if (isset($store['email'])) {
                $map['company_email'] = (string) $store['email'];
            }
            if (isset($store['currency'])) {
                $map['currency'] = (string) $store['currency'];
            }
            if (isset($store['currency_symbol'])) {
                $map['currency_symbol'] = (string) $store['currency_symbol'];
            }
        }
        foreach ($map as $key => $value) {
            $stmt = mysqli_prepare(
                $this->con,
                'INSERT INTO app_settings (`key`, `value`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)'
            );
            mysqli_stmt_bind_param($stmt, 'ss', $key, $value);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }

    public function listBackups(): array
    {
        $dir = $this->projectRoot . '/backups';
        if (!is_dir($dir)) {
            return [];
        }
        $files = glob($dir . '/*.sql') ?: [];
        $items = [];
        foreach ($files as $path) {
            $items[] = [
                'filename' => basename($path),
                'size' => filesize($path),
                'modified' => date('Y-m-d H:i:s', filemtime($path)),
            ];
        }
        usort($items, fn ($a, $b) => strcmp($b['modified'], $a['modified']));
        return $items;
    }

    public function createBackup(): array
    {
        $dir = $this->projectRoot . '/backups';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $filename = 'mobile_' . date('Y-m-d_His') . '.sql';
        $path = $dir . '/' . $filename;
        $tables = [];
        $result = mysqli_query($this->con, 'SHOW TABLES');
        while ($row = mysqli_fetch_row($result)) {
            $tables[] = $row[0];
        }
        $output = "-- Yurostock backup " . date('c') . "\n\n";
        foreach ($tables as $table) {
            $output .= "DROP TABLE IF EXISTS `$table`;\n";
            $create = mysqli_query($this->con, "SHOW CREATE TABLE `$table`");
            $createRow = mysqli_fetch_row($create);
            $output .= $createRow[1] . ";\n\n";
            $rows = mysqli_query($this->con, "SELECT * FROM `$table`");
            while ($r = mysqli_fetch_assoc($rows)) {
                $vals = array_map(fn ($v) => $v === null ? 'NULL' : "'" . mysqli_real_escape_string($this->con, (string) $v) . "'", array_values($r));
                $output .= "INSERT INTO `$table` VALUES(" . implode(',', $vals) . ");\n";
            }
            $output .= "\n";
        }
        file_put_contents($path, $output);
        return ['filename' => $filename, 'size' => strlen($output)];
    }

    public function exportTable(string $table, ?string $fromDate = null, ?string $toDate = null, int $limit = 5000): array
    {
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $table) || !stock_table_exists($this->con, $table)) {
            return ['columns' => [], 'rows' => []];
        }
        $where = '1=1';
        $params = [];
        $types = '';
        if ($fromDate && stock_column_exists($this->con, $table, 'sales_date')) {
            $where .= ' AND sales_date >= ?';
            $params[] = $fromDate;
            $types .= 's';
        } elseif ($fromDate && stock_column_exists($this->con, $table, 'date')) {
            $where .= ' AND date >= ?';
            $params[] = $fromDate;
            $types .= 's';
        }
        if ($toDate && stock_column_exists($this->con, $table, 'sales_date')) {
            $where .= ' AND sales_date <= ?';
            $params[] = $toDate . ' 23:59:59';
            $types .= 's';
        } elseif ($toDate && stock_column_exists($this->con, $table, 'date')) {
            $where .= ' AND date <= ?';
            $params[] = $toDate;
            $types .= 's';
        }
        $sql = "SELECT * FROM `$table` WHERE $where LIMIT $limit";
        if ($types !== '') {
            $stmt = mysqli_prepare($this->con, $sql);
            mysqli_stmt_bind_param($stmt, $types, ...$params);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
        } else {
            $result = mysqli_query($this->con, $sql);
        }
        $rows = [];
        $columns = [];
        if ($result && mysqli_num_rows($result) > 0) {
            $fields = mysqli_fetch_fields($result);
            foreach ($fields as $f) {
                $columns[] = $f->name;
            }
            while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = $row;
            }
        }
        return ['table' => $table, 'columns' => $columns, 'rows' => $rows];
    }

    public function listEmailSubscribers(): array
    {
        if (!stock_table_exists($this->con, 'email_subscribers')) {
            return [];
        }
        $result = mysqli_query($this->con, 'SELECT * FROM email_subscribers ORDER BY id DESC LIMIT 500');
        $items = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = $row;
        }
        return $items;
    }

    public function addEmailSubscriber(string $email): bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        $stmt = mysqli_prepare($this->con, 'INSERT INTO email_subscribers (email) VALUES (?)');
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        $ok = mysqli_stmt_affected_rows($stmt) > 0;
        mysqli_stmt_close($stmt);
        return $ok;
    }

    public function deleteEmailSubscriber(int $id): bool
    {
        $stmt = mysqli_prepare($this->con, 'DELETE FROM email_subscribers WHERE id = ?');
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $ok = mysqli_stmt_affected_rows($stmt) > 0;
        mysqli_stmt_close($stmt);
        return $ok;
    }

    public function unifiedSaleItemLog(int $limit = 200): array
    {
        $unions = [];
        foreach (stock_allowed_product_types() as $type) {
            $logTable = $this->salesLogTable($type);
            if (!stock_table_exists($this->con, $logTable)) {
                continue;
            }
            $nameCol = $type . '_name';
            $unions[] = "SELECT '$type' AS source, sales_id, `$nameCol` AS product_name, sales_date, price, size, status,
                         COALESCE(update_date, sales_date) AS log_date
                         FROM `$logTable`";
        }
        if (empty($unions)) {
            return [];
        }
        $sql = '(' . implode(' UNION ALL ', $unions) . ") AS combined ORDER BY log_date DESC LIMIT $limit";
        $result = mysqli_query($this->con, "SELECT * FROM $sql");
        $items = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $status = (string) ($row['status'] ?? '');
            $row['log_direction'] = in_array($status, ['add_quantity', 'Refund', 'Exchange Back', 'DELIVERY CANCELED', 'SELL DELETED'], true)
                ? 'in' : 'out';
            $items[] = $row;
        }
        return $items;
    }

    public function productsInLog(int $limit = 200): array
    {
        if (!stock_table_exists($this->con, 'products')) {
            return [];
        }
        $sql = "SELECT product_name, product_type, source_table,
                GROUP_CONCAT(CONCAT(size, ' (', quantity, ')') ORDER BY size SEPARATOR ', ') AS sizes,
                MAX(type) AS type, MAX(price) AS price, MAX(created_at) AS created_at,
                SUM(quantity) AS total_quantity
                FROM products
                GROUP BY product_name, product_type, source_table
                ORDER BY MAX(created_at) DESC
                LIMIT ?";
        $stmt = mysqli_prepare($this->con, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $limit);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $items = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = $row;
        }
        mysqli_stmt_close($stmt);
        return $items;
    }

    public function allProductTypesSummary(int $limit = 150): array
    {
        $items = [];
        foreach (stock_allowed_product_types() as $type) {
            if (!stock_table_exists($this->con, $type)) {
                continue;
            }
            $nameCol = $type . '_name';
            $salesTable = $type === 'jeans' ? 'sales' : $type . '_sales';
            $sql = "SELECT '$type' AS category, `$nameCol` AS product_name,
                    GROUP_CONCAT(CONCAT(size, ' (', quantity, ')') SEPARATOR ', ') AS sizes,
                    SUM(quantity) AS total_quantity_now, price, image, MAX(created_at) AS created_at
                    FROM `$type` WHERE quantity > 0
                    GROUP BY `$nameCol`, price, image
                    ORDER BY created_at DESC
                    LIMIT 50";
            $result = mysqli_query($this->con, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                $name = $row['product_name'];
                $received = 0;
                if (stock_table_exists($this->con, 'products')) {
                    $stmt = mysqli_prepare($this->con, 'SELECT COALESCE(SUM(quantity), 0) AS t FROM products WHERE product_name = ? AND source_table = ?');
                    mysqli_stmt_bind_param($stmt, 'ss', $name, $type);
                    mysqli_stmt_execute($stmt);
                    $r = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
                    mysqli_stmt_close($stmt);
                    $received = (float) ($r['t'] ?? 0);
                }
                $sold = 0;
                if (stock_table_exists($this->con, $salesTable)) {
                    $stmt = mysqli_prepare(
                        $this->con,
                        "SELECT COUNT(*) AS c FROM `$salesTable` WHERE `$nameCol` = ? AND status IN ('active', 'Exchange Sell')"
                    );
                    mysqli_stmt_bind_param($stmt, 's', $name);
                    mysqli_stmt_execute($stmt);
                    $r = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
                    mysqli_stmt_close($stmt);
                    $sold = (int) ($r['c'] ?? 0);
                }
                $row['total_received'] = $received;
                $row['total_sold'] = $sold;
                $items[] = $row;
            }
        }
        usort($items, fn ($a, $b) => strcmp($b['created_at'] ?? '', $a['created_at'] ?? ''));
        return array_slice($items, 0, $limit);
    }

    public function listDigitalPages(int $limit = 50): array
    {
        if (!stock_table_exists($this->con, 'single_page')) {
            return [];
        }
        $result = mysqli_query($this->con, "SELECT * FROM single_page ORDER BY single_page_id DESC LIMIT $limit");
        $items = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = $row;
        }
        return $items;
    }

    private function getConstantConfig(int $id): ?array
    {
        $stmt = mysqli_prepare($this->con, 'SELECT * FROM d_constants WHERE id = ? LIMIT 1');
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        mysqli_stmt_close($stmt);
        if (!$row) {
            return null;
        }
        return ['id' => (int) $row['id'], 'name' => $row['name'], 'table' => $row['db']];
    }

    private function assertType(string $type): void
    {
        if (!stock_allowed_product_type($type)) {
            ApiResponse::error('invalid_type', 'Invalid product type', 422);
        }
    }
}
