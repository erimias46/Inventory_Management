<?php
$redirect_link = "../../";
$side_link = "../../";
include_once '../../include/db.php';

$current_date = date('Y-m-d');


$id = $_GET['id'];
$from = $_GET['from'];

if (isset($id) && isset($from)) {
    if ($from == 'email_subscribers') {


        $remove = "DELETE FROM email_subscribers WHERE id ='$id'";
        $remove_res = mysqli_query($con, $remove);
        if ($remove_res) {
            echo "<script>window.location.href='email.php?status=success';</script>";
        }
    } 
} else {
    echo "<script>window.location.href='../../index.php';</script>";
}
