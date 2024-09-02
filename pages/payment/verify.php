<?php 
    $redirect_link = "../../";
    $side_link = "../../";
    include '../../include/nav.php'; 
    $current_date = date('Y-m-d');


    $id = $_GET['id'];
    $from = $_GET['from'];

    if (isset($id) && isset($from)) {
        if ($from == 'bank') {
            $remove = "UPDATE `bank` SET verified = '1' WHERE bank_id ='$id'";
            $remove_res = mysqli_query($con, $remove);
            if ($remove_res) {
                echo "<script>window.location.href='bank_statment.php?status=success';</script>";
            }
        } 
    } else {
        echo "<script>window.location.href='../../index.php';</script>";
    }



?>