<?php

declare(strict_types=1);

final class SaleService
{
    private mysqli $con;

    public function __construct(mysqli $con)
    {
        $this->con = $con;
    }

    private function salesTable(string $type): string
    {
        return $type === 'jeans' ? 'sales' : $type . '_sales';
    }

    private function salesLogTable(string $type): string
    {
        return $type === 'jeans' ? 'sales_log' : $type . '_sales_log';
    }

    private function deliveryTable(string $type): string
    {
        return $type === 'jeans' ? 'delivery' : $type . '_delivery';
    }

    private function nameColumn(string $type): string
    {
        return $type . '_name';
    }

    public function processMultiSale(int $userId, array $lines, string $method = 'shop', ?string $reason = null, ?string $bankName = null): array
    {
        $method = $method === 'cash' ? 'shop' : $method;
        if ($method === 'delivery') {
            $reason = trim((string) $reason);
            if ($reason === '') {
                return [
                    'sales_ids' => [],
                    'errors' => [['error' => 'reason_required']],
                    'success_count' => 0,
                    'delivery_count' => 0,
                ];
            }
        }

        $date = date('Y-m-d H:i:s');
        $salesIds = [];
        $errors = [];
        $deliveryCount = 0;
        $bankId = null;

        if ($bankName) {
            $stmt = mysqli_prepare($this->con, 'SELECT id FROM bankdb WHERE bankname = ? LIMIT 1');
            mysqli_stmt_bind_param($stmt, 's', $bankName);
            mysqli_stmt_execute($stmt);
            $r = mysqli_stmt_get_result($stmt);
            $b = mysqli_fetch_assoc($r);
            mysqli_stmt_close($stmt);
            $bankId = $b['id'] ?? null;
        }

        foreach ($lines as $line) {
            $type = $line['type'];
            $productName = $line['name'];
            $size = $line['size'];
            $price = (float) ($line['price'] ?? 0);
            $cash = (float) ($line['cash'] ?? 0);
            $bank = (float) ($line['bank'] ?? 0);
            $quantity = (int) ($line['quantity'] ?? 1);

            if (!stock_allowed_product_type($type)) {
                $errors[] = ['line' => $line, 'error' => 'invalid_type'];
                continue;
            }

            $nameCol = $this->nameColumn($type);
            $stmt = mysqli_prepare($this->con, "SELECT * FROM `$type` WHERE `$nameCol` = ? AND size = ? LIMIT 1");
            mysqli_stmt_bind_param($stmt, 'ss', $productName, $size);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);

            if (!$row) {
                $errors[] = ['line' => $line, 'error' => 'product_not_found'];
                continue;
            }

            $productId = (int) $row['id'];
            $currentQty = (float) $row['quantity'];

            if ($currentQty < $quantity) {
                $errors[] = ['line' => $line, 'error' => 'insufficient_quantity'];
                continue;
            }

            $sizeId = (int) $row['size_id'];
            $bankIdParam = $bankId ?? 0;
            $bankNameParam = $bankName ?? '';

            if ($method === 'delivery') {
                $deliveryTable = $this->deliveryTable($type);
                $status = 'pending';
                $stmt = mysqli_prepare(
                    $this->con,
                    "INSERT INTO `$deliveryTable` (`{$type}_id`, size_id, `$nameCol`, size, price, cash, bank, method, sales_date, update_date, quantity, user_id, bank_id, bank_name, status, reason)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
                );
                mysqli_stmt_bind_param(
                    $stmt,
                    'iissdddsssiissss',
                    $productId,
                    $sizeId,
                    $productName,
                    $size,
                    $price,
                    $cash,
                    $bank,
                    $method,
                    $date,
                    $date,
                    $quantity,
                    $userId,
                    $bankIdParam,
                    $bankNameParam,
                    $status,
                    $reason
                );
                if (!mysqli_stmt_execute($stmt)) {
                    $errors[] = ['line' => $line, 'error' => 'delivery_insert_failed'];
                    mysqli_stmt_close($stmt);
                    continue;
                }
                mysqli_stmt_close($stmt);
                $deliveryCount++;

                $logTable = $this->salesLogTable($type);
                $logStatus = 'Out for Delivery';
                $stmt = mysqli_prepare(
                    $this->con,
                    "INSERT INTO `$logTable` (`{$type}_id`, size_id, `$nameCol`, size, price, cash, bank, method, sales_date, update_date, quantity, user_id, status)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
                );
                mysqli_stmt_bind_param(
                    $stmt,
                    'iissdddsssiis',
                    $productId,
                    $sizeId,
                    $productName,
                    $size,
                    $price,
                    $cash,
                    $bank,
                    $method,
                    $date,
                    $date,
                    $quantity,
                    $userId,
                    $logStatus
                );
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            } else {
                $salesTable = $this->salesTable($type);
                $stmt = mysqli_prepare(
                    $this->con,
                    "INSERT INTO `$salesTable` (`{$type}_id`, size_id, `$nameCol`, size, price, cash, bank, method, sales_date, update_date, quantity, user_id, bank_id, bank_name, status)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active')"
                );
                mysqli_stmt_bind_param(
                    $stmt,
                    'iissdddsssiiss',
                    $productId,
                    $sizeId,
                    $productName,
                    $size,
                    $price,
                    $cash,
                    $bank,
                    $method,
                    $date,
                    $date,
                    $quantity,
                    $userId,
                    $bankIdParam,
                    $bankNameParam
                );
                if (!mysqli_stmt_execute($stmt)) {
                    $errors[] = ['line' => $line, 'error' => 'sale_insert_failed'];
                    mysqli_stmt_close($stmt);
                    continue;
                }
                $salesId = (int) mysqli_insert_id($this->con);
                mysqli_stmt_close($stmt);
                $salesIds[] = ['type' => $type, 'sales_id' => $salesId];

                $logTable = $this->salesLogTable($type);
                $logStatus = 'sold';
                $stmt = mysqli_prepare(
                    $this->con,
                    "INSERT INTO `$logTable` (`{$type}_id`, size_id, `$nameCol`, size, price, cash, bank, method, sales_date, update_date, quantity, user_id, status)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
                );
                mysqli_stmt_bind_param(
                    $stmt,
                    'iissdddsssiis',
                    $productId,
                    $sizeId,
                    $productName,
                    $size,
                    $price,
                    $cash,
                    $bank,
                    $method,
                    $date,
                    $date,
                    $quantity,
                    $userId,
                    $logStatus
                );
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }

            $newQty = $currentQty - $quantity;
            $stmt = mysqli_prepare($this->con, "UPDATE `$type` SET quantity = ? WHERE id = ? AND size = ?");
            mysqli_stmt_bind_param($stmt, 'dis', $newQty, $productId, $size);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        $successCount = count($salesIds) + $deliveryCount;

        return [
            'sales_ids' => $salesIds,
            'errors' => $errors,
            'success_count' => $successCount,
            'delivery_count' => $deliveryCount,
        ];
    }

    public function listSales(int $page = 1, int $perPage = 50, ?string $dateFrom = null, ?string $dateTo = null): array
    {
        $offset = max(0, ($page - 1) * $perPage);
        $types = ['shoes', 'top', 'complete', 'accessory', 'jeans', 'wig', 'cosmetics'];
        $unions = [];
        foreach ($types as $type) {
            $table = $this->salesTable($type);
            $nameCol = $this->nameColumn($type);
            $unions[] = "SELECT '$type' AS source, sales_id, `$nameCol` AS product_name, sales_date, price, size, cash, bank, method, quantity, status
                         FROM `$table` WHERE status = 'active'";
        }
        $inner = implode(' UNION ALL ', $unions);
        $sql = "SELECT * FROM ($inner) AS combined ORDER BY sales_date DESC LIMIT $perPage OFFSET $offset";
        $result = mysqli_query($this->con, $sql);
        if (!$result) {
            return ['items' => [], 'page' => $page, 'per_page' => $perPage];
        }
        $items = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = [
                'source' => $row['source'],
                'sales_id' => (int) $row['sales_id'],
                'product_name' => $row['product_name'],
                'sales_date' => $row['sales_date'],
                'price' => (float) $row['price'],
                'size' => $row['size'],
                'cash' => (float) $row['cash'],
                'bank' => (float) $row['bank'],
                'method' => $row['method'],
                'quantity' => (int) $row['quantity'],
            ];
        }
        return ['items' => $items, 'page' => $page, 'per_page' => $perPage];
    }

    public function updateSale(string $type, int $salesId, array $data): bool
    {
        if (!stock_allowed_product_type($type)) {
            return false;
        }
        $sale = $this->getSale($type, $salesId);
        if (!$sale) {
            return false;
        }

        $price = (float) ($data['price'] ?? $sale['price']);
        $cash = (float) ($data['cash'] ?? $sale['cash']);
        $bank = (float) ($data['bank'] ?? $sale['bank']);
        $method = (string) ($data['method'] ?? $sale['method']);
        $bankName = $data['bank_name'] ?? $sale['bank_name'] ?? null;
        $bankId = null;
        if ($bank > 0 && $bankName) {
            $stmt = mysqli_prepare($this->con, 'SELECT id FROM bankdb WHERE bankname = ? LIMIT 1');
            mysqli_stmt_bind_param($stmt, 's', $bankName);
            mysqli_stmt_execute($stmt);
            $b = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
            mysqli_stmt_close($stmt);
            $bankId = $b['id'] ?? null;
        }
        $bankIdParam = $bankId ?? 0;
        $bankNameParam = $bankName ?? '';

        $salesTable = $this->salesTable($type);
        $stmt = mysqli_prepare(
            $this->con,
            "UPDATE `$salesTable` SET price = ?, cash = ?, bank = ?, method = ?, bank_id = ?, bank_name = ? WHERE sales_id = ?"
        );
        mysqli_stmt_bind_param($stmt, 'dddsssi', $price, $cash, $bank, $method, $bankIdParam, $bankNameParam, $salesId);
        mysqli_stmt_execute($stmt);
        $ok = mysqli_stmt_affected_rows($stmt) >= 0;
        mysqli_stmt_close($stmt);
        return $ok;
    }

    public function getSale(string $type, int $salesId): ?array
    {
        if (!stock_allowed_product_type($type)) {
            return null;
        }
        $table = $this->salesTable($type);
        $stmt = mysqli_prepare($this->con, "SELECT * FROM `$table` WHERE sales_id = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, 'i', $salesId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        if (!$row) {
            return null;
        }
        $row['source'] = $type;
        $row['sales_id'] = (int) $row['sales_id'];
        return $row;
    }

    public function refund(string $type, int $salesId, int $userId): bool
    {
        $sale = $this->getSale($type, $salesId);
        if (!$sale || ($sale['status'] ?? '') !== 'active') {
            return false;
        }

        $nameCol = $this->nameColumn($type);
        $productId = (int) $sale[$type . '_id'];
        $date = date('Y-m-d H:i:s');
        $logTable = $this->salesLogTable($type);

        $stmt = mysqli_prepare(
            $this->con,
            "INSERT INTO `$logTable` (`{$type}_id`, size_id, `$nameCol`, size, price, cash, bank, method, sales_date, update_date, quantity, user_id, status)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Refund')"
        );
        mysqli_stmt_bind_param(
            $stmt,
            'iissdddsssii',
            $productId,
            $sale['size_id'],
            $sale[$nameCol],
            $sale['size'],
            $sale['price'],
            $sale['cash'],
            $sale['bank'],
            $sale['method'],
            $date,
            $date,
            $sale['quantity'],
            $userId
        );
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $stmt = mysqli_prepare($this->con, "UPDATE `$type` SET quantity = quantity + ? WHERE id = ? AND size = ?");
        mysqli_stmt_bind_param($stmt, 'iis', $sale['quantity'], $productId, $sale['size']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $salesTable = $this->salesTable($type);
        $stmt = mysqli_prepare($this->con, "UPDATE `$salesTable` SET status = 'refunded' WHERE sales_id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $salesId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        return true;
    }

    public function deleteSale(string $type, int $salesId): bool
    {
        $sale = $this->getSale($type, $salesId);
        if (!$sale) {
            return false;
        }
        $table = $this->salesTable($type);
        $stmt = mysqli_prepare($this->con, "DELETE FROM `$table` WHERE sales_id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $salesId);
        mysqli_stmt_execute($stmt);
        $ok = mysqli_stmt_affected_rows($stmt) > 0;
        mysqli_stmt_close($stmt);
        return $ok;
    }

    public function multiSaleLogs(int $limit = 100): array
    {
        $sql = 'SELECT * FROM multi_sale ORDER BY created_at DESC LIMIT ?';
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
}
