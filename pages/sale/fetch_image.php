<?php
include __DIR__ . '/../../include/db.php';

header('Content-Type: application/json');

$type = $_GET['type'] ?? '';
$name = $_GET['name'] ?? '';

if ($type === '' || $name === '' || !stock_allowed_product_type($type)) {
    echo json_encode(['success' => false, 'message' => 'Missing or invalid parameters']);
    exit;
}

$type = mysqli_real_escape_string($con, $type);
$name = mysqli_real_escape_string($con, $name);
$nameCol = $type . '_name';

$sql = "SELECT image FROM `$type` WHERE `$nameCol` = '$name' LIMIT 1";
$result = mysqli_query($con, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    echo json_encode(['success' => true, 'image' => $row['image']]);
} else {
    echo json_encode(['success' => false, 'message' => 'Image not found']);
}
