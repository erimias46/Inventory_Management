<?php

$current_date = date('Y-m-d');
$redirect_link = "../../../";
$side_link = "../../../";




$sale_id = $_POST['sales_id'];

$accessory_name = $_POST['accessory_name'];

$sql = "SELECT * from accessory WHERE accessory_name = '$accessory_name'";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);

$accessory_id = $row['accessory_id'];

$size = $_POST['size'];


$sql = "SELECT * from accessorydb WHERE size = '$size'";
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

if ($bank == 0) {
    $bank_name = null;
    $bank_id = null;
} else {
    $bank_name = $_POST['bank_name'];
    $sql = "SELECT * FROM bankdb WHERE bankname = '$bank_name'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $bank_id = $row['id'];
}




$date = date('Y-m-d');


$status = "Delivered";



$add_sales = "INSERT INTO `accessory_sales`(`accessory_id`, `size_id`, `accessory_name`, `size`, `price`, `cash`, `bank`, `method`, `sales_date`, `update_date`, `quantity`, `user_id`,`bank_id`,`bank_name`, `status`) 
                  VALUES ('$accessory_id', '$size_id', '$accessory_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id','$bank_id','$bank_name', 'active')";
$result_add = mysqli_query($con, $add_sales);

$status = "removed_quantity";

$add_accessory_log = "INSERT INTO `accessory_sales_log`(`accessory_id`, `size_id`, `accessory_name`, `size`, `price`, `cash`, `bank`, `method`, `sales_date`, `update_date`, `quantity`, `user_id`, `status`) 
                      VALUES ('$accessory_id', '$size_id', '$accessory_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id', '$status')";
$result_adds = mysqli_query($con, $add_accessory_log);


$sql = "UPDATE accessory_delivery SET status = 'Delivered', verifiy = 1 WHERE sales_id = $sale_id";
$result = mysqli_query($con, $sql);



$message = "Delivery Have been verified and delivered to customer\n";


$message .= "accessory Name: $accessory_name\n";
$message .= "Price: $price\n";
$message .= "Size: $size\n";
$message .= "Quantity: $quantity\n";
$message .= "Cash :  $cash\n";
$message .= "Bank : $bank\n";



$subject = "Sold accessory Deliverd to Customer";


sendMessageToSubscribers($message, $con);
sendEmailToSubscribers($message, $subject, $con);



if (!$result_add || !$result_adds || !$result) {
    echo "<script>window.location = 'action.php?status=error&redirect=sale_accessory.php'; </script>";
    exit;
}



echo "<script>window.location = 'action.php?status=success&redirect=sale_accessory.php'; </script>";
