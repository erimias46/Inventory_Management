<?php
include("../../../include/db.php");

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $id = $_GET['id'];
        $result = mysqli_query($con, "SELECT * FROM bank WHERE bank_id = $id");
        $item = mysqli_fetch_assoc($result);
        echo json_encode($item);
        break;
    case 'POST':
        $bank_id = $_POST['bank_id'];
        $customer = $_POST['customer'];
        $job_number = $_POST['job_number'];
        $bank_name = $_POST['bank_name'];
        $date = $_POST['date'];
        $reference_number = $_POST['reference_number'];
        $amount = $_POST['amount'];
        $with_hold = $_POST['taxwithholding'];
        $check_no= $_POST['check_no'];
        

        $bank_update = "UPDATE `bank` SET `client`='$customer', `name`='$bank_name', `date`='$date',
                        `reference_number`= '$reference_number', `info_amount`='$amount', `amount`='$amount',
                        `jobnumber` = '$job_number', `taxwithholding` = '$with_hold' WHERE `bank_id` = '$bank_id'";
        $result_update = mysqli_query($con, $bank_update);

        if ($result_update) {
            echo "successfully updated bank statement";
        } else {
            http_response_code(400);
            echo "unable to update bank statement";
            die();
        }
        break;


}