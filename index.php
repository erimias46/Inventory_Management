<?php


$redirect_link = "";
include 'partials/main.php';


include 'include/db.php';




$id = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? '';

if ($username === 'masteradmin') {
    header('Location: index2.php');
    exit;
}

header('Location: pages/sale/main.php');
exit;


?>

