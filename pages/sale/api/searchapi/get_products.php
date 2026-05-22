<?php

$redirect_link = "../../../../";
$side_link = "../../../../";

include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';

if (isset($_POST['product_type']) && isset($_POST['size'])) {
    $productType = $_POST['product_type'];
    $size = $_POST['size'];

    // Database connection
  

    // Dynamic table name based on product type
    $tableName = $productType . "db";

    // Fetch products with the selected size
    $sql = "SELECT {$productType}_name AS product_name, size, quantity, image 
        FROM {$productType} 
        WHERE size = ? AND quantity > 0";


    $stmt = $con->prepare($sql);
    $stmt->bind_param('s', $size);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<tr>
                    <td>' . $row['product_name'] . '</td>
                    <td>' . $row['size'] . '</td>
                    <td>' . $row['quantity'] . '</td>
                    <td><img src="../../include/' . $row['image'] . '" alt="Product Image" width="80" height="80"></td>
                  </tr>';
        }
    } else {
        echo '<tr><td colspan="4">No products found</td></tr>';
    }

   
}
