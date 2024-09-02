<?php
include '../../include/db.php';
$current_date = date('Y-m-d');


$id = $_GET['id'];

$from = $_GET['from'];



echo $id;
echo $from;



if ( isset($from)) {
    // Get primary key column name of the specified table
    

    $remove = "DELETE from $from";

    $remove_res = mysqli_query($con, $remove);

    if ($remove_res) {
        echo "<script>window.location.href='constant.php?id=$id&status=success';</script>";
    } else {
        echo "<script>window.location.href='constant.php?id=$id&status=error';</script>";
    }
} else {
    echo "<script>window.location.href='../../index.php';</script>";
}