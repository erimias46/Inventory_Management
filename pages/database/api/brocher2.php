<?php


include("../../../include/db.php");



$required_paper_full = $_POST['required_paper_full'];
$lamination_type = $_POST['lamination_type'];
$required_quantity = $_POST['quantity'];

$required_paper_full = ceil($required_paper_full);

$sql4 = "SELECT * FROM `laminationdb` WHERE `lam_id`='$lamination_type'";
$result4 = mysqli_query($con, $sql4);
$row4 = mysqli_fetch_assoc($result4);
$care_lamination = $row4['care_lamination']; // This value will be used as the dynamic threshold
$stock_lam_id = $row4['stock_id'];

$sql3 = "SELECT * FROM `stock` WHERE `stock_id`='$stock_lam_id'";
$result3 = mysqli_query($con, $sql3);
$row3 = mysqli_fetch_assoc($result3);
$stock_id = $row3['stock_id'];
$stock_quantity = $row3['stock_quantity2'];
$ratio = $row3['ratio'];
$catagory = $row3['catagory'];
$total_lamination = $row3['total_lamination'];

if ($catagory == 'lamination') {
    $net_care = $total_lamination - $required_quantity;
    if ($net_care < 0) {
        echo "<script>window.location = 'action.php?type=brocher&status=error&redirect=database.php'; </script>";
        exit();
    }

    $job_number = $new_value;
    $reason = $_POST['description'];

    $insert_log = "INSERT INTO stock_log(user_id, stock_id, status, last_quantity, added_removed, reason, jobnumber) 
                   VALUES ('$user_id', '$stock_id', 'remove_quantity', '$stock_quantity', '$required_quantity', '$reason', '$job_number')";
    $result_log = mysqli_query($con, $insert_log);

    $insert_lam_check = "INSERT INTO check_lamination(lam_id, care_lamination, quantity) 
                         VALUES ('$lamination_type', '$care_lamination', '$required_quantity')";
    $result_check = mysqli_query($con, $insert_lam_check);

    if ($result_log && $result_check) {
        // Get total care lamination from check_lamination
        $sql5 = "SELECT SUM(quantity) as total_care_lamination FROM `check_lamination` WHERE `lam_id`='$lamination_type'";
        $result5 = mysqli_query($con, $sql5);
        $row5 = mysqli_fetch_assoc($result5);
        $total_care_lamination = $row5['total_care_lamination'];

        if ($total_care_lamination >= $care_lamination) {
            $full_stocks_to_remove = floor($total_care_lamination / $care_lamination);
            $remaining_care_lamination = $total_care_lamination % $care_lamination;

            $net_care = $total_lamination - ($full_stocks_to_remove * $care_lamination);

            $stock_update = "UPDATE `stock` SET `total_lamination`='$net_care' WHERE `stock_id` = '$stock_id'";
            $result_update = mysqli_query($con, $stock_update);

            if ($result_update) {
                echo "<script>window.location = 'action.php?status=success&redirect=stock_managment.php'; </script>";
            } else {
                echo "<script>window.location = 'action.php?status=error&redirect=stock_managment.php'; </script>";
            }
        } else {
            echo "<script>window.location = 'action.php?status=success&redirect=stock_managment.php'; </script>";
        }
    } else {
        echo "<script>window.location = 'action.php?status=error&redirect=stock_managment.php'; </script>";
    }
}








$paper_id = $_POST['paper_id'];
$sql = "SELECT * FROM `paper` WHERE `paper_id`='$paper_id'";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);
$stock_id = $row['stock_id'];



$user = $_SESSION['username'];
$sql_0 = "SELECT * FROM user WHERE user_name = '$user'";
$result_0 = mysqli_query($con, $sql_0);
$row_0 = mysqli_fetch_assoc($result_0);
$user_id = $row_0['user_id'];



$sql2 = "SELECT * FROM `stock` WHERE `stock_id`='$stock_id'";
$result2 = mysqli_query($con, $sql2);
$row2 = mysqli_fetch_assoc($result2);
$stock_id = $row2['stock_id'];
$stock_quantity = $row2['stock_quantity2'];
$ratio = $row2['ratio'];


$net_quantity = $stock_quantity - $required_paper_full;
$set_stock_quantity = $net_quantity / $ratio;
if ($net_quantity < 0) {
    echo "<script>window.location = 'action.php?type=brocher&status=error&redirect=database.php'; </script>";
    exit();
}


$job_number = $new_value;
$reason = $_POST['description'];

$insert_log = "INSERT INTO stock_log(user_id, stock_id, status, last_quantity, added_removed, reason, jobnumber) 
                    VALUES ('$user_id', '$stock_id', 'remove_quantity', '$stock_quantity', '$required_paper_full', '$reason', '$job_number')";
$result_log = mysqli_query($con, $insert_log);

if ($result_log) {
    $stock_update = "UPDATE `stock` SET `stock_quantity2`='$net_quantity',`stock_quantity`='$set_stock_quantity' WHERE `stock_id` = '$stock_id'";
    $result_update = mysqli_query($con, $stock_update);
    if ($result_update) {
        echo "<script>window.location = 'action.php?status=success&redirect=stock_managment.php'; </script>";
    } else {
        echo "<script>window.location = 'action.php?status=error&redirect=stock_managment.php'; </script>";
    }
} else {
    echo "<script>window.location = 'action.php?status=error&redirect=stock_managment.php'; </script>";
}
