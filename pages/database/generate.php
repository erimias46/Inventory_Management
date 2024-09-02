<?php
include '../../include/db.php';

if (isset($_POST['generate']) && isset($_POST['update'])) {
    foreach ($_POST['update'] as $update_id) {
        $type = $_GET['type'];
        $primary_key = empty($_GET['primary_key']) ? $type . '_id' : $_GET['primary_key'];
        $sql = "SELECT * FROM $type WHERE $primary_key = $update_id";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);
        if ($type == 'book') {
            $common_var = json_decode($row['common_var'], true);
            $total_output = json_decode($row['total_output'], true);

            $customer = $common_var['customer'];
            $job_type = $common_var['job_type'];
            $size = $common_var['size'];
            $required_quantity = $common_var['required_quantity'];
            $unit_price = $total_output['unit_price'];
            $total_price = $total_output['total_price'];
            $total_price_vat = $total_output['total_price_vat'] ?? $total_price * $row['vat'];
        } else {
            $customer = $row['customer'];
            $job_type = $row['job_type'];
            $size = $row['size'];
            $required_quantity = $row['required_quantity'];
            $unit_price = $row['unit_price'];
            $total_price = $row['total_price'];
            $total_price_vat = $row['total_price_vat'] ?? $total_price * $row['vat'];
        }

        $generate_book = "INSERT INTO generate(customer,job_description,size,quantity,total_price ,unit_price,price_vat ,types) 
                VALUES ('$customer', '$job_type', '$size', '$required_quantity', '$total_price', '$unit_price' , '$total_price_vat','$type')";
        $result_generate = mysqli_query($con, $generate_book);

        

        $generate_book = "INSERT INTO performa_log(customer,job_description,size,quantity,total_price ,unit_price,price_vat ,types) 
                VALUES ('$customer', '$job_type', '$size', '$required_quantity', '$total_price', '$unit_price' , '$total_price_vat','$type')";
        $result_generate = mysqli_query($con, $generate_book);

    }
    if ($result_generate) {
        echo "successfully generated";
    } else {
        http_response_code(500);
        echo "Unable to generate";
    }
} else {
    http_response_code(400);
    echo "update must not be empty";
}