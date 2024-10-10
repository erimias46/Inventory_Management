<?php

$redirect_link = "../../../../";
$side_link = "../../../../";

include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';

if (isset($_POST['product_type']) && isset($_POST['sizes'])) {
    $productType = $_POST['product_type'];
    $sizes = $_POST['sizes']; // 'sizes' is now an array of selected sizes

    // Check if sizes array is not empty
    if (!empty($sizes)) {
        // Database connection

        // Dynamic table name based on product type
        $tableName = $productType . "db";

        // Create placeholders for the IN clause
        $placeholders = implode(',', array_fill(0, count($sizes), '?'));

        // Fetch products with the selected sizes
        $sql = "SELECT {$productType}_name AS product_name, size, quantity, image FROM {$productType} WHERE size IN ($placeholders)";

        $stmt = $con->prepare($sql);

        // Bind each size value to the statement
        $stmt->bind_param(str_repeat('s', count($sizes)), ...$sizes);

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
    } else {
        echo '<tr><td colspan="4">No sizes selected</td></tr>';
    }
}
