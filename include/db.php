<?php
$host = "localhost";
$user_name = "root";
$password = "root";
$name = "stock";

$con = mysqli_connect($host, $user_name, $password, $name);
if ($con === false) {
    error_log('cant connect to database: ' . mysqli_connect_error());
} else {
    // Legacy dump/queries expect relaxed SQL mode (MySQL 8 defaults break GROUP BY / zero dates).
    mysqli_query($con, "SET SESSION sql_mode = 'ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");
}

require_once __DIR__ . '/helpers.php';

?>
