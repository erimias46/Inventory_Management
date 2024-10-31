<?php
$redirect_link = "../../";
$side_link = "../../";
include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';


$type = $_GET['type'];
$name = $_GET['name'];

// Query to get the image URL
$sql = "SELECT image FROM `$type` WHERE `{$type}_name` = '$name' LIMIT 1";
$result = mysqli_query($con, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    echo json_encode(['success' => true, 'image' => $row['image']]);
} else {
    echo json_encode(['success' => false, 'message' => 'Image not found']);
}
?>