<?php 

include "../../../include/db.php";

$update_id = $_GET['id'];
$from = $_GET['from'];

if(isset($update_id) && isset($from)){


$sql="SELECT * FROM accessory_verify WHERE id = $update_id";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);
$accessory_name = $row['accessory_name'];
$size = $row['size'];
$size_id=$row['size_id'];
$type_id=$row['type_id'];
$image=$row['image'];
$type = $row['type'];
$price = $row['price'];
$quantity = $row['quantity'];

$active = $row['active'];
$error = $row['error'];


if($error=='1'){
    $active='1';
    $insert = "INSERT INTO `accessory`(`accessory_name`, `size`, `type`, `price`, `quantity`, `active`,`size_id`,`type_id`,`image`) VALUES ('$accessory_name','$size','$type','$price','$quantity','$active','$size_id','$type_id','$image')";
    $result_insert = mysqli_query($con, $insert);

    
    if ($result_insert) {
       
        $delete = "DELETE FROM `accessory_verify` WHERE id = $update_id";
        $result_delete = mysqli_query($con, $delete);
        if ($result_delete) {
            echo "<script>window.location = '../action.php?status=success&redirect=verify.php'; </script>";
        } else {
            echo "<script>window.location = '../action.php?status=error&redirect=verify.php'; </script>";
        }
    } else {
        echo "<script>window.location = '../action.php?status=error&redirect=verify.php'; </script>";
    }

}
if($error=='2'){
    $sql="SELECT * FROM accessory WHERE accessory_name = '$accessory_name' AND size = '$size' ";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $quantity = $row['quantity'] + $quantity;
    $update = "UPDATE `accessory` SET `quantity`= '$quantity' WHERE accessory_name = '$accessory_name' AND size = '$size'";
    $result_update = mysqli_query($con, $update);
    if ($result_update) {
        $delete = "DELETE FROM `accessory_verify` WHERE id = $update_id";
        $result_delete = mysqli_query($con, $delete);
        if ($result_delete) {
            echo "<script>window.location = '../action.php?status=success&redirect=verify.php'; </script>";
        } else {
            echo "<script>window.location = '../action.php?status=error&redirect=verify.php'; </script>";
        }
    } else {
        echo "<script>window.location = '../action.php?status=error&redirect=verify.php'; </script>";
    }
}

}
