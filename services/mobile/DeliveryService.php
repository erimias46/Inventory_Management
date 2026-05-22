<?php

declare(strict_types=1);

final class DeliveryService
{
    private mysqli $con;

    public function __construct(mysqli $con)
    {
        $this->con = $con;
    }

    private function deliveryTable(string $type): string
    {
        return $type === 'jeans' ? 'delivery' : $type . '_delivery';
    }

    public function listPending(): array
    {
        $types = stock_allowed_product_types();
        $items = [];
        foreach ($types as $type) {
            $table = $this->deliveryTable($type);
            if (!stock_table_exists($this->con, $table)) {
                continue;
            }
            $nameCol = $type . '_name';
            $result = mysqli_query($this->con, "SELECT *, '$type' AS source FROM `$table` WHERE status = 'pending' ORDER BY sales_date DESC LIMIT 50");
            while ($row = mysqli_fetch_assoc($result)) {
                $row['source'] = $type;
                $row['product_name'] = $row[$nameCol] ?? '';
                $row['id'] = $row['sales_id'] ?? $row['id'] ?? null;
                $items[] = $row;
            }
        }
        usort($items, fn ($a, $b) => strcmp($b['sales_date'] ?? '', $a['sales_date'] ?? ''));
        return array_slice($items, 0, 100);
    }

    public function complete(string $type, int $id): bool
    {
        if (!stock_allowed_product_type($type)) {
            return false;
        }
        $table = $this->deliveryTable($type);
        $stmt = mysqli_prepare($this->con, "UPDATE `$table` SET status = 'Delivered' WHERE sales_id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $ok = mysqli_stmt_affected_rows($stmt) > 0;
        mysqli_stmt_close($stmt);
        return $ok;
    }
}
