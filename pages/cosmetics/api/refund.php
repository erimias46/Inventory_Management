<?php

include "../../../include/db.php";

$update_id = $_GET['id'];
$from = $_GET['from'];

$sql="SELECT * FROM cosmetics_sales WHERE sales_id = $update_id";

$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);
$cosmetics_name = $row['cosmetics_name'];
$cosmetics_id=$row['cosmetics_id'];
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




$add_cosmetics_log = "INSERT INTO `cosmetics_sales_log`(`cosmetics_id`, `size_id`, `cosmetics_name`, `size`, `price`, `cash`, `bank`, `method`, `sales_date`, `update_date`, `quantity`, `user_id`, `status`) 
VALUES ('$cosmetics_id', '$size_id', '$cosmetics_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id', '$status')";
$result_adds = mysqli_query($con, $add_cosmetics_log);

if($result_adds){

    

    
    $sql="UPDATE cosmetics SET quantity = quantity + $quantity WHERE id = $cosmetics_id AND size = $size";
    $result_update = mysqli_query($con, $sql);
}


$sql="DELETE FROM cosmetics_sales WHERE sales_id = $update_id";
$result = mysqli_query($con, $sql);


if ($result) {
    echo "<script>window.location.href='../sale_cosmetics.php?status=success';</script>";
}











if($result){
    echo "success";
}else{
    echo "error";
}

