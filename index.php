<?php


$redirect_link = "";
include 'partials/main.php';


include 'include/db.php';

$redirect="pages/sale/multi.php";
header("Location: $redirect");


$id = $_SESSION['user_id'];


?>

