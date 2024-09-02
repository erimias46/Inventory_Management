<?php
include("../../../include/db.php");





$required_paper_a_full = $_POST['required_paper_full_a'];
$required_paper_b_full = $_POST['required_paper_full_b'];
$required_paper_c_full = $_POST['required_paper_full_c'];
$required_paper_d_full = $_POST['required_paper_full_d'];


$required_quantity = $_POST['quantity'];

$lamination_type_a = $_POST['lamination_type_a'];
$lamination_type_b = $_POST['lamination_type_b'];
$lamination_type_c = $_POST['lamination_type_c'];
$lamination_type_d = $_POST['lamination_type_d'];



$required_paper_a_full = ceil($required_paper_a_full);
$required_paper_b_full = ceil($required_paper_b_full);
$required_paper_c_full = ceil($required_paper_c_full);
$required_paper_d_full = ceil($required_paper_d_full);




//lamination A

include("booka.php");



// /// lamination B

include("bookb.php");

include("bookc.php");

include("bookd.php");


















$paper_id_a = $_POST['paper_id_a'];
$paper_id_b = $_POST['paper_id_b'];
$paper_id_c = $_POST['paper_id_c'];
$paper_id_d = $_POST['paper_id_d'];

$sql = "SELECT * FROM `paper` WHERE `paper_id`='$paper_id_a'";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);
$stock_id_a = $row['stock_id'];

$user = "masteradmin";

$sql2 = "SELECT * FROM `stock` WHERE `stock_id`='$stock_id_a'";
$result2 = mysqli_query($con, $sql2);
$row2 = mysqli_fetch_assoc($result2);
$stock_id = $row2['stock_id'];
$stock_quantity = $row2['stock_quantity2'];
$ratio = $row2['ratio'];


$net_quantity = $stock_quantity - $required_paper_a_full;
$set_stock_quantity = $net_quantity / $ratio;
if ($net_quantity < 0) {
    echo "<script>window.location = 'action.php?type=book&status=error&redirect=database.php'; </script>";
    exit();
}


$job_number = $new_value;
$reason = $_POST['description'];

$insert_log = "INSERT INTO stock_log(user_id, stock_id, status, last_quantity, added_removed, reason, jobnumber) 
                    VALUES ('$user_id', '$stock_id', 'remove_quantity', '$stock_quantity', '$required_paper_a_full', '$reason', '$job_number')";
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

$sql = "SELECT * FROM `paper` WHERE `paper_id`='$paper_id_b'";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);
$stock_id_b = $row['stock_id'];

$user = "masteradmin";

$sql2 = "SELECT * FROM `stock` WHERE `stock_id`='$stock_id_b'";
$result2 = mysqli_query($con, $sql2);
$row2 = mysqli_fetch_assoc($result2);
$stock_id = $row2['stock_id'];
$stock_quantity = $row2['stock_quantity2'];
$ratio = $row2['ratio'];

$net_quantity = $stock_quantity - $$required_paper_b_full;
$set_stock_quantity = $net_quantity / $ratio;
if ($net_quantity < 0) {
    echo "<script>window.location = 'action.php?type=book&status=error&redirect=database.php'; </script>";
    exit();
}


$job_number = $new_value;
$reason = $_POST['description'];

$insert_log = "INSERT INTO stock_log(user_id, stock_id, status, last_quantity, added_removed, reason, jobnumber) 
                    VALUES ('$user_id', '$stock_id', 'remove_quantity', '$stock_quantity', '$required_paper_b_full', '$reason', '$job_number')";
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

$sql = "SELECT * FROM `paper` WHERE `paper_id`='$paper_id_c'";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);
$stock_id_c = $row['stock_id'];

$user = "masteradmin";

$sql2 = "SELECT * FROM `stock` WHERE `stock_id`='$stock_id_c'";
$result2 = mysqli_query($con, $sql2);
$row2 = mysqli_fetch_assoc($result2);
$stock_id = $row2['stock_id'];
$stock_quantity = $row2['stock_quantity2'];
$ratio = $row2['ratio'];

$net_quantity = $stock_quantity - $required_paper_c_full;
$set_stock_quantity = $net_quantity / $ratio;
if ($net_quantity < 0) {
    echo "<script>window.location = 'action.php?type=brocher&status=error&redirect=database.php'; </script>";
    exit();
}


$job_number = $new_value;
$reason = $_POST['description'];

$insert_log = "INSERT INTO stock_log(user_id, stock_id, status, last_quantity, added_removed, reason, jobnumber) 
                    VALUES ('$user_id', '$stock_id', 'remove_quantity', '$stock_quantity', '$required_paper_c_full', '$reason', '$job_number')";
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

$sql = "SELECT * FROM `paper` WHERE `paper_id`='$paper_id_d'";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);
$stock_id_d = $row['stock_id'];

$user = "masteradmin";

$sql2 = "SELECT * FROM `stock` WHERE `stock_id`='$stock_id_d'";
$result2 = mysqli_query($con, $sql2);
$row2 = mysqli_fetch_assoc($result2);
$stock_id = $row2['stock_id'];
$stock_quantity = $row2['stock_quantity2'];
$ratio = $row2['ratio'];

$net_quantity = $stock_quantity - $required_paper_d_full;
$set_stock_quantity = $net_quantity / $ratio;
if ($net_quantity < 0) {
    echo "<script>window.location = 'action.php?type=brocher&status=error&redirect=database.php'; </script>";
    exit();
}


$job_number = $new_value;
$reason = $_POST['description'];

$insert_log = "INSERT INTO stock_log(user_id, stock_id, status, last_quantity, added_removed, reason, jobnumber) 
                    VALUES ('$user_id', '$stock_id', 'remove_quantity', '$stock_quantity', '$required_paper_d_full', '$reason', '$job_number')";
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



?>