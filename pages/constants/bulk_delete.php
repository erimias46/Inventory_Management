<?php
include_once '../../include/db.php';

if (isset($_POST['selected_ids']) && !empty($_POST['selected_ids'])) {

    $id= $_POST['id'];
    $ids = $_POST['selected_ids'];
    $db_name = $_POST['db_name'];
    $primary_key = $_POST['primary_key'];

    $ids = array_map('intval', $ids);
    $ids = implode(',', $ids);

    $query = "DELETE FROM $db_name WHERE $primary_key IN ($ids)";
    $result = mysqli_query($con, $query);

    if ($result) {
        echo "<script>window.location.href='constant2.php?id=$id&status=success';</script>";
    } else {
        echo "<script>window.location.href='constant2.php?id=$id&status=error';</script>";
    }
} else {
    echo "No rows selected for deletion.";
}
