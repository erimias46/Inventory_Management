<?php

include "../../../include/db.php";

$update_id = $_GET['id'];
$from = $_GET['from'];

$sql="SELECT * FROM complete_sales WHERE sales_id = $update_id";

$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);
$complete_name = $row['complete_name'];
$complete_id=$row['complete_id'];
$size = $row['size'];
$size_id=$row['size_id'];
$price = $row['price'];
$cash = $row['cash'];
$bank = $row['bank'];
$method = $row['method'];
$quantity = $row['quantity'];
$user_id=$row['user_id'];
$sales_date=$row['sales_date'];
$update_date=$row['update_date'];

$date=date('Y-m-d');


$status="Refund";




$add_complete_log = "INSERT INTO `complete_sales_log`(`complete_id`, `size_id`, `complete_name`, `size`, `price`, `cash`, `bank`, `method`, `sales_date`, `update_date`, `quantity`, `user_id`, `status`) 
VALUES ('$complete_id', '$size_id', '$complete_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id', '$status')";
$result_adds = mysqli_query($con, $add_complete_log);

if($result_adds){

    

    
    $sql="UPDATE complete SET quantity = quantity + $quantity WHERE id = $complete_id AND size = $size";
    $result_update = mysqli_query($con, $sql);
}


$sql="DELETE FROM complete_sales WHERE sales_id = $update_id";
$result = mysqli_query($con, $sql);


if ($result) {
    echo "<script>window.location.href='../sale_complete.php?status=success';</script>";
}











if($result){
    echo "success";
}else{
    echo "error";
}
