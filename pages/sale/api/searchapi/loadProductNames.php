<?php
$productType = $_GET['productType'];



$redirect_link = "../../../../";
$side_link = "../../../../";

include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';




// Fetch product names based on product type
// Properly build the table and column name
$query = "SELECT id, `" . $productType . "_name` as name FROM `" . $productType . "`  GROUP BY `" . $productType . "_name`";

$result = $con->query($query);

$productNames = [];
while ($row = $result->fetch_assoc()) {
    $productNames[] = ['id' => $row['id'], 'name' => $row['name']];
}

echo json_encode(['productNames' => $productNames]);

?>