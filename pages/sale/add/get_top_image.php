<?php

$redirect_link = "../../../";
$side_link = "../../../";
include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';

if (isset($_GET['top_name'])) {
   

    $top_name = mysqli_real_escape_string($con, $_GET['top_name']);
    $sql = "SELECT image FROM top WHERE top_name='$top_name'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);

    if ($row && file_exists('../../../include/' . $row['image'])) {
        $image_path = '../../../include/' . $row['image'];
        echo json_encode(['success' => true, 'image_path' => $image_path]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}
?>
