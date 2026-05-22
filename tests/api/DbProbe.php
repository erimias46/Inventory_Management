<?php

declare(strict_types=1);

/** Direct MySQL checks for integration flow tests (stock_test only). */
final class DbProbe
{
    private mysqli $con;

    public function __construct()
    {
        $env = tests_load_env();
        $this->con = mysqli_connect($env['mysql_host'], $env['mysql_user'], $env['mysql_pass'], $env['shop_db']);
        if (!$this->con) {
            throw new RuntimeException('DbProbe: ' . mysqli_connect_error());
        }
    }

    public function productQty(string $type, string $name, string $size): float
    {
        $col = $type . '_name';
        $name = mysqli_real_escape_string($this->con, $name);
        $size = mysqli_real_escape_string($this->con, $size);
        $type = mysqli_real_escape_string($this->con, $type);
        $r = mysqli_query($this->con, "SELECT quantity FROM `$type` WHERE `$col`='$name' AND size='$size' LIMIT 1");
        if ($r && ($row = mysqli_fetch_assoc($r))) {
            return (float) $row['quantity'];
        }
        return -1;
    }

    public function saleStatus(string $type, int $salesId): ?string
    {
        $table = $type === 'jeans' ? 'sales' : $type . '_sales';
        $id = (int) $salesId;
        $r = mysqli_query($this->con, "SELECT status FROM `$table` WHERE sales_id=$id LIMIT 1");
        if ($r && ($row = mysqli_fetch_assoc($r))) {
            return $row['status'];
        }
        return null;
    }

    public function resetProductQty(string $type, string $name, string $size, float $qty): void
    {
        $col = $type . '_name';
        $name = mysqli_real_escape_string($this->con, $name);
        $size = mysqli_real_escape_string($this->con, $size);
        $type = mysqli_real_escape_string($this->con, $type);
        mysqli_query($this->con, "UPDATE `$type` SET quantity=$qty WHERE `$col`='$name' AND size='$size' LIMIT 1");
    }

    public function close(): void
    {
        mysqli_close($this->con);
    }
}
