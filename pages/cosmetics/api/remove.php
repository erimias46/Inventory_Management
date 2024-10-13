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

        if ($from == 'cosmeticss_sales') {

            $sql = "SELECT * FROM cosmeticss_sales WHERE sales_id='$id'";
            $res = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($res);
            $cosmeticss_name = $row['cosmeticss_name'];
            $cosmeticss_id = $row['cosmeticss_id'];
            $size_id = $row['size_id'];
            $price = $row['price'];
            $cash = $row['cash'];
            $bank = $row['bank'];
            $method = $row['method'];
           
            $date = $row['sales_date'];

            $size = $row['size'];
            $quantity = $row['quantity'];


            $user_id=$_SESSION['user_id'];


            $sql="Update cosmeticss SET quantity = quantity + $quantity WHERE cosmeticss_name = '$cosmeticss_name' AND size = '$size'";
            $res = mysqli_query($con, $sql);








            $remove = "DELETE FROM cosmeticss_sales WHERE sales_id ='$id'";
            $remove_res = mysqli_query($con, $remove);


        $status = "SELL DELETED";




        $add_cosmeticss_log = "INSERT INTO `cosmeticss_sales_log`(`cosmeticss_id`, `size_id`, `cosmeticss_name`, `size`, `price`, `cash`, `bank`, `method`, `sales_date`, `update_date`, `quantity`, `user_id`, `status`) 
VALUES ('$cosmeticss_id', '$size_id', '$cosmeticss_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id', '$status')";
        $result_adds = mysqli_query($con, $add_cosmeticss_log);
            if ($remove_res) {
                echo "<script>window.location.href='../sale_cosmeticss.php?status=success';</script>";


            $message = " Sale of cosmeticss Deleted:\n";
            $message .= "cosmeticss Name: $cosmeticss_name\n";
            $message .= "Price: $price\n";
            $message .= "Type: $type\n";
            $message .= "Size: $size\n";
            $message .= "Quantity: $quantity\n";


            $subject = "Sale of cosmeticss Deleted";







            sendMessageToSubscribers($message, $con);
            sendEmailToSubscribers($message, $subject, $con);
            }

            
        } elseif ($from == 'cosmeticss_verify') {
            $remove = "DELETE FROM cosmeticss_verify WHERE id ='$id'";
            $remove_res = mysqli_query($con, $remove);
            if ($remove_res) {
                echo "<script>window.location.href='../verify.php?status=success';</script>";
            }
        } 

        elseif($from=='cosmeticss'){

            $sql = "SELECT * FROM cosmeticss WHERE id='$id'";
            $res = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($res);
            $cosmeticss_name = $row['cosmeticss_name'];
            $size = $row['size'];
            $price = $row['price'];
            $type = $row['type'];
            $quantity = $row['quantity'];

            $remove = "DELETE FROM cosmeticss WHERE id ='$id'";
            $remove_res = mysqli_query($con, $remove);
            if ($remove_res) {
                echo "<script>window.location.href='../all_cosmeticss.php?status=success';</script>";


            $status = "cosmeticss DELETED";

            $message = " cosmeticss Deleted:\n";
            $message .= "cosmeticss Name: $cosmeticss_name\n";
            $message .= "Price: $price\n";
            $message .= "Type: $type\n";
            $message .= "Size: $size\n";
            $message .= "Quantity: $quantity\n";
            $message .= "status: $status\n";

            $subject = "cosmeticss Deleted";

            sendMessageToSubscribers($message, $con);
            sendEmailToSubscribers($message, $subject, $con);
            }
        }

        elseif ($from=='cosmeticss_delivery'){
            $sql = "SELECT * FROM cosmeticss_delivery WHERE sales_id='$id'";
            $res = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($res);
        $cosmeticss_name = $row['cosmeticss_name'];
        $cosmeticss_id = $row['cosmeticss_id'];
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




        $add_cosmeticss_log = "INSERT INTO `cosmeticss_sales_log`(`cosmeticss_id`, `size_id`, `cosmeticss_name`, `size`, `price`, `cash`, `bank`, `method`, `sales_date`, `update_date`, `quantity`, `user_id`, `status`) 
VALUES ('$cosmeticss_id', '$size_id', '$cosmeticss_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id', '$status')";
        $result_adds = mysqli_query($con, $add_cosmeticss_log);

        if ($result_adds) {


            $message = " cosmeticss Delivery Canceled:\n";
            $message .= "cosmeticss Name: $cosmeticss_name\n";
            $message .= "Price: $price\n";
            $message .= "Type: $type\n";
            $message .= "Size: $size\n";
            $message .= "Quantity: $quantity\n";
            $message .= "status: $status\n";


            $subject = "cosmeticss Delivery Canceled";




            sendMessageToSubscribers($message, $con);
            sendEmailToSubscribers($message, $subject, $con);




            $sql = "UPDATE cosmeticss SET quantity = quantity + $quantity WHERE id = $cosmeticss_id AND size = $size";
            $result_update = mysqli_query($con, $sql);
        }

            $remove = "DELETE FROM cosmeticss_delivery WHERE sales_id ='$id'";
            $remove_res = mysqli_query($con, $remove);
            if ($remove_res) {
                echo "<script>window.location.href='../delivery.php?status=success';</script>";
            }
        }
    } else {
        echo "<script>window.location.href='../../index.php';</script>";
    }



?>