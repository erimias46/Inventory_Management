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

    if (isset($id) && isset($from)) {

        if ($from == 'shoes_sales') {

            $sql = "SELECT * FROM shoes_sales WHERE sales_id='$id'";
            $res = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($res);
            $shoes_name = $row['shoes_name'];
            $shoes_id = $row['shoes_id'];
            $size_id = $row['size_id'];
            $price = $row['price'];
            $cash = $row['cash'];
            $bank = $row['bank'];
            $method = $row['method'];
           
            $date = $row['sales_date'];

            $size = $row['size'];
            $quantity = $row['quantity'];


            $user_id=$_SESSION['user_id'];


            $sql="Update shoes SET quantity = quantity + $quantity WHERE shoes_name = '$shoes_name' AND size = '$size'";
            $res = mysqli_query($con, $sql);








            $remove = "DELETE FROM shoes_sales WHERE sales_id ='$id'";
            $remove_res = mysqli_query($con, $remove);


        $status = "SELL DELETED";




        $add_shoes_log = "INSERT INTO `shoes_sales_log`(`shoes_id`, `size_id`, `shoes_name`, `size`, `price`, `cash`, `bank`, `method`, `sales_date`, `update_date`, `quantity`, `user_id`, `status`) 
VALUES ('$shoes_id', '$size_id', '$shoes_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id', '$status')";
        $result_adds = mysqli_query($con, $add_shoes_log);
            if ($remove_res) {
                echo "<script>window.location.href='../sale_shoes.php?status=success';</script>";


            $message = " Sale of shoes Deleted:\n";
            $message .= "shoes Name: $shoes_name\n";
            $message .= "Price: $price\n";
            $message .= "Type: $type\n";
            $message .= "Size: $size\n";
            $message .= "Quantity: $quantity\n";


            $subject = "Sale of shoes Deleted";







            sendMessageToSubscribers($message, $con);
            sendEmailToSubscribers($message, $subject, $con);
            }

            
        } elseif ($from == 'shoes_verify') {
            $remove = "DELETE FROM shoes_verify WHERE id ='$id'";
            $remove_res = mysqli_query($con, $remove);
            if ($remove_res) {
                echo "<script>window.location.href='../verify.php?status=success';</script>";
            }
        } 

        elseif($from=='shoes'){

            $sql = "SELECT * FROM shoes WHERE id='$id'";
            $res = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($res);
            $shoes_name = $row['shoes_name'];
            $size = $row['size'];
            $price = $row['price'];
            $type = $row['type'];
            $quantity = $row['quantity'];

            $remove = "DELETE FROM shoes WHERE id ='$id'";
            $remove_res = mysqli_query($con, $remove);
            if ($remove_res) {
                echo "<script>window.location.href='../all_shoes.php?status=success';</script>";


            $status = "Shoes DELETED";

            $message = " shoes Deleted:\n";
            $message .= "shoes Name: $shoes_name\n";
            $message .= "Price: $price\n";
            $message .= "Type: $type\n";
            $message .= "Size: $size\n";
            $message .= "Quantity: $quantity\n";
            $message .= "status: $status\n";

            $subject = "Shoes Deleted";

            sendMessageToSubscribers($message, $con);
            sendEmailToSubscribers($message, $subject, $con);
            }
        }

        elseif ($from=='shoes_delivery'){
            $sql = "SELECT * FROM shoes_delivery WHERE sales_id='$id'";
            $res = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($res);
        $shoes_name = $row['shoes_name'];
        $shoes_id = $row['shoes_id'];
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




        $add_shoes_log = "INSERT INTO `shoes_sales_log`(`shoes_id`, `size_id`, `shoes_name`, `size`, `price`, `cash`, `bank`, `method`, `sales_date`, `update_date`, `quantity`, `user_id`, `status`) 
VALUES ('$shoes_id', '$size_id', '$shoes_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id', '$status')";
        $result_adds = mysqli_query($con, $add_shoes_log);

        if ($result_adds) {


            $message = " shoes Delivery Canceled:\n";
            $message .= "shoes Name: $shoes_name\n";
            $message .= "Price: $price\n";
            $message .= "Type: $type\n";
            $message .= "Size: $size\n";
            $message .= "Quantity: $quantity\n";
            $message .= "status: $status\n";


            $subject = "shoes Delivery Canceled";




            sendMessageToSubscribers($message, $con);
            sendEmailToSubscribers($message, $subject, $con);




            $sql = "UPDATE shoes SET quantity = quantity + $quantity WHERE id = $shoes_id AND size = $size";
            $result_update = mysqli_query($con, $sql);
        }

            $remove = "DELETE FROM shoes_delivery WHERE sales_id ='$id'";
            $remove_res = mysqli_query($con, $remove);
            if ($remove_res) {
                echo "<script>window.location.href='../delivery.php?status=success';</script>";
            }
        }
    } else {
        echo "<script>window.location.href='../../index.php';</script>";
    }



?>