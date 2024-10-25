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
        // Dynamic table name based on product type
        $tableName = $productType . "db";

        // Create placeholders for the IN clause
        $placeholders = implode(',', array_fill(0, count($sizes), '?'));

        // Fetch products that have all selected sizes
        $sql = "SELECT {$productType}_name AS product_name, 
                       GROUP_CONCAT(CONCAT(size, ' (', quantity, ' qty)') ORDER BY size) AS size_quantity,
                       image 
                FROM {$productType} 
                WHERE size IN ($placeholders) 
                and quantity > 0
                GROUP BY {$productType}_name 
                HAVING COUNT(DISTINCT size) = ?  ";

        $stmt = $con->prepare($sql);

        // Dynamically bind the size parameters and the size count
        $types = str_repeat('s', count($sizes)) . 'i';  // 's' for each size, 'i' for the count
        $params = array_merge($sizes, [count($sizes)]); // Combine sizes and count into one array

        // Use call_user_func_array to bind the parameters dynamically
        $stmt->bind_param($types, ...$params);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<tr>
                        <td>' . $row['product_name'] . '</td>
                        <td>' . $row['size_quantity'] . '</td>
                        <td><img src="../../include/' . $row['image'] . '" alt="Product Image" width="80" height="80"></td>
                      </tr>';
            }
        } else {
            echo '<tr><td colspan="4">No products found with all selected sizes</td></tr>';
        }
    } else {
        echo '<tr><td colspan="4">No sizes selected</td></tr>';
    }
}
