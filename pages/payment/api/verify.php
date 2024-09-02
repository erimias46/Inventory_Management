<?php
include("../../../include/db.php");

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $bank_id = $_POST['bank_id'];
        $qry = mysqli_query($con, "UPDATE bank SET verified = 1 WHERE bank_id='$bank_id'");
        if ($qry) {
            echo 'payment verified';
        } else {
           http_response_code(400);
           echo 'unable to verify';
        }
        break;
    }