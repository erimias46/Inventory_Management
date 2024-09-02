<?php 
    include '../../include/db.php'; 
    $current_date = date('Y-m-d');


    $id = $_GET['id'];
    $key = $_GET['key'];
    $from = $_GET['from'];
    error_log("$id $key $from");



    if (isset($key) && isset($from)) {
        // Get primary key column name of the specified table
        $sql = "SHOW KEYS FROM $from WHERE Key_name = 'PRIMARY'";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);
        $primary_key = $row['Column_name'];

        $remove = "DELETE FROM $from WHERE $primary_key ='$key'";
        error_log($remove);
        $remove_res = mysqli_query($con, $remove);

        if ($remove_res) {
            echo "<script>window.location.href='constant.php?id=$id&status=success';</script>";
        } else {
            echo "<script>window.location.href='constant.php?id=$id&status=error';</script>";
        }
    } else {
        echo "<script>window.location.href='../../index.php';</script>";
    }



?>