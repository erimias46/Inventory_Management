<?php

declare(strict_types=1);

final class InventoryService
{
    private mysqli $con;
    private string $baseUrl;

    public function __construct(mysqli $con, string $baseUrl)
    {
        $this->con = $con;
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    public function list(string $type, ?string $search = null, int $limit = 100, int $offset = 0): array
    {
        $this->assertType($type);
        $nameCol = $type . '_name';
        $where = '1=1';
        $params = [];
        $types = '';

        if ($search) {
            $where .= " AND (`$nameCol` LIKE ? OR size LIKE ?)";
            $q = '%' . $search . '%';
            $params = [$q, $q];
            $types = 'ss';
        }

        $sql = "SELECT * FROM `$type` WHERE $where ORDER BY `$nameCol`, size LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        $types .= 'ii';

        $stmt = mysqli_prepare($this->con, $sql);
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $items = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = $this->formatRow($type, $row);
        }
        mysqli_stmt_close($stmt);
        return $items;
    }

    public function get(string $type, int $id): ?array
    {
        $this->assertType($type);
        $stmt = mysqli_prepare($this->con, "SELECT * FROM `$type` WHERE id = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        return $row ? $this->formatRow($type, $row) : null;
    }

    public function update(string $type, int $id, array $data): bool
    {
        $this->assertType($type);
        $allowed = ['price', 'quantity', 'buy_price'];
        $sets = [];
        $params = [];
        $types = '';
        foreach ($allowed as $field) {
            if (array_key_exists($field, $data)) {
                $sets[] = "`$field` = ?";
                $params[] = $data[$field];
                $types .= 'd';
            }
        }
        if (empty($sets)) {
            return false;
        }
        $params[] = $id;
        $types .= 'i';
        $sql = "UPDATE `$type` SET " . implode(', ', $sets) . ' WHERE id = ?';
        $stmt = mysqli_prepare($this->con, $sql);
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        mysqli_stmt_execute($stmt);
        $ok = mysqli_stmt_affected_rows($stmt) >= 0;
        mysqli_stmt_close($stmt);
        return $ok;
    }

    public function delete(string $type, int $id): bool
    {
        $this->assertType($type);
        $stmt = mysqli_prepare($this->con, "DELETE FROM `$type` WHERE id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $ok = mysqli_stmt_affected_rows($stmt) > 0;
        mysqli_stmt_close($stmt);
        return $ok;
    }

    /**
     * Add stock (one size line). Merges quantity when name+size already exists (web add_* behavior).
     *
     * @return array{id: int, merged: bool}
     */
    public function create(string $type, array $data): array
    {
        $this->assertType($type);
        $nameCol = $type . '_name';
        $name = trim((string) ($data['name'] ?? ''));
        $size = trim((string) ($data['size'] ?? ''));
        $sizeId = (int) ($data['size_id'] ?? 0);
        $typeId = (int) ($data['type_id'] ?? 0);
        $typeLabel = trim((string) ($data['type'] ?? ''));
        $price = (float) ($data['price'] ?? 0);
        $quantity = (float) ($data['quantity'] ?? 0);
        $image = trim((string) ($data['image'] ?? ''));
        if ($image === '') {
            $image = $this->defaultImage($type);
        }

        if ($name === '' || $size === '' || $quantity <= 0) {
            ApiResponse::error('validation', 'name, size, and quantity are required', 422);
        }

        if ($sizeId <= 0) {
            $sizeId = $this->lookupSizeId($type, $size);
        }

        $stmt = mysqli_prepare(
            $this->con,
            "SELECT id, quantity FROM `$type` WHERE `$nameCol` = ? AND size = ? AND active = '1' LIMIT 1"
        );
        mysqli_stmt_bind_param($stmt, 'ss', $name, $size);
        mysqli_stmt_execute($stmt);
        $existing = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        mysqli_stmt_close($stmt);

        if ($existing) {
            $newQty = (float) $existing['quantity'] + $quantity;
            $id = (int) $existing['id'];
            $stmt = mysqli_prepare($this->con, "UPDATE `$type` SET quantity = ?, price = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, 'ddi', $newQty, $price, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            $this->recordProductIn($type, $name, $size, $typeLabel, $image, $price, $quantity);
            return ['id' => $id, 'merged' => true];
        }

        $stmt = mysqli_prepare(
            $this->con,
            "INSERT INTO `$type` (`$nameCol`, size, size_id, image, price, type_id, type, quantity, active)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, '1')"
        );
        mysqli_stmt_bind_param(
            $stmt,
            'ssisdisd',
            $name,
            $size,
            $sizeId,
            $image,
            $price,
            $typeId,
            $typeLabel,
            $quantity
        );
        mysqli_stmt_execute($stmt);
        $id = (int) mysqli_insert_id($this->con);
        mysqli_stmt_close($stmt);
        $this->recordProductIn($type, $name, $size, $typeLabel, $image, $price, $quantity);

        return ['id' => $id, 'merged' => false];
    }

    private function lookupSizeId(string $type, string $size): int
    {
        $table = match ($type) {
            'jeans' => 'jeansdb',
            'shoes' => 'shoesdb',
            'top' => 'topdb',
            'complete' => 'completedb',
            'accessory' => 'accessorydb',
            'wig' => 'wigdb',
            'cosmetics' => 'cosmeticsdb',
            default => $type . 'db',
        };
        if (!stock_table_exists($this->con, $table)) {
            return 0;
        }
        $stmt = mysqli_prepare($this->con, "SELECT id FROM `$table` WHERE size = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, 's', $size);
        mysqli_stmt_execute($stmt);
        $r = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        mysqli_stmt_close($stmt);
        return (int) ($r['id'] ?? 0);
    }

    private function defaultImage(string $type): string
    {
        return match ($type) {
            'shoes' => 'uploads/defaultshoes.jpg',
            'top' => 'uploads/defaulttop.jpg',
            'complete' => 'uploads/defaultcomplete.jpg',
            'accessory' => 'uploads/defaultaccessory.jpg',
            'wig' => 'uploads/defaultwig.jpg',
            'cosmetics' => 'uploads/defaultcosmetics.jpg',
            default => 'uploads/defaultjeans.jpg',
        };
    }

    private function recordProductIn(
        string $type,
        string $name,
        string $size,
        string $typeLabel,
        string $image,
        float $price,
        float $quantity
    ): void {
        if (!stock_table_exists($this->con, 'products')) {
            return;
        }
        $stmt = mysqli_prepare(
            $this->con,
            'INSERT INTO products (product_name, product_type, size, type, image, price, quantity, source_table)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
        );
        mysqli_stmt_bind_param($stmt, 'sssssdds', $name, $type, $size, $typeLabel, $image, $price, $quantity, $type);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    public function productTypes(string $type): array
    {
        $table = $type . 'db';
        if ($type === 'jeans') {
            $table = 'jeansdb';
        } elseif ($type === 'shoes') {
            $table = 'shoesdb';
        } elseif ($type === 'top') {
            $table = 'topdb';
        } elseif ($type === 'complete') {
            $table = 'completedb';
        } elseif ($type === 'accessory') {
            $table = 'accessorydb';
        } elseif ($type === 'wig') {
            $table = 'wigdb';
        } elseif ($type === 'cosmetics') {
            $table = 'cosmeticsdb';
        }

        if (!stock_table_exists($this->con, $table)) {
            $typeTable = $type . '_type_db';
            if (stock_table_exists($this->con, $typeTable)) {
                $result = mysqli_query($this->con, "SELECT * FROM `$typeTable` LIMIT 200");
                $items = [];
                while ($row = mysqli_fetch_assoc($result)) {
                    $items[] = $row;
                }
                return $items;
            }
            return [];
        }

        $result = mysqli_query($this->con, "SELECT * FROM `$table` LIMIT 200");
        $items = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = $row;
        }
        return $items;
    }

    public function verifyQueue(string $type): array
    {
        $this->assertType($type);
        $table = $type . '_verify';
        if (!stock_table_exists($this->con, $table)) {
            return [];
        }
        $result = mysqli_query($this->con, "SELECT * FROM `$table` ORDER BY id DESC LIMIT 100");
        $items = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = $row;
        }
        return $items;
    }

    public function approveVerify(string $type, int $id): bool
    {
        $this->assertType($type);
        $verifyTable = $type . '_verify';
        $stmt = mysqli_prepare($this->con, "SELECT * FROM `$verifyTable` WHERE id = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        if (!$row) {
            return false;
        }

        $nameCol = $type . '_name';
        $error = (string) ($row['error'] ?? '0');

        if ($error === '1') {
            $stmt = mysqli_prepare(
                $this->con,
                "INSERT INTO `$type` (`$nameCol`, size, type, price, quantity, active, size_id, type_id, image)
                 VALUES (?, ?, ?, ?, ?, '1', ?, ?, ?)"
            );
            mysqli_stmt_bind_param(
                $stmt,
                'sssddiis',
                $row[$nameCol],
                $row['size'],
                $row['type'],
                $row['price'],
                $row['quantity'],
                $row['size_id'],
                $row['type_id'],
                $row['image']
            );
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        } elseif ($error === '2') {
            $stmt = mysqli_prepare($this->con, "UPDATE `$type` SET quantity = quantity + ? WHERE `$nameCol` = ? AND size = ?");
            mysqli_stmt_bind_param($stmt, 'dss', $row['quantity'], $row[$nameCol], $row['size']);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        $stmt = mysqli_prepare($this->con, "DELETE FROM `$verifyTable` WHERE id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return true;
    }

    private function formatRow(string $type, array $row): array
    {
        $nameCol = $type . '_name';
        $image = stock_absolute_image_url($row['image'] ?? null, $this->baseUrl, $type);
        return [
            'id' => (int) $row['id'],
            'name' => $row[$nameCol] ?? '',
            'size' => $row['size'] ?? '',
            'price' => (float) ($row['price'] ?? 0),
            'buy_price' => (float) ($row['buy_price'] ?? 0),
            'quantity' => (float) ($row['quantity'] ?? 0),
            'type' => $row['type'] ?? '',
            'image' => $image,
        ];
    }

    private function assertType(string $type): void
    {
        if (!stock_allowed_product_type($type)) {
            ApiResponse::error('invalid_type', 'Invalid product type', 422);
        }
    }
}
