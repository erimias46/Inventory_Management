<?php

$current_date = date('Y-m-d');
$redirect_link = "../../../";
$side_link = "../../../";



include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php'; 
$sale_id = $_POST['sales_id'];

$complete_name = $_POST['complete_name'];

$sql="SELECT * from complete WHERE complete_name = '$complete_name'";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);

$complete_id = $row['complete_id'];

$size = $_POST['size'];


$sql="SELECT * from completedb WHERE size = '$size'";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);

$size_id = $row['id'];


$price = $_POST['price'];
$cash = $_POST['cash'];
$bank = $_POST['bank'];
$method = $_POST['method'];
$quantity = $_POST['quantity'];
$user_id = $_SESSION['user_id'];
$bank_id = $_POST['bank_id'];
$bank_name = $_POST['bank_name'];

if($bank == 0){
    $bank_name = null; 
    $bank_id = null;
} else {
    $bank_name = $_POST['bank_name'];
    $sql="SELECT * FROM bankdb WHERE bankname = '$bank_name'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $bank_id = $row['id'];
}




$date = date('Y-m-d');


$status = "Delivered";



$add_sales = "INSERT INTO `complete_sales`(`complete_id`, `size_id`, `complete_name`, `size`, `price`, `cash`, `bank`, `method`, `sales_date`, `update_date`, `quantity`, `user_id`,`bank_id`,`bank_name`, `status`) 
                  VALUES ('$complete_id', '$size_id', '$complete_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id','$bank_id','$bank_name', 'active')";
$result_add = mysqli_query($con, $add_sales);

$status = "removed_quantity";

$add_complete_log = "INSERT INTO `complete_sales_log`(`complete_id`, `size_id`, `complete_name`, `size`, `price`, `cash`, `bank`, `method`, `sales_date`, `update_date`, `quantity`, `user_id`, `status`) 
                      VALUES ('$complete_id', '$size_id', '$complete_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id', '$status')";
$result_adds = mysqli_query($con, $add_complete_log);


$sql = "UPDATE complete_delivery SET status = 'Delivered', verifiy = 1 WHERE sales_id = $sale_id";
$result = mysqli_query($con, $sql);



if (!$result_add || !$result_adds || !$result) {
    echo "<script>window.location = 'action.php?status=error&redirect=sale_complete.php'; </script>";
    exit;
}



echo "<script>window.location = 'action.php?status=success&redirect=sale_complete.php'; </script>";

?>

