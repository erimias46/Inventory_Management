<?php

declare(strict_types=1);

final class TestDatabase
{
    private static ?mysqli $con = null;

    public static function connection(): mysqli
    {
        if (self::$con instanceof mysqli) {
            return self::$con;
        }
        $env = tests_load_env();
        $con = mysqli_connect($env['mysql_host'], $env['mysql_user'], $env['mysql_pass'], $env['shop_db']);
        if (!$con) {
            throw new RuntimeException('Test DB connect failed: ' . mysqli_connect_error() . ' — run php tests/fixtures/setup_test_shop.php');
        }
        mysqli_set_charset($con, 'utf8mb4');
        self::$con = $con;
        return $con;
    }

    public static function scalar(string $sql): float
    {
        $con = self::connection();
        $result = mysqli_query($con, $sql);
        if (!$result || !($row = mysqli_fetch_assoc($result))) {
            return 0.0;
        }
        return (float) reset($row);
    }
}
