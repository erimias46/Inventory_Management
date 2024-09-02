<?php
include("../include/db.php");
// $con = mysqli_connect("localhost", "root", "", "fgsystemnet_elegant");
// if (!$con) {
//     echo "connection lost";
// }

$id = $_GET['id'];
$data = $_GET['data'];
$current_date=date("Y-m-d");

if ($id) {
    $update_data = "UPDATE `payment` SET `status`='$data',`updated_at`='$current_date' WHERE `payment_id`='$id'"; 
    $result = mysqli_query($con, $update_data);
    if ($result) {
        echo "<script>window.location.href='job_status.php';</script>";
    }
} else {
    echo "<script>window.location.href='status.php';</script>";
}