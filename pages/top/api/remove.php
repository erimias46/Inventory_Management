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

        if ($from == 'top_sales') {

            $sql = "SELECT * FROM top_sales WHERE sales_id='$id'";
            $res = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($res);
            $top_name = $row['top_name'];
            $top_id = $row['top_id'];
            $size_id = $row['size_id'];
            $price = $row['price'];
            $cash = $row['cash'];
            $bank = $row['bank'];
            $method = $row['method'];
           
            $date = $row['sales_date'];

            $size = $row['size'];
            $quantity = $row['quantity'];


            $user_id=$_SESSION['user_id'];


            $sql="Update top SET quantity = quantity + $quantity WHERE top_name = '$top_name' AND size = '$size'";
            $res = mysqli_query($con, $sql);








            $remove = "DELETE FROM top_sales WHERE sales_id ='$id'";
            $remove_res = mysqli_query($con, $remove);


        $status = "SELL DELETED";




        $add_top_log = "INSERT INTO `top_sales_log`(`top_id`, `size_id`, `top_name`, `size`, `price`, `cash`, `bank`, `method`, `sales_date`, `update_date`, `quantity`, `user_id`, `status`) 
VALUES ('$top_id', '$size_id', '$top_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id', '$status')";
        $result_adds = mysqli_query($con, $add_top_log);
            if ($remove_res) {
                echo "<script>window.location.href='../sale_top.php?status=success';</script>";
            }

            
        } elseif ($from == 'top_verify') {
            $remove = "DELETE FROM top_verify WHERE id ='$id'";
            $remove_res = mysqli_query($con, $remove);
            if ($remove_res) {
                echo "<script>window.location.href='../verify.php?status=success';</script>";
            }
        } 

        elseif($from=='top'){
            $remove = "DELETE FROM top WHERE id ='$id'";
            $remove_res = mysqli_query($con, $remove);
            if ($remove_res) {
                echo "<script>window.location.href='../all_top.php?status=success';</script>";
            }
        }

        elseif ($from=='top_delivery'){
            $sql = "SELECT * FROM top_delivery WHERE sales_id='$id'";
            $res = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($res);
        $top_name = $row['top_name'];
        $top_id = $row['top_id'];
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




        $add_top_log = "INSERT INTO `top_sales_log`(`top_id`, `size_id`, `top_name`, `size`, `price`, `cash`, `bank`, `method`, `sales_date`, `update_date`, `quantity`, `user_id`, `status`) 
VALUES ('$top_id', '$size_id', '$top_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id', '$status')";
        $result_adds = mysqli_query($con, $add_top_log);

        if ($result_adds) {




            $sql = "UPDATE top SET quantity = quantity + $quantity WHERE id = $top_id AND size = $size";
            $result_update = mysqli_query($con, $sql);
        }

            $remove = "DELETE FROM top_delivery WHERE sales_id ='$id'";
            $remove_res = mysqli_query($con, $remove);
            if ($remove_res) {
                echo "<script>window.location.href='../delivery.php?status=success';</script>";
            }
        }
    } else {
        echo "<script>window.location.href='../../index.php';</script>";
    }



?>