<?php

declare(strict_types=1);

final class ProductService
{
    private mysqli $con;
    private string $baseUrl;

    public function __construct(mysqli $con, string $baseUrl)
    {
        $this->con = $con;
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    public function categories(): array
    {
        $rows = stock_get_categories($this->con);
        return array_map(fn($r) => [
            'slug'          => $r['slug'],
            'label'         => $r['label'],
            'icon'          => $r['icon'],
            'sort_order'    => (int) $r['sort_order'],
            'default_image' => $r['default_image'] ?? '',
        ], $rows);
    }

    public function types(): array
    {
        $labels = stock_product_type_labels();
        $items = [];
        foreach (stock_allowed_product_types() as $key) {
            if (!stock_table_exists($this->con, $key)) {
                continue;
            }
            $result = mysqli_query($this->con, "SELECT COUNT(*) AS c FROM `$key`");
            $count = 0;
            if ($result && ($row = mysqli_fetch_assoc($result))) {
                $count = (int) $row['c'];
            }
            $items[] = [
                'key' => $key,
                'label' => $labels[$key] ?? ucfirst($key),
                'product_count' => $count,
            ];
        }
        return $items;
    }

    public function names(string $type): array
    {
        $this->assertType($type);
        $nameCol = $type . '_name';
        $sql = "SELECT MIN(id) AS id, `$nameCol` AS name FROM `$type` GROUP BY `$nameCol` ORDER BY name ASC";
        $result = mysqli_query($this->con, $sql);
        $items = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = ['id' => (int) $row['id'], 'name' => $row['name']];
        }
        return $items;
    }

    public function sizes(string $type, string $name): array
    {
        $this->assertType($type);
        $nameCol = $type . '_name';
        $stmt = mysqli_prepare($this->con, "SELECT size, quantity, image FROM `$type` WHERE `$nameCol` = ?");
        mysqli_stmt_bind_param($stmt, 's', $name);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $sizes = [];
        $image = null;
        while ($row = mysqli_fetch_assoc($result)) {
            $sizes[] = [
                'size' => $row['size'],
                'quantity' => (float) $row['quantity'],
            ];
            if ($image === null) {
                $image = stock_absolute_image_url($row['image'], $this->baseUrl, $type);
            }
        }
        mysqli_stmt_close($stmt);
        return ['sizes' => $sizes, 'image' => $image];
    }

    public function price(string $type, string $name, string $size): float
    {
        $this->assertType($type);
        $nameCol = $type . '_name';
        $stmt = mysqli_prepare($this->con, "SELECT price FROM `$type` WHERE `$nameCol` = ? AND size = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, 'ss', $name, $size);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        return $row ? (float) $row['price'] : 0.0;
    }

    public function image(string $type, string $name): ?string
    {
        $this->assertType($type);
        $nameCol = $type . '_name';
        $stmt = mysqli_prepare($this->con, "SELECT image FROM `$type` WHERE `$nameCol` = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, 's', $name);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        return $row ? stock_absolute_image_url($row['image'], $this->baseUrl, $type) : null;
    }

    public function search(string $type, ?string $size, ?string $query): array
    {
        $this->assertType($type);
        $nameCol = $type . '_name';
        $conditions = ['1=1'];
        $params = [];
        $types = '';

        if ($size !== null && $size !== '') {
            $conditions[] = 'size = ?';
            $params[] = $size;
            $types .= 's';
        }
        if ($query !== null && $query !== '') {
            $conditions[] = "`$nameCol` LIKE ?";
            $params[] = '%' . $query . '%';
            $types .= 's';
        }

        $sql = "SELECT id, `$nameCol` AS name, size, price, quantity, image FROM `$type` WHERE " . implode(' AND ', $conditions) . ' ORDER BY name, size LIMIT 100';
        $stmt = mysqli_prepare($this->con, $sql);
        if ($types !== '') {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $items = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = [
                'id' => (int) $row['id'],
                'name' => $row['name'],
                'size' => $row['size'],
                'price' => (float) $row['price'],
                'quantity' => (float) $row['quantity'],
                'image' => stock_absolute_image_url($row['image'], $this->baseUrl, $type),
            ];
        }
        mysqli_stmt_close($stmt);
        return $items;
    }

    /** Search product name across all categories (web search by name). */
    public function searchAll(?string $query, int $limitPerType = 30): array
    {
        $items = [];
        $q = trim((string) $query);
        foreach (stock_allowed_product_types() as $type) {
            if (!stock_table_exists($this->con, $type)) {
                continue;
            }
            $nameCol = $type . '_name';
            if ($q !== '') {
                $stmt = mysqli_prepare(
                    $this->con,
                    "SELECT id, `$nameCol` AS name, size, price, quantity, image FROM `$type`
                     WHERE `$nameCol` LIKE ? AND quantity > 0 ORDER BY name, size LIMIT ?"
                );
                $like = '%' . $q . '%';
                mysqli_stmt_bind_param($stmt, 'si', $like, $limitPerType);
            } else {
                $stmt = mysqli_prepare(
                    $this->con,
                    "SELECT id, `$nameCol` AS name, size, price, quantity, image FROM `$type`
                     WHERE quantity > 0 ORDER BY name, size LIMIT ?"
                );
                mysqli_stmt_bind_param($stmt, 'i', $limitPerType);
            }
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            while ($row = mysqli_fetch_assoc($result)) {
                $items[] = [
                    'source' => $type,
                    'id' => (int) $row['id'],
                    'name' => $row['name'],
                    'size' => $row['size'],
                    'price' => (float) $row['price'],
                    'quantity' => (float) $row['quantity'],
                    'image' => stock_absolute_image_url($row['image'], $this->baseUrl, $type),
                ];
            }
            mysqli_stmt_close($stmt);
        }
        return $items;
    }

    /**
     * Products that have all given sizes in stock (web search_multi.php).
     *
     * @param list<string> $sizes
     */
    public function searchMulti(string $type, array $sizes): array
    {
        $this->assertType($type);
        $sizes = array_values(array_filter(array_map('trim', $sizes)));
        if (count($sizes) === 0) {
            return [];
        }
        $nameCol = $type . '_name';
        $placeholders = implode(',', array_fill(0, count($sizes), '?'));
        $sql = "SELECT `$nameCol` AS name,
                GROUP_CONCAT(CONCAT(size, ' (', quantity, ')') ORDER BY size SEPARATOR ', ') AS size_summary,
                MAX(image) AS image, MIN(price) AS price, SUM(quantity) AS total_qty
                FROM `$type`
                WHERE size IN ($placeholders) AND quantity > 0
                GROUP BY `$nameCol`
                HAVING COUNT(DISTINCT size) = ?";
        $stmt = mysqli_prepare($this->con, $sql);
        $types = str_repeat('s', count($sizes)) . 'i';
        $params = [...$sizes, count($sizes)];
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $items = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = [
                'source' => $type,
                'name' => $row['name'],
                'size_summary' => $row['size_summary'],
                'price' => (float) $row['price'],
                'quantity' => (float) $row['total_qty'],
                'image' => stock_absolute_image_url($row['image'], $this->baseUrl, $type),
            ];
        }
        mysqli_stmt_close($stmt);
        return $items;
    }

    private function assertType(string $type): void
    {
        if (!stock_allowed_product_type($type)) {
            ApiResponse::error('invalid_type', 'Invalid product type', 422);
        }
    }
}
