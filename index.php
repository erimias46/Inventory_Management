<?php


$redirect_link = "";
include 'partials/main.php';


include 'include/db.php';




$id = $_SESSION['user_id'];


if ($user_name == "master_admin") {

    $redirect = "index2.php";
    header("Location: $redirect");

}

else {
    
    $redirect = "pages/sale/main.php";
    header("Location: $redirect");
}


?>

