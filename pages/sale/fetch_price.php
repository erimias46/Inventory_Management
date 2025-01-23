<?php 

$redirect_link = "../../";
$side_link = "../../";
include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get POST parameters
$table = mysqli_real_escape_string($con, $_POST['table']);
$code_name = mysqli_real_escape_string($con, $_POST['code_name']);
$size = mysqli_real_escape_string($con, $_POST['size']);

// Initialize response array
$response = array();

// Validate table name to prevent SQL injection
$allowed_tables = ['jeans', 'shoes', 'complete', 'accessory', 'top'];
if (!in_array($table, $allowed_tables)) {
    $response['error'] = 'Invalid table name';
    echo json_encode($response);
    exit;
}

try {
    // Step 1: Get product details (price and image) based on code_name, any size
    $sqlProduct = "SELECT price, image FROM $table WHERE {$table}_name = ? LIMIT 1";
    $stmtProduct = mysqli_prepare($con, $sqlProduct);
    if ($stmtProduct === false) {
        throw new Exception("Error preparing product statement: " . mysqli_error($con));
    }
    mysqli_stmt_bind_param($stmtProduct, "s", $code_name);
    if (!mysqli_stmt_execute($stmtProduct)) {
        throw new Exception("Error executing product statement: " . mysqli_stmt_error($stmtProduct));
    }
    $resultProduct = mysqli_stmt_get_result($stmtProduct);
    $productRow = mysqli_fetch_assoc($resultProduct);
    mysqli_stmt_close($stmtProduct);

    if (!$productRow) {
        // Product not found at all
        $response = array('price' => 0, 'stock' => 0, 'image' => null);
        echo json_encode($response);
        exit;
    }

    $price = $productRow['price'];
    $image = $productRow['image'];

    // Step 2: Check if the specific size exists to get the quantity
    $sqlStock = "SELECT quantity FROM $table WHERE {$table}_name = ? AND size = ?";
    $stmtStock = mysqli_prepare($con, $sqlStock);
    if ($stmtStock === false) {
        throw new Exception("Error preparing stock statement: " . mysqli_error($con));
    }
    mysqli_stmt_bind_param($stmtStock, "ss", $code_name, $size);
    if (!mysqli_stmt_execute($stmtStock)) {
        throw new Exception("Error executing stock statement: " . mysqli_stmt_error($stmtStock));
    }
    $resultStock = mysqli_stmt_get_result($stmtStock);
    $stockRow = mysqli_fetch_assoc($resultStock);
    mysqli_stmt_close($stmtStock);

    $stock = $stockRow ? intval($stockRow['quantity']) : 0;

    // Prepare response
    $response = array(
        'price' => floatval($price),
        'stock' => $stock,
        'image' => $image
    );

} catch (Exception $e) {
    $response['error'] = 'Database error: ' . $e->getMessage();
    error_log($e->getMessage());
} finally {
    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>