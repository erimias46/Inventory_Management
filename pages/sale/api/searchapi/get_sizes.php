<?php

// Include the necessary files and database connection
$redirect_link = "../../../../";
$side_link = "../../../../";
include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';


if (isset($_POST['product_type'])) {
    $productType = $_POST['product_type'];

    // Database connection
    

    // Dynamic table name based on product type
    $tableName = $productType . "db";

    // Fetch available sizes from the corresponding table
    $sql = "SELECT DISTINCT size FROM $tableName";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        echo '<option value="">Select Size</option>';
        while ($row = $result->fetch_assoc()) {
            echo '<option value="' . $row['size'] . '">' . $row['size'] . '</option>';
        }
    } else {
        echo '<option value="">No sizes available</option>';
    }

   
}
