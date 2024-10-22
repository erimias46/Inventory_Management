<?php


$current_date = date('Y-m-d');
$redirect_link = "../../../";
$side_link = "../../../";



include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';

include_once $redirect_link . 'include/email.php';
include_once $redirect_link . 'include/bot.php';
// include '../../include/nav.php'; 
// $current_date = date('Y-m-d');


$id = $_GET['id'];
$from = $_GET['from'];
$source = $_GET['source'];


if ($from == 'delivery') {


    if($source =="jeans"){
        $table='delivery';
    }
    else{
        $table=$source.'_delivery';
    }


    $sql="SELECT * FROM $table WHERE sales_id='$id'";
    $res = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($res);

    $product_name = $row[$source.'_name'];
    $product_id = $row[$source.'_id'];
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


$status = "DELIVERY CANCELED";


if($source == 'jeans'){
    $sales_log='sales_log';
}
else{
    $sales_log=$source.'_sales_log';
}

$source_id = $source.'_id';
$source_name = $source.'_name';



$add_sales_log = "INSERT INTO $sales_log(`$source_id`, `size_id`, `$source_name`, `size`, `price`, `cash`, `bank`, `method`, `sales_date`, `update_date`, `quantity`, `user_id`, `status`)
VALUES ('$product_id', '$size_id', '$product_name', '$size', '$price', '$cash', '$bank', '$method', '$sales_date', '$date', '$quantity', '$user_id', '$status')";
$result_adds = mysqli_query($con, $add_sales_log);



if ($result_adds) {


$message = " $source Delivery Canceled:\n";
$message .= "$source Name: $product_name\n";
$message .= "Price: $price\n";
$message .= "Type: $type\n";
$message .= "Size: $size\n";
$message .= "Quantity: $quantity\n";
$message .= "status: $status\n";

$subject = "$source Delivery Canceled";




sendMessageToSubscribers($message, $con);
sendEmailToSubscribers($message, $subject, $con);



$sql="UPDATE $source SET quantity = quantity + $quantity WHERE id = $product_id AND size = $size";

$result_update = mysqli_query($con, $sql);
}



$remove="DELETE FROM $table WHERE sales_id ='$id'";
$remove_res = mysqli_query($con, $remove);
if ($remove_res) {
echo "<script>
    window.location.href = '../delivery.php?status=success';
</script>";
}
}
