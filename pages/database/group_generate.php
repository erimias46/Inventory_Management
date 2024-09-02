<?php
$redirect_link = "../../";
$side_link = "../../";
include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';

$data = json_decode(file_get_contents("php://input"), true);
$ids = $data['ids'];
$type = $data['type'];

if (count($ids) > 0) {
    // Initialize variables for numeric fields
    $total_price_vat = 0;
    $unit_price = 0;
    $total_price = 0;
    $quantity = 0;

    // Initialize variables for text fields, start with an empty string
    $customer = '';
    $job_description = '';
    $size = '';

    foreach ($ids as $id) {
        if ($type == 'design') {
            $primary_key = 'digital_id';
        } else {
            $primary_key = $type . '_id';
        }

        // Query to get the details for the selected ID
        $item_sql = "SELECT * FROM $type WHERE $primary_key = '$id'";
        $item_result = mysqli_query($con, $item_sql);
        $item_row = mysqli_fetch_assoc($item_result);


        if($type == 'book'){
            $common_var = json_decode($item_row['common_var'], true);
            $customer = $common_var['customer'];

            if (!empty($job_description)) {
                $job_description .= ', ';
            }
            if (!empty($size)) {
                $size .= ', ';
            }


            $job_description .= $common_var['job_type'];
            $size .= $common_var['size'];
           


            $total_output = json_decode($item_row['total_output'], true);
            $unit_price += $total_output['unit_price'];
            $total_price += $total_output['total_price'];
            $total_price_vat += $total_output['total_price_vat'] ?? $total_price * $item_row['vat'];
            $quantity += $common_var['required_quantity'];

        }else{

        // Concatenate text fields with a comma and space, but avoid leading commas
        if (!empty($customer)) {
            $customer .= ', ';
        }
        if (!empty($job_description)) {
            $job_description .= ', ';
        }
        if (!empty($size)) {
            $size .= ', ';
        }

        // Concatenate current values
        $customer = $item_row['customer'];
        $job_description .= $item_row['job_type'];
        $size .= $item_row['size'];

        // Sum the numeric fields
        $total_price_vat += $item_row['total_price_vat'];
        $unit_price += $item_row['unit_price'];
        $total_price += $item_row['total_price'];
        $quantity += $item_row['required_quantity'];

    }


    }

    // Insert the combined data into the generate table
    $insert_sql = "INSERT INTO generate (customer, job_description, size, quantity, total_price, unit_price, price_vat,types)
                   VALUES ('$customer', '$job_description', '$size', '$quantity', '$total_price', '$unit_price', '$total_price_vat', '$type')";

    if (mysqli_query($con, $insert_sql)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => mysqli_error($con)]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'No IDs provided']);
}
