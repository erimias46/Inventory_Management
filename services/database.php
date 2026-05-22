<?php

require_once __DIR__ . '/../include/db.php';

class DatabaseService
{
    public static function getConnection(): mysqli
    {
        global $con;
        if ($con === false || !($con instanceof mysqli)) {
            throw new RuntimeException('Database connection failed: ' . mysqli_connect_error());
        }
        return $con;
    }
}
