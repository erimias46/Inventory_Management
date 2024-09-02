<?php
include '../../include/db.php';




if (isset($_POST['generate']) && isset($_POST['update'])) {
    foreach ($_POST['update'] as $update_id) {



        $sql="SELECT * FROM payment WHERE payment_id = $update_id";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);
        $customer = $row['client'];
        $job_type = $row['job_description'];
        $size = $row['size'];
        $required_quantity = $row['quantity'];
        $unit_price = $row['unit_price'];
        
        $total_price_vat = $row['total'];
        $total_price = $unit_price * $required_quantity;



       
        $type = "payment";



        

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