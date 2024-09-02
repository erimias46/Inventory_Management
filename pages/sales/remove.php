<?php 


   $current_date = date('Y-m-d');
    $redirect_link = "../../";
    $side_link = "../../";

    

include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';
   // include '../../include/nav.php'; 
   // $current_date = date('Y-m-d');


    $id = $_GET['id'];
    $from = $_GET['from'];

    if (isset($id) && isset($from)) {
        if ($from == 'sales') {
            $remove = "DELETE FROM sales WHERE sales_id ='$id'";
            $remove_res = mysqli_query($con, $remove);
            if ($remove_res) {
                echo "<script>window.location.href='purchase.php?status=success';</script>";
            }

            
        } elseif ($from == 'sales_withoutvat_purchase') {
            $remove = "DELETE FROM sales_withoutvat_purchase WHERE sales_id ='$id'";
            $remove_res = mysqli_query($con, $remove);
            if ($remove_res) {
                echo "<script>window.location.href='purchase_without.php?status=success';</script>";
            }
        } elseif ($from == 'sales_withvat') {
            $remove = "DELETE FROM sales_withvat WHERE sales_id ='$id'";
            $remove_res = mysqli_query($con, $remove);
            if ($remove_res) {
                echo "<script>window.location.href='sales.php?status=success';</script>";
            }
        } elseif ($from == 'sales_withoutvat') {
            $remove = "DELETE FROM sales_withoutvat WHERE sales_id ='$id'";
            $remove_res = mysqli_query($con, $remove);
            if ($remove_res) {
                echo "<script>window.location.href='sales_without.php?status=success';</script>";
            }
        } 
    } else {
        echo "<script>window.location.href='../../index.php';</script>";
    }



?>