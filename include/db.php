<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host      = 'localhost';
$db_user   = 'root';
$db_pass   = 'root';
$db_name   = $_SESSION['shop_db'] ?? 'stock';

$con = mysqli_connect($host, $db_user, $db_pass, $db_name);
if ($con === false) {
    error_log('Cannot connect to database "' . $db_name . '": ' . mysqli_connect_error());
} else {
    mysqli_query($con, "SET SESSION sql_mode = 'ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");
    mysqli_set_charset($con, 'utf8mb4');
}

require_once __DIR__ . '/helpers.php';
