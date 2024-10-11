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

    if ($from == 'complete_sales') {

        $sql = "SELECT * FROM complete_sales WHERE sales_id='$id'";
        $res = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($res);
        $complete_name = $row['complete_name'];
        $complete_id = $row['complete_id'];
        $size_id = $row['size_id'];
        $price = $row['price'];
        $cash = $row['cash'];
        $bank = $row['bank'];
        $method = $row['method'];

        $date = $row['sales_date'];

        $size = $row['size'];
        $quantity = $row['quantity'];


        $user_id = $_SESSION['user_id'];


        $sql = "Update complete SET quantity = quantity + $quantity WHERE complete_name = '$complete_name' AND size = '$size'";
        $res = mysqli_query($con, $sql);








        $remove = "DELETE FROM complete_sales WHERE sales_id ='$id'";
        $remove_res = mysqli_query($con, $remove);


        $status = "SELL DELETED";




        $add_complete_log = "INSERT INTO `complete_sales_log`(`complete_id`, `size_id`, `complete_name`, `size`, `price`, `cash`, `bank`, `method`, `sales_date`, `update_date`, `quantity`, `user_id`, `status`) 
VALUES ('$complete_id', '$size_id', '$complete_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id', '$status')";
        $result_adds = mysqli_query($con, $add_complete_log);
        if ($remove_res) {
            echo "<script>window.location.href='../sale_complete.php?status=success';</script>";


            $message = " Sale of complete Deleted:\n";
            $message .= "complete Name: $complete_name\n";
            $message .= "Price: $price\n";
            $message .= "Type: $type\n";
            $message .= "Size: $size\n";
            $message .= "Quantity: $quantity\n";


            $subject = "Sale of complete Deleted";







            sendMessageToSubscribers($message, $con);
            sendEmailToSubscribers($message, $subject, $con);
        }
    } elseif ($from == 'complete_verify') {
        $remove = "DELETE FROM complete_verify WHERE id ='$id'";
        $remove_res = mysqli_query($con, $remove);
        if ($remove_res) {
            echo "<script>window.location.href='../verify.php?status=success';</script>";
        }
    } elseif ($from == 'complete') {

        $sql = "SELECT * FROM complete WHERE id='$id'";
        $res = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($res);
        $complete_name = $row['complete_name'];
        $size = $row['size'];
        $price = $row['price'];
        $type = $row['type'];
        $quantity = $row['quantity'];

        $remove = "DELETE FROM complete WHERE id ='$id'";
        $remove_res = mysqli_query($con, $remove);
        if ($remove_res) {
            echo "<script>window.location.href='../all_complete.php?status=success';</script>";


            $status = "complete DELETED";

            $message = " complete Deleted:\n";
            $message .= "complete Name: $complete_name\n";
            $message .= "Price: $price\n";
            $message .= "Type: $type\n";
            $message .= "Size: $size\n";
            $message .= "Quantity: $quantity\n";
            $message .= "status: $status\n";

            $subject = "complete Deleted";

            sendMessageToSubscribers($message, $con);
            sendEmailToSubscribers($message, $subject, $con);
        }
    } elseif ($from == 'complete_delivery') {
        $sql = "SELECT * FROM complete_delivery WHERE sales_id='$id'";
        $res = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($res);
        $complete_name = $row['complete_name'];
        $complete_id = $row['complete_id'];
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




        $add_complete_log = "INSERT INTO `complete_sales_log`(`complete_id`, `size_id`, `complete_name`, `size`, `price`, `cash`, `bank`, `method`, `sales_date`, `update_date`, `quantity`, `user_id`, `status`) 
VALUES ('$complete_id', '$size_id', '$complete_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id', '$status')";
        $result_adds = mysqli_query($con, $add_complete_log);

        if ($result_adds) {


            $message = " complete Delivery Canceled:\n";
            $message .= "complete Name: $complete_name\n";
            $message .= "Price: $price\n";
            $message .= "Type: $type\n";
            $message .= "Size: $size\n";
            $message .= "Quantity: $quantity\n";
            $message .= "status: $status\n";


            $subject = "complete Delivery Canceled";




            sendMessageToSubscribers($message, $con);
            sendEmailToSubscribers($message, $subject, $con);




            $sql = "UPDATE complete SET quantity = quantity + $quantity WHERE id = $complete_id AND size = $size";
            $result_update = mysqli_query($con, $sql);
        }

        $remove = "DELETE FROM complete_delivery WHERE sales_id ='$id'";
        $remove_res = mysqli_query($con, $remove);
        if ($remove_res) {
            echo "<script>window.location.href='../delivery.php?status=success';</script>";
        }
    }
} else {
    echo "<script>window.location.href='../../index.php';</script>";
}
