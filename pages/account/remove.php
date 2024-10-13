<?php 
    $redirect_link = "../../";
    $side_link = "../../";
    include_once '../../include/db.php';
    
    $current_date = date('Y-m-d');


    $id = $_GET['id'];
    $from = $_GET['from'];

    if (isset($id) && isset($from)) {
        if ($from == 'customer') {

            $sql= "SELECT * FROM customer WHERE customer_id = '$id'";
            $res = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($res);
            $management_id = $row['management_id'];

            $sql2= "Delete from oli_clients where id = '$management_id'";
            $res2 = mysqli_query($conn, $sql2);


            $remove = "DELETE FROM customer WHERE customer_id ='$id'";
            $remove_res = mysqli_query($con, $remove);
            if ($remove_res) {
                echo "<script>window.location.href='customer.php?status=success';</script>";
            }
        } elseif ($from == 'user') {
            $remove = "DELETE FROM user WHERE user_id ='$id'";
            $remove_res = mysqli_query($con, $remove);
            if ($remove_res) {
                echo "<script>window.location.href='users.php?status=success';</script>";
            }
        } 
    } else {
        echo "<script>window.location.href='../../index.php';</script>";
    }



?>