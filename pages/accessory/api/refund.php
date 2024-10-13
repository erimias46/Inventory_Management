<?php



$redirect_link = "../../../";
$side_link = "../../../";



include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php'; // Include your database connection

include_once $redirect_link . 'include/email.php';
include_once $redirect_link . 'include/bot.php';

$update_id = $_GET['id'];
$from = $_GET['from'];

$sql = "SELECT * FROM accessory_sales WHERE sales_id = $update_id";

$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);
$accessory_name = $row['accessory_name'];
$accessory_id = $row['accessory_id'];
$size = $row['size'];
$size_id = $row['size_id'];
$price = $row['price'];
$cash = $row['cash'];
$bank = $row['bank'];
$method = $row['method'];
$quantity = $row['quantity'];
$user_id = $row['user_id'];
$sales_date = $row['sales_date'];
$update_date = $row['update_date'];

$date = date('Y-m-d');


$status = "Refund";




$add_accessory_log = "INSERT INTO `accessory_sales_log`(`accessory_id`, `size_id`, `accessory_name`, `size`, `price`, `cash`, `bank`, `method`, `sales_date`, `update_date`, `quantity`, `user_id`, `status`) 
VALUES ('$accessory_id', '$size_id', '$accessory_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id', '$status')";
$result_adds = mysqli_query($con, $add_accessory_log);

if ($result_adds) {


    $subject = "Refund accessory";
    $message = " Refund accessory\n";
    $message .= "accessory Name: $accessory_name\n";
    $message .= "Size: $size\n";
    $message .= "Quantity: $quantity\n";
    $message .= "Price: $price\n";
    $message .= "Cash: $cash\n";
    $message .= "Bank: $bank\n";
    $message .= "Method: $method\n";
    $message .= "Date: $date\n";


    sendMessageToSubscribers($message, $con);
    sendEmailToSubscribers($message, $subject, $con);




    $sql = "UPDATE accessory SET quantity = quantity + $quantity WHERE id = $accessory_id AND size = $size";
    $result_update = mysqli_query($con, $sql);
}


$sql = "DELETE FROM accessory_sales WHERE sales_id = $update_id";
$result = mysqli_query($con, $sql);


if ($result) {
    echo "<script>window.location.href='../sale_accessory.php?status=success';</script>";
}











if ($result) {
    echo "success";
} else {
    echo "error";
}
