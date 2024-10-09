<?php

$redirect_link = "../../../";
$side_link = "../../../";

include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php'; // Include your database connection
include_once $redirect_link . 'include/email.php';
include_once $redirect_link . 'include/bot.php';

$update_id = $_GET['sales_id'];
$type = $_GET['type'];
$from = $_GET['from'];

// Adjust the query to select from the respective table based on the type

if($type=='jeans'){

    $sql = "SELECT * FROM sales WHERE sales_id = $update_id";

}
else{

    $sql = "SELECT * FROM shoes_sales WHERE sales_id = $update_id";

}


$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);
$item_name = $row[$type . '_name'];
$item_id = $row[$type . '_id'];
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

// Insert the refund log into the respective table based on type

if($type=='jeans'){
    $add_item_log = "INSERT INTO `sales_log`(`jeans_id`, `size_id`, `jeans_name`, `size`, `price`, `cash`, `bank`, `method`, `sales_date`, `update_date`, `quantity`, `user_id`, `status`) 
    VALUES ('$item_id', '$size_id', '$item_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id', '$status')";
}
else{
    
$add_item_log = "INSERT INTO `{$type}_sales_log`(`{$type}_id`, `size_id`, `{$type}_name`, `size`, `price`, `cash`, `bank`, `method`, `sales_date`, `update_date`, `quantity`, `user_id`, `status`) 
VALUES ('$item_id', '$size_id', '$item_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id', '$status')";
}
$result_adds = mysqli_query($con, $add_item_log);

if ($result_adds) {
    $subject = "Refund $type";
    $message = "Refund $type\n";
    $message .= ucfirst($type) . " Name: $item_name\n";
    $message .= "Size: $size\n";
    $message .= "Quantity: $quantity\n";
    $message .= "Price: $price\n";
    $message .= "Cash: $cash\n";
    $message .= "Bank: $bank\n";
    $message .= "Method: $method\n";
    $message .= "Date: $date\n";

    sendMessageToSubscribers($message, $con);
    sendEmailToSubscribers($message, $subject, $con);

    // Update the stock for the respective item
    $sql = "UPDATE `$type` SET quantity = quantity + $quantity WHERE id = $item_id AND size = $size";
    $result_update = mysqli_query($con, $sql);
}

// Delete the sales record from the respective table

if($type=='jeans'){

    $sql = "DELETE FROM sales WHERE sales_id = $update_id";

}
else{
$sql = "DELETE FROM `{$type}_sales` WHERE sales_id = $update_id";
}

$result = mysqli_query($con, $sql);

if ($result) {
    echo "<script>window.location.href='../all_sales.php?status=success';</script>";
}

if ($result) {
    echo "success";
} else {
    echo "error";
}
