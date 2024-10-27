<?php

$redirect_link = "../../../";
include "../../../include/db.php";

include_once $redirect_link . 'include/email.php';
include_once $redirect_link . 'include/bot.php';


$update_id = $_GET['id'];
$from = $_GET['from'];

$delete = isset($_GET['delete'])? $_GET['delete'] : '';



if ($delete == 'delete') {

    $remove= "DELETE FROM {$from}_verify WHERE id = $update_id";
   
    $remove_res = mysqli_query($con, $remove);
    if ($remove_res) {
        echo "<script>window.location.href='../verify_products.php?status=success';</script>";
    }
}

if (isset($update_id) && isset($from)) {




    $sql = "SELECT * FROM {$from}_verify WHERE id = $update_id";

    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);

    $product_name = $row[$from . '_name'];
    $size = $row['size'];
    $size_id = $row['size_id'];
    $type_id = $row['type_id'];
    $image = $row['image'];
    $type = $row['type'];
    $price = $row['price'];
    $quantity = $row['quantity'];

    $active = $row['active'];
    $error = $row['error'];


    if ($error == '1') {
        $active = '1';
        $insert= "INSERT INTO `$from`(`$from"."_name`, `size`, `type`, `price`, `quantity`, `active`,`size_id`,`type_id`,`image`) VALUES ('$product_name','$size','$type','$price','$quantity','$active','$size_id','$type_id','$image')";
        
        $result_insert = mysqli_query($con, $insert);


        if ($result_insert) {

            $delete = "DELETE FROM {$from}_verify WHERE id = $update_id";
            $result_delete = mysqli_query($con, $delete);
            if ($result_delete) {
                echo "<script>window.location = '../action.php?status=success&redirect=verify_products.php'; </script>";


                $message="Product $product_name has been Verified to the $from \n";
                $message.="Size: $size \n";
                $message.="Quantity: $quantity \n";
                $message.="Price: $price \n";


        

                $subject = "Product Verification";


                sendMessageToSubscribers($message, $con);
                sendEmailToSubscribers($message, $subject, $con);



               


            } else {
                echo "<script>window.location = '../action.php?status=error&redirect=verify_products.php'; </script>";
            }
        } else {
            echo "<script>window.location = '../action.php?status=error&redirect=verify_products.php'; </script>";
        }
    }
    if ($error == '2') {

        $sql = "SELECT * FROM $from WHERE {$from}_name = '$product_name' AND size = '$size' ";
       
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);
        $quantity = $row['quantity'] + $quantity;
        $update = "UPDATE `$from` SET `quantity`= '$quantity' WHERE {$from}_name = '$product_name' AND size = '$size'";
       
        $result_update = mysqli_query($con, $update);
        if ($result_update) {
            $delete = "DELETE FROM {$from}_verify WHERE id = $update_id";
           
            $result_delete = mysqli_query($con, $delete);
            if ($result_delete) {
                echo "<script>window.location = '../action.php?status=success&redirect=verify_products.php'; </script>";

                $message = "Product $product_name has been Verified to the $from \n";
                $message .= "Size: $size \n";
                $message .= "Quantity: $quantity \n";
                $message .= "Price: $price \n";

                $subject = "Product Verification";


                sendMessageToSubscribers($message, $con);
                sendEmailToSubscribers($message, $subject, $con);
            } else {
                echo "<script>window.location = '../action.php?status=error&redirect=verify_products.php'; </script>";
            }
        } else {
            echo "<script>window.location = '../action.php?status=error&redirect=verify_products.php'; </script>";
        }
    }
} 




