<?php
include("../../../include/db.php");



$type_priniting=$_POST['types'];

if($type_priniting=='digital'){
    include("digital.php");
    include('bookmachine.php');
}
else{
    include("cover.php");
    include('bookmachine.php');
}


?>