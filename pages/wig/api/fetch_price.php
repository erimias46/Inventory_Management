<?php
$current_date = date('Y-m-d');
$redirect_link = "../../../";
$side_link = "../../../";



include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php'; // Include your database connection

if (isset($_POST['wig_name']) && isset($_POST['size'])) {
    $wig_name = mysqli_real_escape_string($con, $_POST['wig_name']);
    $size = mysqli_real_escape_string($con, $_POST['size']);

    $query = "SELECT price FROM wig WHERE wig_name = '$wig_name' AND size = '$size'";
    $result = mysqli_query($con, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        echo $row['price'];
    } else {
        echo '0'; // Price not found
    }
}
