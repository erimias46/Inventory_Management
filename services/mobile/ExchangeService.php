<?php

declare(strict_types=1);

final class ExchangeService
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

    private function exchangeTable(string $type): string
    {
        return $type === 'jeans' ? 'exchange' : 'exchange_' . $type;
    }

    private function verifyTable(string $type): string
    {
        return $type . '_verify';
    }

    private function nameColumn(string $type): string
    {
        return $type . '_name';
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

    /**
     * Exchange an active sale for a different product/size (mirrors web module exchange APIs).
     *
     * @return array{success: bool, new_sales_id?: int, error?: string, verify?: bool}
     */
    public function exchange(
        int $userId,
        string $type,
        int $salesId,
        string $productName,
        string $size,
        float $price,
        float $cash,
        float $bank,
        string $method,
        ?string $bankName,
        int $quantity = 1
    ): array {
        if (!stock_allowed_product_type($type)) {
            return ['success' => false, 'error' => 'invalid_type'];
        }

        $nameCol = $this->nameColumn($type);
        $salesTable = $this->salesTable($type);
        $date = date('Y-m-d');

        $stmt = mysqli_prepare($this->con, "SELECT * FROM `$salesTable` WHERE sales_id = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, 'i', $salesId);
        mysqli_stmt_execute($stmt);
        $originalSale = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        mysqli_stmt_close($stmt);

        if (!$originalSale || ($originalSale['status'] ?? '') !== 'active') {
            return ['success' => false, 'error' => 'sale_not_active'];
        }

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

        $stmt = mysqli_prepare($this->con, "SELECT * FROM `$type` WHERE `$nameCol` = ? AND size = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, 'ss', $productName, $size);
        mysqli_stmt_execute($stmt);
        $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        mysqli_stmt_close($stmt);

        if (!$row) {
            $verifyId = $this->queueVerifyMissingSize($type, $productName, $size, $price, $quantity);
            return ['success' => false, 'error' => 'product_not_found', 'verify' => $verifyId > 0];
        }

        $productId = (int) $row['id'];
        $sizeId = (int) $row['size_id'];
        $currentQty = (float) $row['quantity'];

        if ($currentQty < $quantity) {
            $verifyId = $this->queueVerifyInsufficient($type, $row, $quantity);
            return ['success' => false, 'error' => 'insufficient_quantity', 'verify' => $verifyId > 0];
        }

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
            mysqli_stmt_close($stmt);
            return ['success' => false, 'error' => 'sale_insert_failed'];
        }
        $newSalesId = (int) mysqli_insert_id($this->con);
        mysqli_stmt_close($stmt);

        $stmt = mysqli_prepare($this->con, "UPDATE `$salesTable` SET status = 'Exchange Sell' WHERE sales_id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $salesId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $logTable = $this->salesLogTable($type);
        $logStatus = 'Exchange Sell';
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

        $newQty = $currentQty - $quantity;
        $stmt = mysqli_prepare($this->con, "UPDATE `$type` SET quantity = ? WHERE id = ? AND size = ?");
        mysqli_stmt_bind_param($stmt, 'dis', $newQty, $productId, $size);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $origProductId = (int) $originalSale[$type . '_id'];
        $origSizeId = (int) $originalSale['size_id'];
        $origQty = (int) $originalSale['quantity'];
        $origName = $originalSale[$nameCol];
        $origSize = $originalSale['size'];
        $origPrice = (float) $originalSale['price'];
        $origCash = (float) $originalSale['cash'];
        $origBank = (float) $originalSale['bank'];
        $origMethod = $originalSale['method'];
        $origDate = $originalSale['sales_date'];

        $stmt = mysqli_prepare($this->con, "SELECT quantity FROM `$type` WHERE id = ? AND size_id = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, 'ii', $origProductId, $origSizeId);
        mysqli_stmt_execute($stmt);
        $origRow = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        mysqli_stmt_close($stmt);

        if ($origRow) {
            $restoredQty = (float) $origRow['quantity'] + $origQty;
            $stmt = mysqli_prepare($this->con, "UPDATE `$type` SET quantity = ? WHERE id = ? AND size_id = ?");
            mysqli_stmt_bind_param($stmt, 'dii', $restoredQty, $origProductId, $origSizeId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        $backStatus = 'Exchange Back';
        $stmt = mysqli_prepare(
            $this->con,
            "INSERT INTO `$logTable` (`{$type}_id`, size_id, `$nameCol`, size, price, cash, bank, method, sales_date, update_date, quantity, user_id, status)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        mysqli_stmt_bind_param(
            $stmt,
            'iissdddsssiis',
            $origProductId,
            $origSizeId,
            $origName,
            $origSize,
            $origPrice,
            $origCash,
            $origBank,
            $origMethod,
            $origDate,
            $origDate,
            $origQty,
            $userId,
            $backStatus
        );
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $exchangeTable = $this->exchangeTable($type);
        if (stock_table_exists($this->con, $exchangeTable)) {
            $stmt = mysqli_prepare($this->con, "INSERT INTO `$exchangeTable` (before_sale_id, after_sale_id) VALUES (?, ?)");
            mysqli_stmt_bind_param($stmt, 'ii', $salesId, $newSalesId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        return ['success' => true, 'new_sales_id' => $newSalesId];
    }

    private function queueVerifyMissingSize(string $type, string $productName, string $size, float $price, int $quantity): int
    {
        $nameCol = $this->nameColumn($type);
        $stmt = mysqli_prepare($this->con, "SELECT * FROM `$type` WHERE `$nameCol` = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, 's', $productName);
        mysqli_stmt_execute($stmt);
        $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        mysqli_stmt_close($stmt);
        if (!$row) {
            return 0;
        }

        $sizeId = $this->lookupSizeId($type, $size);
        return $this->insertVerify($type, $productName, $size, $price, $quantity, $row, $sizeId, '1');
    }

    private function queueVerifyInsufficient(string $type, array $row, int $quantity): int
    {
        $nameCol = $this->nameColumn($type);
        return $this->insertVerify(
            $type,
            $row[$nameCol],
            $row['size'],
            (float) $row['price'],
            $quantity,
            $row,
            (int) $row['size_id'],
            '2'
        );
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

    private function insertVerify(
        string $type,
        string $productName,
        string $size,
        float $price,
        int $quantity,
        array $row,
        int $sizeId,
        string $errorCode
    ): int {
        $nameCol = $this->nameColumn($type);
        $verifyTable = $this->verifyTable($type);
        if (!stock_table_exists($this->con, $verifyTable)) {
            return 0;
        }
        $image = $row['image'] ?? $this->defaultImage($type);
        $typeLabel = $row['type'] ?? '';
        $typeId = (int) ($row['type_id'] ?? 0);
        $stmt = mysqli_prepare(
            $this->con,
            "INSERT INTO `$verifyTable` (`$nameCol`, size, price, quantity, image, type, type_id, size_id, active, error)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, '0', ?)"
        );
        mysqli_stmt_bind_param(
            $stmt,
            'ssddssiis',
            $productName,
            $size,
            $price,
            $quantity,
            $image,
            $typeLabel,
            $typeId,
            $sizeId,
            $errorCode
        );
        mysqli_stmt_execute($stmt);
        $id = (int) mysqli_insert_id($this->con);
        mysqli_stmt_close($stmt);
        return $id;
    }
}
