<?php
include("../../../include/db.php");

$client = $_GET['client'];
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($client)) {
    $sql = "SELECT SUM(amount + taxwithholding) as paid_amount FROM bank WHERE client = '$client' AND verified = 1";
    $res = mysqli_query($con, $sql);
    $paid_amount = mysqli_fetch_assoc($res)['paid_amount'] ?? 0;
    echo $paid_amount;
}
?>