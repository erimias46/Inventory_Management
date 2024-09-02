<?php
include_once '../../include/db.php';

if (isset($_GET['key'])) {
    $key = intval($_GET['key']);
    $sql = "DELETE FROM recent_order WHERE id = $key";
    if (mysqli_query($con, $sql)) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($con);
    }
} else {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}
?>
