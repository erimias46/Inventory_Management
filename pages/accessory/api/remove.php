<?php 


   $current_date = date('Y-m-d');
    $redirect_link = "../../../";
    $side_link = "../../../";

    

include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';
   // include '../../include/nav.php'; 
   // $current_date = date('Y-m-d');


    $id = $_GET['id'];
    $from = $_GET['from'];

    if (isset($id) && isset($from)) {

        if ($from == 'accessory_sales') {

            $sql = "SELECT * FROM accessory_sales WHERE sales_id='$id'";
            $res = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($res);
            $accessory_name = $row['accessory_name'];
            $accessory_id = $row['accessory_id'];
            $size_id = $row['size_id'];
            $price = $row['price'];
            $cash = $row['cash'];
            $bank = $row['bank'];
            $method = $row['method'];
           
            $date = $row['sales_date'];

            $size = $row['size'];
            $quantity = $row['quantity'];


            $user_id=$_SESSION['user_id'];


            $sql="Update accessory SET quantity = quantity + $quantity WHERE accessory_name = '$accessory_name' AND size = '$size'";
            $res = mysqli_query($con, $sql);








            $remove = "DELETE FROM accessory_sales WHERE sales_id ='$id'";
            $remove_res = mysqli_query($con, $remove);


        $status = "SELL DELETED";




        $add_accessory_log = "INSERT INTO `accessory_sales_log`(`accessory_id`, `size_id`, `accessory_name`, `size`, `price`, `cash`, `bank`, `method`, `sales_date`, `update_date`, `quantity`, `user_id`, `status`) 
VALUES ('$accessory_id', '$size_id', '$accessory_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id', '$status')";
        $result_adds = mysqli_query($con, $add_accessory_log);
            if ($remove_res) {
                echo "<script>window.location.href='../sale_accessory.php?status=success';</script>";
            }

            
        } elseif ($from == 'accessory_verify') {
            $remove = "DELETE FROM accessory_verify WHERE id ='$id'";
            $remove_res = mysqli_query($con, $remove);
            if ($remove_res) {
                echo "<script>window.location.href='../verify.php?status=success';</script>";
            }
        } 

        elseif($from=='accessory'){
            $remove = "DELETE FROM accessory WHERE id ='$id'";
            $remove_res = mysqli_query($con, $remove);
            if ($remove_res) {
                echo "<script>window.location.href='../all_accessory.php?status=success';</script>";
            }
        }

        elseif ($from=='accessory_delivery'){
            $sql = "SELECT * FROM accessory_delivery WHERE sales_id='$id'";
            $res = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($res);
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


        $status = "DELIVERY CANCELED";




        $add_accessory_log = "INSERT INTO `accessory_sales_log`(`accessory_id`, `size_id`, `accessory_name`, `size`, `price`, `cash`, `bank`, `method`, `sales_date`, `update_date`, `quantity`, `user_id`, `status`) 
VALUES ('$accessory_id', '$size_id', '$accessory_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id', '$status')";
        $result_adds = mysqli_query($con, $add_accessory_log);

        if ($result_adds) {




            $sql = "UPDATE accessory SET quantity = quantity + $quantity WHERE id = $accessory_id AND size = $size";
            $result_update = mysqli_query($con, $sql);
        }

            $remove = "DELETE FROM accessory_delivery WHERE sales_id ='$id'";
            $remove_res = mysqli_query($con, $remove);
            if ($remove_res) {
                echo "<script>window.location.href='../delivery.php?status=success';</script>";
            }
        }
    } else {
        echo "<script>window.location.href='../../index.php';</script>";
    }



?>