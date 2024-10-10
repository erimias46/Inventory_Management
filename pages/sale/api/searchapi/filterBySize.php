<?php 
$redirect_link = "../../../../";
$side_link = "../../../../";

include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';



$size = isset($_GET['size']) ? $_GET['size'] : '';
$productName = isset($_GET['productName']) ? $_GET['productName'] : '';


echo $size;
echo $productName;

// Prepare SQL query to filter products by product name and size
if (!empty($size) && !empty($productName)) {
// SQL query to get products with the specified product name and size
$sql = "SELECT {$productName}_name, size, quantity FROM {$productName} WHERE {$productName}_name = ? AND size = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $productName, $size);
$stmt->execute();
$result = $stmt->get_result();

// Create an array to store the fetched products
$products = [];

if ($result->num_rows > 0) {
// Fetch results and store them in the array
while ($row = $result->fetch_assoc()) {
$products[] = [
'name' => $row['name'],
'size' => $row['size'],
'quantity' => $row['quantity']
];
}
} else {
// If no results, send an empty array
$products = [];
}

// Send the response in JSON format
header('Content-Type: application/json');
echo json_encode(['products' => $products]);

// Close the prepared statement and the connection
$stmt->close();
} else {
// If size or productName is missing, return an error message
echo json_encode(['error' => 'Invalid size or product name']);
}


?>