<?php

include "../../../include/db.php";

$update_id = $_GET['id'];
$from = $_GET['from'];

$sql="SELECT * FROM top_sales WHERE sales_id = $update_id";

$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);
$top_name = $row['top_name'];
$top_id=$row['top_id'];
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




$add_top_log = "INSERT INTO `top_sales_log`(`top_id`, `size_id`, `top_name`, `size`, `price`, `cash`, `bank`, `method`, `sales_date`, `update_date`, `quantity`, `user_id`, `status`) 
VALUES ('$top_id', '$size_id', '$top_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id', '$status')";
$result_adds = mysqli_query($con, $add_top_log);

if($result_adds){

    

    
    $sql="UPDATE top SET quantity = quantity + $quantity WHERE id = $top_id AND size = $size";
    $result_update = mysqli_query($con, $sql);
}


$sql="DELETE FROM top_sales WHERE sales_id = $update_id";
$result = mysqli_query($con, $sql);


if ($result) {
    echo "<script>window.location.href='../sale_top.php?status=success';</script>";
}











if($result){
    echo "success";
}else{
    echo "error";
}

