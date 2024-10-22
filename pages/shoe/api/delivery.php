<?php

$current_date = date('Y-m-d');
$redirect_link = "../../../";
$side_link = "../../../";




$sale_id = $_POST['sales_id'];

$shoes_name = $_POST['shoes_name'];

$sql="SELECT * from shoes WHERE shoes_name = '$shoes_name'";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);

$shoes_id = $row['shoes_id'];

$size = $_POST['size'];


$sql="SELECT * from shoesdb WHERE size = '$size'";
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



$add_sales = "INSERT INTO `shoes_sales`(`shoes_id`, `size_id`, `shoes_name`, `size`, `price`, `cash`, `bank`, `method`, `sales_date`, `update_date`, `quantity`, `user_id`,`bank_id`,`bank_name`, `status`) 
                  VALUES ('$shoes_id', '$size_id', '$shoes_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id','$bank_id','$bank_name', 'active')";
$result_add = mysqli_query($con, $add_sales);

$status = "removed_quantity";

$add_shoes_log = "INSERT INTO `shoes_sales_log`(`shoes_id`, `size_id`, `shoes_name`, `size`, `price`, `cash`, `bank`, `method`, `sales_date`, `update_date`, `quantity`, `user_id`, `status`) 
                      VALUES ('$shoes_id', '$size_id', '$shoes_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id', '$status')";
$result_adds = mysqli_query($con, $add_shoes_log);


$sql = "UPDATE shoes_delivery SET status = 'Delivered', verifiy = 1 WHERE sales_id = $sale_id";
$result = mysqli_query($con, $sql);



$message = "Delivery Have been verified and delivered to customer\n";


$message .= "Shoes Name: $shoes_name\n";
$message .= "Price: $price\n";
$message .= "Size: $size\n";
$message .= "Quantity: $quantity\n";
$message .= "Cash :  $cash\n";
$message .= "Bank : $bank\n";



$subject = "Sold shoes Deliverd to Customer";


sendMessageToSubscribers($message, $con);
sendEmailToSubscribers($message, $subject, $con);



if (!$result_add || !$result_adds || !$result) {
    if(isset($place)){
        echo "<script>window.location = '../../sale/action.php?status=success&redirect=delivery.php'; </script>";
    }
    else{
    echo "<script>window.location = 'action.php?status=error&redirect=sale_shoes.php'; </script>";}
    exit;
}


if (isset($place)) {
    echo "<script>window.location = '../../sale/action.php?status=success&redirect=delivery.php'; </script>";
} else {
echo "<script>window.location = 'action.php?status=success&redirect=sale_shoes.php'; </script>";
}

?>

