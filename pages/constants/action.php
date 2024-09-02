<?php

$id = $_GET['id'];
$status = $_GET['status'];
$redirect = $_GET['redirect'];

if ($status AND $redirect) {
    if ($status == 'success') {
        echo "<script>window.location.href='$redirect?id=$id&status=success';</script>";
    } else {
        echo "<script>window.location.href='$redirect?id=$id&status=error';</script>";
    }
}


?>