<?php

$status = $_GET['status'];
$redirect = $_GET['redirect'];

if ($status AND $redirect) {
    if ($status == 'success') {
        echo "<script>window.location.href='$redirect?status=success';</script>";
    } else {
        echo "<script>window.location.href='$redirect?status=error';</script>";
    }
}


?>