<?php
// Include the necessary files and database connection
$redirect_link = "../../../../";
$side_link = "../../../../";
include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';

// Get the productType and productName from the GET request
$productType = $_GET['productType'];
$productName = $_GET['productName'];

// Make sure productType and productName are not empty
if (empty($productType) || empty($productName)) {
    echo json_encode(['error' => 'Invalid product type or product name']);
    exit;
}

// Prepare the query using productType dynamically and parameterize the productName
$query = "SELECT size, quantity, image FROM `$productType` WHERE `{$productType}_name` = ?";
$stmt = $con->prepare($query);

// Check if the statement was prepared successfully
if ($stmt === false) {
    echo json_encode(['error' => 'Failed to prepare the statement']);
    exit;
}

// Bind the productName parameter to avoid SQL injection
$stmt->bind_param("s", $productName);

// Execute the statement
if ($stmt->execute() === false) {
    echo json_encode(['error' => 'Failed to execute the query']);
    exit;
}

// Get the result of the query
$result = $stmt->get_result();

// Fetch the sizes and quantities into an array
$sizes = [];
$image = null; // To hold the image

while ($row = $result->fetch_assoc()) {
    $sizes[] = ['size' => $row['size'], 'quantity' => $row['quantity']];
    if ($image === null) {
        // Set the image once (assuming all rows have the same image)
        $image = $row['image'];
    }
}

// Return the sizes, quantities, and image as JSON
echo json_encode(['sizes' => $sizes, 'image' => $image]);

// Close the statement and connection (optional but good practice)
// $stmt->close();
// $con->close();
