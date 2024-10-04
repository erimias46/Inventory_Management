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

        if ($from == 'cosmetics_sales') {

            $sql = "SELECT * FROM cosmetics_sales WHERE sales_id='$id'";
            $res = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($res);
            $cosmetics_name = $row['cosmetics_name'];
            $cosmetics_id = $row['cosmetics_id'];
            $size_id = $row['size_id'];
            $price = $row['price'];
            $cash = $row['cash'];
            $bank = $row['bank'];
            $method = $row['method'];
           
            $date = $row['sales_date'];

            $size = $row['size'];
            $quantity = $row['quantity'];


            $user_id=$_SESSION['user_id'];


            $sql="Update cosmetics SET quantity = quantity + $quantity WHERE cosmetics_name = '$cosmetics_name' AND size = '$size'";
            $res = mysqli_query($con, $sql);








            $remove = "DELETE FROM cosmetics_sales WHERE sales_id ='$id'";
            $remove_res = mysqli_query($con, $remove);


        $status = "SELL DELETED";




        $add_cosmetics_log = "INSERT INTO `cosmetics_sales_log`(`cosmetics_id`, `size_id`, `cosmetics_name`, `size`, `price`, `cash`, `bank`, `method`, `sales_date`, `update_date`, `quantity`, `user_id`, `status`) 
VALUES ('$cosmetics_id', '$size_id', '$cosmetics_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id', '$status')";
        $result_adds = mysqli_query($con, $add_cosmetics_log);
            if ($remove_res) {
                echo "<script>window.location.href='../sale_cosmetics.php?status=success';</script>";
            }

            
        } elseif ($from == 'cosmetics_verify') {
            $remove = "DELETE FROM cosmetics_verify WHERE id ='$id'";
            $remove_res = mysqli_query($con, $remove);
            if ($remove_res) {
                echo "<script>window.location.href='../verify.php?status=success';</script>";
            }
        } 

        elseif($from=='cosmetics'){
            $remove = "DELETE FROM cosmetics WHERE id ='$id'";
            $remove_res = mysqli_query($con, $remove);
            if ($remove_res) {
                echo "<script>window.location.href='../all_cosmetics.php?status=success';</script>";
            }
        }

        elseif ($from=='cosmetics_delivery'){
            $sql = "SELECT * FROM cosmetics_delivery WHERE sales_id='$id'";
            $res = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($res);
        $cosmetics_name = $row['cosmetics_name'];
        $cosmetics_id = $row['cosmetics_id'];
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




        $add_cosmetics_log = "INSERT INTO `cosmetics_sales_log`(`cosmetics_id`, `size_id`, `cosmetics_name`, `size`, `price`, `cash`, `bank`, `method`, `sales_date`, `update_date`, `quantity`, `user_id`, `status`) 
VALUES ('$cosmetics_id', '$size_id', '$cosmetics_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id', '$status')";
        $result_adds = mysqli_query($con, $add_cosmetics_log);

        if ($result_adds) {




            $sql = "UPDATE cosmetics SET quantity = quantity + $quantity WHERE id = $cosmetics_id AND size = $size";
            $result_update = mysqli_query($con, $sql);
        }

            $remove = "DELETE FROM cosmetics_delivery WHERE sales_id ='$id'";
            $remove_res = mysqli_query($con, $remove);
            if ($remove_res) {
                echo "<script>window.location.href='../delivery.php?status=success';</script>";
            }
        }
    } else {
        echo "<script>window.location.href='../../index.php';</script>";
    }



?>