<?php


$redirect_link = "";
include 'partials/main.php';


include 'include/db.php';

$redirect="index2.php";
header("Location: $redirect");


$id = $_SESSION['user_id'];


?>

