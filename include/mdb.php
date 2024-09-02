<?php
$host = "localhost";
$user_name = "root";
$password = "";
$name = "management";



$conn = mysqli_connect($host, $user_name, $password, $name);
if ($conn === false) {
    error_log('cant connect to database');
}
